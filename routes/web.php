<?php

use App\Http\Controllers\AwardsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ListsController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\UserSearchesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->to('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/auth-check', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class,'authCheck']);
    Route::get('/awards', [AwardsController::class,'index'])->name('awards.index');
    Route::get('/award/{id}', [AwardsController::class,'show'])->name('awards.show');

    Route::get('/sponsor', [AwardsController::class,'showSponsor'])->name('sponsor.show');

    Route::get('/plans', [PricingController::class, 'index'])->name('plans');
    Route::get('/checkout', [PricingController::class, 'checkoutIndex'])->name('checkout');
    Route::post('/subscribe-plan', [PricingController::class, 'subscribePlan'])->name('subscribe');
    Route::get('/purchase-success', [PricingController::class, 'purchaseSuccess'])->name('purchase.index');

    Route::get('/lists', [ListsController::class, 'index'])->name('lists.index');
    Route::get('/list/{list}', [ListsController::class, 'show'])->name('list.show');
    Route::post('/list-save', [ListsController::class, 'store'])->name('list.store');
    Route::post('/add-to-list', [ListsController::class, 'addAwardsToList'])->name('awardList.store');
    Route::post('/remove-from-list', [ListsController::class, 'removeAwardsFromList'])->name('awardList.remove');
    Route::post('/list/rename',[ListsController::class, 'renameList'])->name('list.rename');
    Route::post('/list/delete',[ListsController::class, 'deleteList'])->name('list.delete');
    Route::get('/get-lists',[ListsController::class, 'customerLists']);
    Route::get('/lists/{list}/export', [ListsController::class, 'exportAwards'])->name('lists.export');

    Route::get('/saved-searches', [UserSearchesController::class, 'index'])->name('searches.index');
    Route::get('/get-search-lists',[UserSearchesController::class, 'getSearchLists']);
    Route::post('/add-search-list', [UserSearchesController::class, 'addSearchList'])->name('search.list.create');
    Route::delete('/delete-list/{id}', [UserSearchesController::class, 'deleteSearchList'])->name('search.list.destroy');
    Route::put('/rename-list/{id}', [UserSearchesController::class, 'renameList'])->name('search.list.update');
    Route::post('/add-search-to-list', [UserSearchesController::class, 'addSearchToList'])->name('search.create');

    Route::get('/contact-us', [CommonController::class, 'index'])->name('contactUs');
    Route::post('/send-mail', [CommonController::class, 'sendMail'])->name('sendMail');
    Route::get('/confirmation', [CommonController::class, 'mailSuccess'])->name('mail.success');
});

require __DIR__.'/auth.php';
