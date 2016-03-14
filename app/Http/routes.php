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
Route::get('/', [
    'middleware' => 'auth',
    'as' => 'index',
    'uses' => 'ProfileController@show'
]);

// Authentication routes...
Route::get('auth/login', array('as' => 'getLogin', 'uses' => 'Auth\AuthController@getLogin'));
Route::post('auth/login', array('as' => 'postLogin', 'uses' => 'Auth\AuthController@postLogin'));
Route::get('auth/logout', array('as' => 'getLogout', 'uses' =>'Auth\AuthController@getLogout'));

// Registration routes...
Route::get('auth/register', array('as' => 'getRegister', 'uses' =>'Auth\AuthController@getRegister'));
Route::post('auth/register', array('as'=>'postRegister', 'uses' => 'Auth\AuthController@postRegister'));

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::controllers([
   'password' => 'Auth\PasswordController',
]);

Route::get('preview/{filename}', [
    'as' => 'preview', 'uses' => 'ProfileController@preview', function(){
    return View::make('preview');
}]);

Route::get('share/{hashed_name}', [
	'as' => 'share', 'uses' => 'ShareController@share', function(){
}]);

Route::group(['middleware' => 'auth'], function () {
    Route::any('file', 'FirstController@fileName' ); // For test purposes!!!
    Route::post('upload', 'UploadController@upload');
    Route::post('getfiles', 'ProfileController@getFiles');
    Route::post('currentfile', 'UploadController@currentfile');
    Route::post('routingfile', 'ProfileController@routingFiles');
    Route::post('deletefile', 'ProfileController@deleteUserFiles');
    Route::post('createfolder', 'ProfileController@createFolder');
    Route::post('deletefolder', 'ProfileController@deleteFolder');
    Route::post('getfolder', 'ProfileController@getFolder');
    Route::post('fileinfo', 'ProfileController@fileInfo');
    Route::post('downloadfile', ['as' => 'download', 'uses' => 'ProfileController@downloadUserFiles']);
    Route::post('downloadversion', ['as' => 'download', 'uses' => 'ProfileController@downloadVersions']);
    Route::post('selectedfiles', 'ProfileController@selectedFiles');
    Route::post('shareurl', 'ProfileController@getShareUrl');    
    Route::get('getimage/{filename}', [
	'as' => 'getImage', 'uses' => 'ProfileController@getImage', function(){
    }]);   
});
/*
Route::get('/', function () {  
    return View::make('index')->with('files', DB::table('files')->orderBy('created_at', 'desc')->get());
});
*/
 


