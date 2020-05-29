<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'HomeController@test')->name('test');


Route::middleware(['auth'])->group(function () {

    Route::get('/quizzes', 'QuizController@index')->name('quiz.list');
    Route::get('/quiz/create', 'QuizController@create')->name('quiz.create');
    Route::post('/quiz/store', 'QuizController@store')->name('quiz.store');
    Route::get('/quiz/edit/{id}', 'QuizController@edit')->name('quiz.edit');
    Route::post('/quiz/update/{id}', 'QuizController@update')->name('quiz.update');
    Route::get('/quiz/start/{id}', 'QuizController@start')->name('quiz.start');
    Route::get('/quiz/delete/{id}', 'QuizController@destroy')->name('quiz.delete');
    Route::post('/quiz/api-show/{id}', 'QuizController@show')->name('quiz.api.show');


});