<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\AboutSettingController;
use App\Http\Controllers\Dashboard\ClientProjectController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\HeroSettingController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\NoteController;
use App\Http\Controllers\Dashboard\PortfolioProjectController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\TestimonialController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/contact', [ContactController::class, 'submit'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard routes (protected)
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Settings
    Route::get('/settings/hero', [HeroSettingController::class, 'edit'])->name('settings.hero.edit');
    Route::put('/settings/hero', [HeroSettingController::class, 'update'])->name('settings.hero.update');
    Route::get('/settings/about', [AboutSettingController::class, 'edit'])->name('settings.about.edit');
    Route::put('/settings/about', [AboutSettingController::class, 'update'])->name('settings.about.update');

    // Portfolio Projects
    Route::resource('portfolio-projects', PortfolioProjectController::class)->except(['show']);

    // Services
    Route::resource('services', ServiceController::class)->except(['show']);

    // Testimonials
    Route::resource('testimonials', TestimonialController::class)->except(['show']);

    // CRM - Customers
    Route::resource('customers', CustomerController::class);

    // CRM - Customer Notes
    Route::post('/customers/{customer}/notes', [NoteController::class, 'store'])->name('customers.notes.store');
    Route::delete('/customers/{customer}/notes/{note}', [NoteController::class, 'destroy'])->name('customers.notes.destroy');

    // CRM - Client Projects
    Route::resource('client-projects', ClientProjectController::class);
    Route::post('/client-projects/{clientProject}/cancel', [ClientProjectController::class, 'cancel'])->name('client-projects.cancel');
    Route::get('/client-projects/{clientProject}/cancellation-document', [ClientProjectController::class, 'cancellationDocument'])->name('client-projects.cancellation-document');

    // CRM - Invoices (nested under client projects)
    Route::get('/client-projects/{client_project}/invoices/create', [InvoiceController::class, 'create'])->name('client-projects.invoices.create');
    Route::post('/client-projects/{client_project}/invoices', [InvoiceController::class, 'store'])->name('client-projects.invoices.store');
    Route::get('/client-projects/{client_project}/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('client-projects.invoices.edit');
    Route::put('/client-projects/{client_project}/invoices/{invoice}', [InvoiceController::class, 'update'])->name('client-projects.invoices.update');
    Route::delete('/client-projects/{client_project}/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('client-projects.invoices.destroy');
});
