<?php

use App\Http\Controllers\Admin\TwoFactorAuthenticationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClassroomPeopleController;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\Webhooks\StripeController;
use App\Models\Classwork;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthenticatedSessionController::class , 'create']);

Route::get('/admin/2fa' , [TwoFactorAuthenticationController::class , 'create']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// require __DIR__.'/auth.php';

Route::get('plans' , [PlansController::class , 'index'])->name('plans');

Route::middleware(['auth'])->group(function(){

    Route::get('subscription/{subscription}/pay' , [PaymentsController::class , 'create'])->name('checkout');


    Route::post('subscriptions' , [SubscriptionsController::class , 'store'])->name('subscriptions.store');

    Route::post('payments/{subscription}' , [PaymentsController::class , 'store'])->name('payments.store');

    Route::get('/payments/{subscription}/success' , [PaymentsController::class , 'success'])->name('payments.success');
    Route::get('/payments/{subscription}/cancel' , [PaymentsController::class , 'cancle'])->name('payments.cancle');
    
    Route::prefix('/classrooms/trashed')
    ->as('classrooms.')
    ->controller(ClassroomsController::class)
    ->group(function(){
        Route::get('/' , 'trashed')->name('trashed');
        Route::put('/{classroom}' , 'restore')->name('restore');
        Route::delete('/{classroom}' , 'forceDelete')->name('force-delete');
    });

    Route::prefix('/topics/trashed')
    ->as('topics.')
    ->controller(TopicsController::class)
    ->group(function(){
        Route::get('/' , 'trashed')->name('trashed');
        Route::put('/{topic}' , 'restore')->name('restore');
        Route::delete('/{topic}' , 'forceDelete')->name('force-delete');
    });


    Route::get('/classrooms/{classroom}/join' , [JoinClassroomController::class , 'create'])
    ->middleware('signed')
    ->name('classrooms.join');
    Route::post('/classrooms/{classroom}/join' , [JoinClassroomController::class , 'store']);
    
    
    Route::get('/topics/create/{classroom}' , [TopicsController::class ,'create'])->name('topics.create');
    Route::post('/topics' , [TopicsController::class , 'store'])->name('topics.store');
    Route::get('/topics/{id}' , [TopicsController::class ,'show'])->name('topics.show')->where(['id' => '\d+']);
    Route::get('/topics/{id}/edit' , [TopicsController::class ,'edit'])->name('topics.edit')->where(['id' => '\d+']);
    Route::put('/topics/{id}' , [TopicsController::class ,'update'])->name('topics.update')->where(['id' => '\d+']);
    Route::delete('/topics/{id}' , [TopicsController::class ,'destroy'])->name('topics.destroy')->where(['id' => '\d+']);
    
    Route::get('classrooms/{classroom}/chat', [ClassroomsController::class, 'chat'])->name('classrooms.chat');

    Route::resource('/classrooms' , ClassroomsController::class);

    Route::resource('classrooms.classworks' , ClassworkController::class);


    Route::get('/classrooms/{classroom}/people' , [ClassroomPeopleController::class , 'index'])->name('classrooms.people');
    Route::delete('/classrooms/{classroom}/people' , [ClassroomPeopleController::class , 'destroy'])->name('classrooms.people.destroy');

    Route::post('comments' , [CommentController::class , 'store'])->name('comments.store');

    Route::post('posts/{classroom}' , [PostController::class , 'store'])->name('posts.store');
    Route::delete('posts/{classroom}' , [PostController::class , 'destroy'])->name('posts.destroy');
    Route::post('posts/{post}/comments', [PostController::class , 'store'])->name('posts.comments.store');
    
    Route::post('classwork/{classwork}/submissions', [SubmissionController::class , 'store'])
    ->name('submissions.store');
    // ->middleware('can:create,App\Models\Classwork');

    Route::get('submissions/{submission}/file', [SubmissionController::class , 'file'])->name('submissions.file');

    Route::get('lang/{locale}', [LanguageController::class , 'changeLanguage'])->name('lang.switch');

});

Route::post('/payments/stripe/webhook' , StripeController::class);