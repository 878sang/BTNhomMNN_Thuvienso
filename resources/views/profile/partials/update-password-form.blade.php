<section x-data="passwordForm()">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Cập nhật mật khẩu
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Đảm bảo tài khoản của bạn sử dụng mật khẩu dài, ngẫu nhiên để giữ an toàn.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <x-input-label for="update_password_current_password" value="Mật khẩu hiện tại" />
            <div class="relative mt-1">
                <x-text-input id="update_password_current_password" name="current_password" x-bind:type="showCurrent ? 'text' : 'password'" class="block w-full pr-10" autocomplete="current-password" />
                <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-600 transition-colors focus:outline-none">
                    <svg x-show="!showCurrent" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showCurrent" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <x-input-label for="update_password_password" value="Mật khẩu mới" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" x-model="password" x-bind:type="showNew ? 'text' : 'password'" class="block w-full pr-10" autocomplete="new-password" />
                <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-600 transition-colors focus:outline-none">
                    <svg x-show="!showNew" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showNew" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
            
            <!-- Password Strength Indicator -->
            <div class="mt-3 text-sm bg-gray-50 p-3 rounded-md border border-gray-100 shadow-sm" x-show="password.length > 0" x-transition>
                <p class="font-medium text-gray-700 mb-2">Yêu cầu bảo mật:</p>
                <ul class="space-y-1.5">
                    <li class="flex items-center transition-colors duration-200" :class="hasLength ? 'text-green-600' : 'text-gray-500'">
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="hasLength ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" /></svg>
                        Tối thiểu 8 ký tự
                    </li>
                    <li class="flex items-center transition-colors duration-200" :class="hasUpper ? 'text-green-600' : 'text-gray-500'">
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="hasUpper ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" /></svg>
                        Ít nhất 1 chữ in hoa
                    </li>
                    <li class="flex items-center transition-colors duration-200" :class="hasNumber ? 'text-green-600' : 'text-gray-500'">
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="hasNumber ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" /></svg>
                        Ít nhất 1 chữ số
                    </li>
                </ul>
                
                <div class="mt-3 h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-500" :class="strengthClass" :style="'width: ' + strengthPercent + '%'"></div>
                </div>
            </div>
            
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="update_password_password_confirmation" value="Xác nhận mật khẩu" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" x-model="confirmPassword" x-bind:type="showConfirm ? 'text' : 'password'" class="block w-full pr-10" autocomplete="new-password" />
                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-600 transition-colors focus:outline-none">
                    <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showConfirm" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
            </div>
            
            <div class="mt-2 text-sm" x-show="confirmPassword.length > 0" x-transition>
                <p class="flex items-center font-medium" :class="passwordsMatch ? 'text-green-600' : 'text-red-500'">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="passwordsMatch" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        <path x-show="!passwordsMatch" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span x-text="passwordsMatch ? 'Mật khẩu trùng khớp' : 'Mật khẩu không trùng khớp'"></span>
                </p>
            </div>

            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <!-- Dùng x-bind:disabled để chặn nút khi chưa đủ điều kiện -->
            <x-primary-button x-bind:class="{ 'opacity-50 cursor-not-allowed': password.length > 0 && (!isStrong || !passwordsMatch) }">
                Lưu thay đổi
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium flex items-center bg-green-50 px-3 py-1.5 rounded-md"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Đã lưu thành công.
                </p>
            @endif
        </div>
    </form>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('passwordForm', () => ({
                showCurrent: false,
                showNew: false,
                showConfirm: false,
                password: '',
                confirmPassword: '',
                
                get hasLength() { return this.password.length >= 8; },
                get hasUpper() { return /[A-Z]/.test(this.password); },
                get hasNumber() { return /[0-9]/.test(this.password); },
                // Bỏ qua ký tự đặc biệt cho đỡ phức tạp, chỉ cần Hoa, Số, Dài >= 8
                
                get strengthScore() {
                    let score = 0;
                    if (this.hasLength) score++;
                    if (this.hasUpper) score++;
                    if (this.hasNumber) score++;
                    return score;
                },
                
                get strengthPercent() {
                    return (this.strengthScore / 3) * 100;
                },
                
                get strengthClass() {
                    if (this.strengthScore === 1) return 'bg-red-500';
                    if (this.strengthScore === 2) return 'bg-yellow-500';
                    return 'bg-green-500';
                },
                
                get isStrong() {
                    return this.strengthScore === 3;
                },
                
                get passwordsMatch() {
                    return this.password === this.confirmPassword && this.password.length > 0;
                }
            }))
        })
    </script>
</section>
