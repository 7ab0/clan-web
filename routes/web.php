<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowClinicGuestController;
use App\Http\Controllers\ShowClinicAdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationAdminController;
use App\Http\Controllers\ReservationReviewController;
use App\Http\Controllers\MaintenanceController;

Route::controller(MaintenanceController::class)->group(function () {
    Route::get('/mantenimiento', 'show')->name('maintenance.show');
    Route::post('/mantenimiento', 'unlock')->name('maintenance.unlock');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    // Paginas reales del sitio, con slugs limpios (no /home/*)
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/experiencias', 'experiencias')->name('experiencias');
});

// Íntimo: landing, reserva y pago (universo CLAN, mismo layout base)
Route::get('/intimo/{token?}', [EventController::class, 'intimo'])->name('intimo');
Route::post('/intimo/reservar', [ReservationController::class, 'store'])->name('intimo.reservar');

// Fermento (CLAN x FORNO): landing y reserva de mesa por fecha
Route::get('/fermento/{token?}', [EventController::class, 'fermento'])->name('fermento');
Route::post('/fermento/reservar', [ReservationController::class, 'store'])->name('fermento.reservar');

Route::prefix('reservas/{code}')->group(function () {
    Route::get('/pago', [PaymentController::class, 'show'])->name('reservas.pago');
    Route::get('/confirmacion', [PaymentController::class, 'confirmation'])->name('reservas.confirmacion');
});

// Panel admin de reservas (Fermento + Íntimo): confirma manualmente el pago
// de la seña coordinado por WhatsApp. Protegido por contraseña simple,
// mismo patrón que showclinic/admin.
Route::prefix('reservas/admin')->name('reservas.admin.')->group(function () {
    Route::get('/login', [ReservationAdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [ReservationAdminController::class, 'login'])->name('login.submit');
    Route::post('/logout', [ReservationAdminController::class, 'logout'])->name('logout');

    Route::middleware('reservas.admin')->group(function () {
        Route::get('/', [ReservationAdminController::class, 'index'])->name('index');
        Route::post('/', [ReservationAdminController::class, 'storeReservation'])->name('store');
        Route::post('/{reservation}/confirmar', [ReservationAdminController::class, 'confirmPayment'])->name('confirmar');
        Route::put('/{reservation}', [ReservationAdminController::class, 'updateReservation'])->name('update');
        Route::delete('/{reservation}', [ReservationAdminController::class, 'destroy'])->name('destroy');
        Route::get('/{reservation}/historial', [ReservationAdminController::class, 'history'])->name('history');
        Route::get('/clientes', [ReservationAdminController::class, 'customers'])->name('clientes');
        Route::put('/clientes/{customer}', [ReservationAdminController::class, 'updateCustomer'])->name('clientes.update');

        Route::get('/invitados', [ReservationAdminController::class, 'guests'])->name('guests');
        Route::post('/invitados', [ReservationAdminController::class, 'storeGuest'])->name('guests.store');
        Route::put('/invitados/{guest}', [ReservationAdminController::class, 'updateGuest'])->name('guests.update');
        Route::post('/invitados/{guest}/mensaje1', [ReservationAdminController::class, 'markMensaje1'])->name('guests.mensaje1');
        Route::post('/invitados/{guest}/aceptar', [ReservationAdminController::class, 'toggleAceptado'])->name('guests.aceptar');
        Route::post('/invitados/{guest}/mensaje2', [ReservationAdminController::class, 'markMensaje2'])->name('guests.mensaje2');
    });
});

// Panel de solo revisión (reservas ya confirmadas), contraseña propia y
// distinta de reservas.admin — pensado para compartir con FORNO/MOLTO.
Route::prefix('reservas/revision')->name('reservas.review.')->group(function () {
    Route::get('/login', [ReservationReviewController::class, 'loginForm'])->name('login');
    Route::post('/login', [ReservationReviewController::class, 'login'])->name('login.submit');
    Route::post('/logout', [ReservationReviewController::class, 'logout'])->name('logout');

    Route::middleware('reservas.review')->group(function () {
        Route::get('/', [ReservationReviewController::class, 'index'])->name('index');
    });
});

// Landing standalone de evento (identidad visual propia, sin header/footer de CLAN)
Route::get('/showclinic', [ShowClinicGuestController::class, 'show'])->name('showclinic');
Route::post('/showclinic/confirmar', [ShowClinicGuestController::class, 'confirm'])->name('showclinic.confirmar');

// Panel de administración de invitados (protegido por contraseña simple)
Route::prefix('showclinic/admin')->name('showclinic.admin.')->group(function () {
    Route::get('/login', [ShowClinicAdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [ShowClinicAdminController::class, 'login'])->name('login.submit');
    Route::post('/logout', [ShowClinicAdminController::class, 'logout'])->name('logout');

    Route::middleware('showclinic.admin')->group(function () {
        Route::get('/', [ShowClinicAdminController::class, 'index'])->name('index');
        Route::post('/guests', [ShowClinicAdminController::class, 'store'])->name('guests.store');
        Route::put('/guests/{guest}', [ShowClinicAdminController::class, 'update'])->name('guests.update');
        Route::delete('/guests/{guest}', [ShowClinicAdminController::class, 'destroy'])->name('guests.destroy');
        Route::patch('/guests/{guest}/toggle', [ShowClinicAdminController::class, 'toggle'])->name('guests.toggle');
    });
});

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