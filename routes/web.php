<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyRegistrationController;
use App\Http\Controllers\RequestInquiryController;
use App\Http\Controllers\FaqController;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/register', function () {
    return view('company-registration');
})->name('register');

Route::post('/register/step1', [CompanyRegistrationController::class, 'step1'])->name('register.step1');
Route::post('/register/step2', [CompanyRegistrationController::class, 'step2'])->name('register.step2');
Route::post('/register/step3', [CompanyRegistrationController::class, 'step3'])->name('register.step3');
Route::post('/register/step4', [CompanyRegistrationController::class, 'step4'])->name('register.step4');
Route::post('/register/complete', [CompanyRegistrationController::class, 'complete'])->name('register.complete');

Route::get('/inquiry', [RequestInquiryController::class, 'inquiry'])->name('inquiry');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');
Route::get('/api/company-activities', function() {
    return \App\Models\CompanyActivity::where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name']);
})->name('api.company-activities');

Route::get('/api/cities', function() {
    return \App\Models\City::where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name']);
})->name('api.cities');

Route::middleware('auth')->group(function () {
    Route::get('/qualification-request/{qualificationRequest}/pdf', [\App\Http\Controllers\QualificationRequestPdfController::class, 'generatePdf'])
        ->name('qualification-request.pdf');
    Route::get('/qualification-request/{qualificationRequest}/pdf/preview', [\App\Http\Controllers\QualificationRequestPdfController::class, 'previewPdf'])
        ->name('qualification-request.pdf.preview');
    
    // Document viewing routes
    Route::get('/documents/legal/{document}/view', [\App\Http\Controllers\DocumentViewController::class, 'viewLegalDocument'])
        ->name('documents.legal.view');
    Route::get('/documents/legal/{document}/download', [\App\Http\Controllers\DocumentViewController::class, 'downloadLegalDocument'])
        ->name('documents.legal.download');
    
    Route::get('/documents/technical/{document}/view', [\App\Http\Controllers\DocumentViewController::class, 'viewTechnicalDocument'])
        ->name('documents.technical.view');
    Route::get('/documents/technical/{document}/download', [\App\Http\Controllers\DocumentViewController::class, 'downloadTechnicalDocument'])
        ->name('documents.technical.download');
    
    Route::get('/documents/financial/{document}/view', [\App\Http\Controllers\DocumentViewController::class, 'viewFinancialDocument'])
        ->name('documents.financial.view');
    Route::get('/documents/financial/{document}/download', [\App\Http\Controllers\DocumentViewController::class, 'downloadFinancialDocument'])
        ->name('documents.financial.download');
});
