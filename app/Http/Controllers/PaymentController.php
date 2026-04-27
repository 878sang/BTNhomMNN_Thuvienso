<?php

namespace App\Http\Controllers;

use App\Models\PointsTransaction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function recharge()
    {
        return view('recharge');
    }

    public function checkout(Request $request)
    {
        $minRecharge = (int) (Setting::getVal('min_recharge') ?: 10000);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $minRecharge],
        ]);

        $vnpTmnCode = $this->getVnpTmnCode();
        $vnpHashSecret = $this->getVnpHashSecret();
        $vnpUrl = config('services.vnpay.url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnpReturnUrl = $this->getVnpReturnUrl();

        if (!$vnpTmnCode || !$vnpHashSecret) {
            return back()->withInput()->with('error', 'Thieu cau hinh VNPAY. Vui long cap nhat TmnCode va HashSecret trong trang quan tri.');
        }

        $vnpTxnRef = $this->generateTxnRef();
        $vnpOrderInfo = 'Nap diem cho nguoi dung ' . Auth::user()->name;
        $vnpAmount = (int) $request->amount * 100;
        $vnpBankCode = $request->bank_code;
        $createdAt = Carbon::now('Asia/Ho_Chi_Minh');
        $expireAt = (clone $createdAt)->addMinutes(15);

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnpTmnCode,
            'vnp_Amount' => $vnpAmount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => $createdAt->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $request->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => $vnpOrderInfo,
            'vnp_OrderType' => 'billpayment',
            'vnp_ReturnUrl' => $vnpReturnUrl,
            'vnp_TxnRef' => $vnpTxnRef,
            'vnp_ExpireDate' => $expireAt->format('YmdHis'),
        ];

        if (!empty($vnpBankCode)) {
            $inputData['vnp_BankCode'] = $vnpBankCode;
        }

        ksort($inputData);

        $hashData = http_build_query($inputData);
        $query = http_build_query($inputData);
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

        PointsTransaction::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'points' => ($request->amount / 1000) * (Setting::getVal('points_per_1000vnd') ?: 10),
            'type' => 'recharge',
            'status' => 'pending',
            'reference_id' => $vnpTxnRef,
        ]);

        return redirect($vnpUrl . '?' . $query . '&vnp_SecureHashType=SHA512&vnp_SecureHash=' . $vnpSecureHash);
    }

    public function vnpayReturn(Request $request)
    {
        $vnpHashSecret = $this->getVnpHashSecret();

        if (!$vnpHashSecret || !$request->has('vnp_SecureHash')) {
            return redirect('/')->with('error', 'Khong the xac thuc giao dich do thieu cau hinh chu ky.');
        }

        $inputData = $request->query();
        $vnpSecureHash = $inputData['vnp_SecureHash'];

        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

        if (!hash_equals($secureHash, $vnpSecureHash)) {
            return redirect('/')->with('error', 'Chu ky thanh toan khong hop le.');
        }

        $transaction = PointsTransaction::where('reference_id', $request->vnp_TxnRef)->first();

        if (!$transaction) {
            return redirect('/')->with('error', 'Khong tim thay giao dich nap diem.');
        }

        if ($request->vnp_ResponseCode === '00') {
            if ($transaction->status === 'pending') {
                $transaction->update(['status' => 'completed']);
                $transaction->user->increment('points', $transaction->points);

                return redirect('/')->with('success', 'Nap diem thanh cong! So du tai khoan da duoc cap nhat.');
            }

            return redirect('/')->with('success', 'Giao dich da duoc xu ly truoc do.');
        }

        if ($transaction->status === 'pending') {
            $transaction->update(['status' => 'failed']);
        }

        return redirect('/')->with('error', 'Thanh toan khong thanh cong hoac da bi huy.');
    }

    private function getVnpTmnCode(): ?string
    {
        return Setting::getVal('vnp_TmnCode') ?: config('services.vnpay.tmn_code');
    }

    private function getVnpHashSecret(): ?string
    {
        return Setting::getVal('vnp_HashSecret') ?: config('services.vnpay.hash_secret');
    }

    private function getVnpReturnUrl(): string
    {
        return config('services.vnpay.return_url') ?: route('payment.vnpay_return');
    }

    private function generateTxnRef(): string
    {
        return Carbon::now('Asia/Ho_Chi_Minh')->format('YmdHis') . random_int(1000, 9999);
    }
}
