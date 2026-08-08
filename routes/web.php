<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    // Paginas reales del sitio, con slugs limpios (no /home/*)
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/experiencias', 'experiencias')->name('experiencias');
});

// Landing standalone de evento (identidad visual propia, sin header/footer de CLAN)
Route::view('/showclinic', 'showclinic')->name('showclinic');

    // demos
Route::prefix('home')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::get('/about','about')->name('about');
        Route::get('/blog','blog')->name('blog');
        Route::get('/blogDetails','blogDetails')->name('blogDetails');
        Route::get('/chef','chef')->name('chef');
        Route::get('/comming','comming')->name('comming');
        Route::get('/contact','contact')->name('contact');
        Route::get('/error','error404')->name('error404');
        Route::get('/gallery','gallery')->name('gallery');
        Route::get('/home2','home2')->name('home2');
        Route::get('/home3','home3')->name('home3');
        Route::get('/index','index')->name('index');
        Route::get('/meetTheChef','meetTheChef')->name('meetTheChef');
        Route::get('/portfolio','portfolio')->name('portfolio');
        Route::get('/portfolioDetails','portfolioDetails')->name('portfolioDetails');
        Route::get('/reservations','reservations')->name('reservations');
    });
});