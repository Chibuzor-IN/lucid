<?php

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
    return view('welcome');
});

Route::get('/home', function () {
    return view('welcome');
});
Route::get('login', function () {
    return view('auth/login');
});
Route::get('register', function () {
    return view('auth/register');
});
Route::get('explore', function () {
    return view('explore');
});
Route::get('loader', function () {
    return view('preloader');
});
Route::get('subscribe', function () {
    return view('subscribe');
});
Route::get('fix','HomeController@fix');
Route::get('post/{username}','HomeController@checkpost');
Route::get('feed/{username}','HomeController@checkfeed');
Route::get('dropfeed','HomeController@dropfeed');
Route::get('loadfeed/{username}','HomeController@loadfeed');
Route::get('postFixer/','HomeController@postFixer');
//Route::get('oldfeed','HomeController@old');
//Route::get('newfeed','HomeController@new');



Route::get('under-construction', 'pageController@construction')->name('under-construction');
Route::get('microblog','HomeController@microblog');
Route::post('save-post','HomeController@savePost');
Route::post('save-subscription','pageController@saveSubscriptionEmail');

Route::get('/filter/{method}','pageController@filterPost');



Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::post('login', 'LoginController@do')->name('login');

Route::prefix('{username}')->group(function () {


    Route::get('/contact', 'pageController@contact');
    Route::get('/post/{postTitle}','pageController@singlePostPage');
    Route::get('/post-data/{id}','pageController@getPostData');
    Route::get('/','pageController@homePage');
    Route::get('/home','pageController@homePage');
    Route::get('/thoughts','pageController@thoughts');
    Route::post('/save-post','HomeController@savePost');
    Route::get('/logout', "Auth\LoginController@logout");
    Route::get('/posts','pageController@posts');

    Route::get('/subscribe','HomeController@subscribe');
    Route::post('/addrss','ExtRssController@addRss');
    Route::post('/unfollow','ExtRssController@unfollow');
    Route::post('/extrss','ExtRssController@addExtRss');

    Route::post('/publish','HomeController@publish');
    Route::post('/send-mail','SendEmailController@sendEmail');
    Route::get('/settings', 'HomeController@settings');

    Route::post('/save_settings','HomeController@saveSettings');
    Route::get('/following','pageController@following')->name("following");
    Route::get('/followers','pageController@followers')->name("followers");
    Route::post('/update-contact-details','HomeController@updateContactDetails');
    Route::post('/delete-post','HomeController@deletePost')->name('deletePost');
    Route::get('/comments/{post_id}','pageController@comments')->name('comment');
    Route::post('/save-comment','HomeController@saveComment')->name('save-comment');
    Route::post('/notif','pageController@notification')->name('notif');
    Route::get('/notif','pageController@notification');
    Route::post('/edit-post','HomeController@editPost');
    Route::get('/replies','pageController@reply')->name('reply');

});
