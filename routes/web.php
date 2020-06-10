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

// M01 - Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('redirect','Auth\SocialAuthGoogleController@redirect');
Route::get('callback','Auth\SocialAuthGoogleController@callback');


Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name("password.form");
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name("password.email");
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name("password.reset");
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name("password.update");

// M02 - Individual Profile
Route::get('users/{id}', 'UserController@show');
Route::get('users/{id}-{name?}', 'UserController@show')->name('profile');
Route::put('api/user/info', 'UserController@update_info');
Route::put('api/user/email', 'UserController@update_email');
Route::put('api/user/password', 'UserController@update_password');
Route::delete('user', 'UserController@delete');

Route::get('api/users/{id}', 'UserController@post_api'); // Extra APIs

// M03 - Friends
Route::get('users/{id}/friends', 'UserController@show_friends');
Route::get('users/{id}-{name?}/friends', 'UserController@show_friends')->name('friends');
Route::get('friends/requests', 'FriendController@show_friend_requests')->name('friends.requests');

Route::post('api/friends/requests', 'FriendController@sendRequest');
Route::post('api/friends/requests/{id}', 'FriendController@respondRequest');
Route::delete('api/friends/{id}', 'FriendController@delete');
Route::post('api/friends/groups', 'GroupController@create')->name('groups.create');
Route::put('api/friends/groups/{id}/{friend_id}', 'GroupController@add_member');
Route::delete('api/friends/groups/{id}/{friend_id}', 'GroupController@remove_member');
Route::delete('api/friends/groups/{id}', 'GroupController@delete')->name('groups.delete');
Route::put('api/friends/groups/{id}', 'GroupController@rename')->name('groups.edit');

// M04 - Content
Route::get('hot', 'PostController@hot')->name('hot');
Route::get('feed', 'PostController@feed')->name('feed');
Route::get('post/{id}', 'PostController@show');
Route::get('post/{id}-{name?}', 'PostController@show')->name('post');
Route::get('search', 'SearchController@search')->name('search');

Route::get('api/hot', 'PostController@hot_api'); // Extra APIs
Route::get('api/feed', 'PostController@feed_api');
Route::get('api/post/{id}', 'PostController@post_api');
Route::get('api/search', 'SearchController@search_api');

Route::post('api/post', 'PostController@create')->name('post.create');
Route::post('api/post/{id}/comment', 'CommentController@create')->name('comment.create');

Route::put('api/post/{id}', 'PostController@update');
Route::put('api/post/{post_id}/comment/{comment_id}', 'CommentController@update');

Route::delete('api/post/{id}', 'PostController@delete')->name('post.delete');
Route::delete('api/post/{post_id}/comment/{comment_id}', 'CommentController@delete')->name('comment.delete');

Route::post('api/content/{id}/appraisal', 'ContentController@addAppraisal');
Route::delete('api/content/{id}/appraisal', 'ContentController@deleteAppraisal');
Route::post('api/content/{id}/report', 'ContentController@report');


// M05
Route::get('messages', 'MessageController@show_messages')->name('messages');
Route::get('conversation/{id}-{name?}', 'MessageController@show_conversation')->name('conversation');
Route::get('api/messages/{id}', 'MessageController@get_user_messages');

Route::post('api/messages/{id}', 'MessageController@send_message');
Route::post('api/messages/{id}/seen', 'MessageController@see_messages');

Route::delete('api/messages/{id}', 'MessageController@delete_message');

// M06 - Notification
Route::delete('api/notifications/{id}', 'NotificationsController@deleteNotification');


// M07 - Administration
Route::get('admin', 'AdminController@home')->name('admin');
Route::get('admin/announcements', 'AdminController@announcements')->name('admin.announcements');
Route::post('api/admin/announcements', 'AnnouncementController@create')->name('announcement.create');
Route::delete('api/admin/announcements/{id}', 'AnnouncementController@delete');

Route::get('admin/reports', 'AdminController@reports')->name('admin.reports');
Route::delete('api/admin/reports/{id}', 'ReportController@clear');

Route::get('admin/users', 'AdminController@users')->name('admin.users');
Route::put('api/users/{id}/ban', 'AdminController@ban_user')->name('admin.ban');
Route::put('api/users/{id}/unban', 'AdminController@unban_user')->name('admin.unban');

// M08 - Static Pages
Route::get('/', 'StaticPageController@home')->name('home');
Route::get('about', 'StaticPageController@about')->name('about');
Route::get('403', 'StaticPageController@e403');
Route::get('404', 'StaticPageController@e404');
