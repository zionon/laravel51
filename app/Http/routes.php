<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('phpinfo', function () {
	phpinfo();
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('admin', 'HomeController@index');
});

Route::get('/hello/{id}', function ($id) {
	echo 'hello ' . $id;
});

Route::get('/id/{id}/name/{name}', function ($id, $name) {
	echo "hello " . $name . " id=" . $id;
});

Route::get('/hello/laravel', ['as' => 'laravel', function () {
	echo "hello laravel";
}]);

Route::get('/as', function () {
	return route('laravel');
});

Route::get('/redirect', function () {
	return redirect()->route('laravel');
});

Route::get('/name/{name}', ['as' => 'name', function ($name) {
	echo "my name is " . $name;
}]);

Route::get('/rename/{name}', function ($name) {
	return redirect()->route('name', $name);
});

//test middleware
Route::group(['middleware' => 'test:male'], function () {
	Route::get('/middleware/write', function () {
		echo "this is middleware write";
	});

	Route::get('/middleware/update', function () {
		echo "this is middleware update";
	});
});

Route::get('/age/refuse', ['as' => 'refuse', function () {
	echo "18岁以上男子才能访问！！";
}]);

//Sub-Domain Routing
Route::group(['domain' => '{service}.laravel51.com'], function () {
	Route::get('/write/domain', function ($service) {
		return "Write FROM {$service}.laravel51.com";
	});

	Route::get('/update/domain', function ($service) {
		return "Update FROM {$service}.laravel51.com";
	});
});

//CSRF
Route::get('testCsrf',function(){
    $csrf_field = csrf_field();
    $html = <<<GET
        <form method="POST" action="/testCsrf">
            <input type="submit" value="Test"/>
        </form>
GET;
    return $html;
});

Route::post('testCsrf', function () {
	return 'success';
});

//Implicit Controllers
Route::controller('request', 'RequestController');

//response
Route::get('testResponse', function () {
	$content = 'Hello Laravel';
	$status = 200;
	$value = 'text/html;charset=utf-8';
	// return response($content, $status)->header('Content-Type', $value)->withCookie('site', 'Laravel51.com', 30, '/', 'laravel.app');
	// return response()->view('test/hello', ['message' => 'Hello Laravel51'])->header('Content', $value);
	// return view('test/hello', ['message' => 'Hello World']);
	// return response()->json(['name' => 'laravel51', 'passwd' => 'laravel51.com']);
	return response()->download(
		realpath(base_path('public/images')) . '/IMG_1501.JPG',
		'Laravel.jpg'
	);
});

Route::get('testResponseRedirect', function () {
	return redirect()->route('laravel');
});

//share data
Route::get('testViewHello', function () {
	return view('test/hello');
});

Route::get('testViewHome', function () {
	return view('test/home');
});

//provider
Route::resource('test', 'TestController');

//learn database facade
Route::get('database/insert', 'DatabaseController@insert');
Route::get('database/select', 'DatabaseController@select');
Route::get('database/update', 'DatabaseController@update');
Route::get('database/delete', 'DatabaseController@delete');
Route::get('database/statement', 'DatabaseController@statement');

//learn database Query Builder
Route::get('qb/insert', 'QbController@insert');
Route::get('qb/select', 'QbController@select');
Route::get('qb/update', 'QbController@update');
Route::get('qb/delete', 'QbController@delete');

//learn Eloquent ORM
Route::get('eloquent', 'PostController@index');
Route::get('eloquent/create', 'PostController@create');
Route::get('eloquent/save', 'PostController@savedata');
Route::get('eloquent/createdata', 'PostController@createdata');
Route::get('eloquent/updatedata', 'PostController@updatedata');
Route::get('eloquent/updatecreate', 'PostController@updatecreate');

// auth route
Route::group(['namespace' => 'auth'], function () {
	//Authentication routes
	Route::get('login', 'AuthController@getLogin');
	Route::post('login', 'AuthController@postLogin');
	Route::get('logout', 'AuthController@getLogout');

	//Registration routes
	Route::get('register', 'AuthController@getRegister');
	Route::post('register', 'AuthController@postRegister');

	//Password reset link request routes
	Route::get('password/email', 'PasswordController@getEmail');
	Route::post('password/email', 'PasswordController@postEmail');

	//Password reset routes
	Route::get('password/reset/{token}', 'PasswordController@getReset');
	Route::post('password/reset', 'PasswordController@postReset');

	//github
	Route::get('/auth/github', 'AuthController@redirectToProvider');
	Route::get('/auth/github/callback', 'AuthController@handleProviderCallback');
});
