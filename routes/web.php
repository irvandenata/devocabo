<?php

use App\Http\Controllers\Auth\LoginController;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

 Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\GroupWordController::class, 'index'])->name('group-words.index');
    Route::get('/explore', [App\Http\Controllers\GroupWordController::class, 'search'])->name('group-words.explore');
    Route::get('/a', [App\Http\Controllers\GroupWordController::class, 'index'])->name('group-words.create');
    Route::get('/group-words/{id}/edit', [App\Http\Controllers\GroupWordController::class, 'edit'])->name('group-words.edit');
    Route::post('/group-words/store', [App\Http\Controllers\GroupWordController::class, 'store'])->name('group-words.store');
    Route::post('/group-words/{id}/update', [App\Http\Controllers\GroupWordController::class, 'update'])->name('group-words.update');
    Route::delete('/group-words/destroy/{id}', [App\Http\Controllers\GroupWordController::class, 'destroy'])->name('group-words.destroy');
    Route::get('/group-words/clone/{id}', [App\Http\Controllers\GroupWordController::class, 'clone'])->name('group-words.clone');

    Route::get('/group-words/{id}', [App\Http\Controllers\WordController::class, 'index'])->name('words.index');
    Route::get('/group-words/{id}/show', [App\Http\Controllers\WordController::class, 'show'])->name('words.show');
    Route::post('/group-words/{id}', [App\Http\Controllers\WordController::class, 'store'])->name('words.store');
    Route::delete('/words/{id}', [App\Http\Controllers\WordController::class, 'destroy'])->name('words.destroy');
    Route::put('/words-change/{word}/{group}/{lang}', [App\Http\Controllers\WordController::class, 'changeType'])->name('words.change');
    // Route::get('/group-words/{id}', [App\Http\Controllers\WordController::class, 'index'])->name('words.create');
    // Route::get('/group-words/{id}', [App\Http\Controllers\WordController::class, 'index'])->name('words.store');
    // Route::get('/group-words/{id}', [App\Http\Controllers\WordController::class, 'index'])->name('words.create');
 });

 Route::get('/google/callback', [App\Http\Controllers\GoogleController::class, 'callback']);
 Route::get('/google', [App\Http\Controllers\GoogleController::class, 'redirect']);


Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.search');
Route::post('/get-in-touch', [App\Http\Controllers\LandingController::class, 'getInTouch'])->name('get-in-touch');
Route::get('/projects', [App\Http\Controllers\BlogController::class, 'project'])->name('projects.search');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/tos', function () {
    $data['tos'] = Setting::where('config_key', 'tos')->first();
    return view('tos', $data);
});


Route::get('count-view',function(){
    $countActivity = \App\Models\Activity::where('date',date('Y-m-d'))->first();
    if($countActivity){
        $countActivity->count_view = $countActivity->count_view + 1;
        $countActivity->save();
    }else{
        $countActivity = new \App\Models\Activity();
        $countActivity->count_view = 1;
        $countActivity->date = date('Y-m-d');
        $countActivity->save();
    }
    return response()->json(['status' => 'success']);
})->name('count-view');

Route::get('/leaderboard', [App\Http\Controllers\Api\ResultTestController::class, 'getLeaderboard']);
Route::get('/masuk-dulu-ges', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/masuk-dulu-ges', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('google', [App\Http\Controllers\GoogleController::class, 'redirect']);
Route::get('google/callback', [App\Http\Controllers\GoogleController::class, 'callback']);

Route::get('/remove-duplicate',function(){
    $groupWords = \App\Models\GroupWord::all();
    foreach($groupWords as $groupWord){
        $words = \App\Models\Word::where('group_word_id',$groupWord->id)->get();
        $wordArray = [];
        foreach($words as $word){
            if(in_array($word->word,$wordArray)){
                $word->delete();
            }else{
                $wordArray[] = $word->word;
            }
        }
    }
    return "success";
});

// Route::prefix('admin')->as('admin.')->middleware('auth') ->group(function () {
//     Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('users', App\Http\Controllers\Admin\UserController::class);
//     Route::get('/users/{id}/change-show', [App\Http\Controllers\Admin\UserController::class, 'changeShow']);

//     Route::resource('additional-types', App\Http\Controllers\Admin\AdditionalTypeController::class);
//     Route::get('/additional-types/{id}/change-show', [App\Http\Controllers\Admin\AdditionalTypeController::class, 'changeShow']);

//     Route::resource('messages', App\Http\Controllers\Admin\MessageController::class);


//     Route::resource('additional-infos', App\Http\Controllers\Admin\AdditionalInfoController::class);
//     Route::get('/additional-infos/{id}/change-show', [App\Http\Controllers\Admin\AdditionalInfoController::class, 'changeShow']);

//     Route::resource('settings', App\Http\Controllers\Admin\SettingController::class);
//     Route::resource('article-categories', App\Http\Controllers\Admin\ArticleCategoryController::class);
//     Route::get('/article-categories/{id}/change-show', [App\Http\Controllers\Admin\ArticleCategoryController::class, 'changeShow']);
//     Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class);
//     Route::get('/articles/{id}/change-show', [App\Http\Controllers\Admin\ArticleController::class, 'changeShow']);
//     Route::prefix('test')->group(function () {
//         Route::resource('questions', App\Http\Controllers\Admin\QuestionController::class);
//         Route::get('/questions/{id}/change-show', [App\Http\Controllers\Admin\QuestionController::class, 'changeShow']);
//         Route::resource('categories-question', App\Http\Controllers\Admin\CategoryQuestionController::class);
//         Route::get('/categories-question/{id}/change-show', [App\Http\Controllers\Admin\CategoryQuestionController::class, 'changeShow']);
//     });




//     Route::post('user/detail/{id}', [App\Http\Controllers\Admin\UserController::class, 'updateDetail'])->name('admin.user.update');

//     //setting
//     Route::resource('category', App\Http\Controllers\Admin\CategoryController::class);
//     Route::resource('word', App\Http\Controllers\Admin\WordController::class);
//     Route::resource('category-word', App\Http\Controllers\Admin\CategoryWordController::class);
//     Route::get('/api/word/search', [App\Http\Controllers\Admin\WordController::class, 'getWord']);

// });
Route::get('/test', [App\Http\Controllers\Api\ApiController::class, 'test']);
Route::get('/gabut', function () {
    return view('gabut');
});
Route::get('/privacy', function(){
return view('privacy');
})->name('home');

Route::middleware('role')->group(function () {
    Route::get('/delete-account', [App\Http\Controllers\DeleteAccountController::class, 'index']);
    Route::post('/delete-account', [App\Http\Controllers\DeleteAccountController::class, 'delete'])->name('delete');
});


Route::get('/clear-cache',function(){
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');
    return "Cache is cleared";
});

Route::get('/stroage-link', function () {
    Artisan::call('storage:link');
    return "storage link created";
});

Route::get('/generate-sitemap',function(){
    Artisan::call('sitemap:generate');
    return "sitemap-generated";
});

Route::get('/sitemap', [App\Http\Controllers\LandingController::class, 'sitemap'])->name('sitemap');



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
