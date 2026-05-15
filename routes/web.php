<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books.index');
Route::get('/books/{slug}', [App\Http\Controllers\BookController::class, 'show'])->name('books.show');

Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::view('/cart', 'cart')->name('cart');
});

Route::middleware('auth')->group(function () {
    Route::post('/books/{book}/purchase', [App\Http\Controllers\BookController::class, 'purchase'])->name('books.purchase');
    Route::get('/recharge', [App\Http\Controllers\PaymentController::class, 'recharge'])->name('payment.recharge');
    Route::post('/checkout', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');

    Route::get('/my-profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::get('/my-transactions', [App\Http\Controllers\UserController::class, 'transactions'])->name('user.transactions');
    Route::get('/my-purchased', [App\Http\Controllers\UserController::class, 'purchased'])->name('user.purchased');
    Route::post('/books/{book}/rate', [App\Http\Controllers\BookRatingController::class, 'store'])->name('books.rate');
});

Route::get('/vnpay-return', [App\Http\Controllers\PaymentController::class, 'vnpayReturn'])->name('payment.vnpay_return');

// Admin Authentication
Route::get('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);

// Admin Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('authors', App\Http\Controllers\Admin\AuthorController::class);
    Route::resource('publishers', App\Http\Controllers\Admin\PublisherController::class);
    Route::resource('books', App\Http\Controllers\Admin\BookController::class);
    Route::post('books/{book}/approve', [App\Http\Controllers\Admin\BookController::class, 'approve'])->name('books.approve');
    Route::post('books/{book}/reject', [App\Http\Controllers\Admin\BookController::class, 'reject'])->name('books.reject');
    
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    Route::get('comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
    Route::delete('comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
    
    Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::put('contacts/{contact}/status', [App\Http\Controllers\Admin\ContactController::class, 'updateStatus'])->name('contacts.updateStatus');
    Route::delete('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::get('contacts/mark-all-read', [App\Http\Controllers\Admin\ContactController::class, 'markAllRead'])->name('contacts.markAllRead');
    
    Route::get('ratings', [App\Http\Controllers\Admin\RatingController::class, 'index'])->name('ratings.index');
    Route::delete('ratings/{rating}', [App\Http\Controllers\Admin\RatingController::class, 'destroy'])->name('ratings.destroy');
    
    Route::get('favorites', [App\Http\Controllers\Admin\FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('downloads', [App\Http\Controllers\Admin\DownloadController::class, 'index'])->name('downloads.index');
    
    Route::resource('links', App\Http\Controllers\Admin\LinkController::class);
    
    Route::get('transactions', [App\Http\Controllers\Admin\PointsTransactionController::class, 'index'])->name('transactions.index');
    Route::resource('sliders', App\Http\Controllers\Admin\SliderController::class);
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->prefix('my')->name('user.')->group(function () {
    Route::resource('books', App\Http\Controllers\User\BookController::class);
    Route::post('books/{book}/favorite', [App\Http\Controllers\BookController::class, 'toggleFavorite'])->name('books.favorite');
    Route::post('books/{book}/comment', [App\Http\Controllers\CommentController::class, 'store'])->name('books.comment');
    Route::delete('comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('delete.comment');
});

Route::get('/wishlist', [App\Http\Controllers\UserController::class, 'wishlist'])
    ->name('wishlist')
    ->middleware('auth');

Route::get('/books/{book}/download', [App\Http\Controllers\BookController::class, 'download'])->name('books.download');
Route::get('/books/{book}/preview-pdf', [App\Http\Controllers\BookController::class, 'previewPdf'])->name('books.preview_pdf');

Route::post('/ai/chat', [App\Http\Controllers\AiChatController::class, 'chat'])->name('ai.chat');

require __DIR__.'/auth.php';
