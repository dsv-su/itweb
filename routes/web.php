<?php

use App\Http\Controllers\SystemController;
use App\Services\Settings\AuthHandler;
use Illuminate\Support\Facades\Route;

if (class_exists(AuthHandler::class))
    $login = app()->make('SystemService')->authorize()->global->login_route;

//Setting for SU idplogin
Route::get('/sulogin', 'SystemController@SUlogin')->name('sulogin');
Route::get($login, [SystemController::class, 'login'])->name('login');

// Locale constraint
$langConstraint = 'en|sv|swe';

// Language switcher (explicit)
Route::get('/lang/{lang}', [\App\Http\Controllers\LocalizationController::class, 'index'])
    ->where('lang', $langConstraint)
    ->name('language');

/*
|--------------------------------------------------------------------------
| Travel Request
|--------------------------------------------------------------------------
*/
// Put the non-localized explicit route first
Route::get('/travel', [\App\Http\Controllers\TravelRequestController::class, 'create'])
    ->name('travel-request-create');

// Localized version (MUST constrain lang)
Route::get('/{lang}/travel', [\App\Http\Controllers\TravelRequestController::class, 'create'])
    ->where('lang', $langConstraint);

// Show + submit
Route::get('/travel/show/{travelRequest}', [\App\Http\Controllers\TravelRequestController::class, 'show'])
    ->name('travel-request-show');

Route::post('/travel', [\App\Http\Controllers\TravelRequestController::class, 'submit'])
    ->name('travel-submit');

Route::post('/travelresume/{tr}', [\App\Http\Controllers\TravelRequestController::class, 'resume'])
    ->name('travel-request-resume');

/*
|--------------------------------------------------------------------------
| Review
|--------------------------------------------------------------------------
*/
Route::get('/travel/review/{travelRequest}', [\App\Http\Controllers\ReviewController::class, 'show'])
    ->name('travel-request-review');

Route::post('/review/{travelRequest}', [\App\Http\Controllers\ReviewController::class, 'review'])
    ->name('review');

Route::post('/fo_review/{travelRequest}', [\App\Http\Controllers\ReviewController::class, 'fo_review'])
    ->name('fo_review');

/*
|--------------------------------------------------------------------------
| FO Handler
|--------------------------------------------------------------------------
*/
Route::get('/list', [\App\Http\Controllers\FOController::class, 'list'])
    ->name('request-list')
    ->middleware('checklang');

// Localized list (constrained)
Route::get('/{lang}/list', [\App\Http\Controllers\FOController::class, 'svlist'])
    ->where('lang', $langConstraint)
    ->middleware('checklang');

Route::get('/show/{id}', [\App\Http\Controllers\FOController::class, 'show'])
    ->name('fo-request-show');

// Localized show (FIX leading slash + constrain)
Route::get('/{lang}/show/{id}', [\App\Http\Controllers\FOController::class, 'show'])
    ->where('lang', $langConstraint);

Route::get('/viewpdf/{id}', [\App\Http\Controllers\FOController::class, 'pdfview'])
    ->name('travel-request-pdfview');

Route::get('/travel/pdf/{id}', [\App\Http\Controllers\FOController::class, 'download'])
    ->name('travel-request-pdf');

Route::get('/settings', [\App\Http\Controllers\FOController::class, 'settings'])
    ->name('settings');

Route::post('/fo', [\App\Http\Controllers\FOController::class, 'settings_fo'])
    ->name('fo');

Route::post('/fo_eu', [\App\Http\Controllers\FOController::class, 'settings_fo_eu'])
    ->name('fo_eu');

Route::get('/assign_fo', [\App\Http\Controllers\AssignFOController::class, 'index'])
    ->name('assign.fo');

/*
|--------------------------------------------------------------------------
| News list entries
|--------------------------------------------------------------------------
*/
Route::get('/en/newslist/{collection}', [\App\Http\Controllers\NewsListController::class, 'list'])
    ->name('newslist.en');

Route::get('/sv/newslist/{collection}', [\App\Http\Controllers\NewsListController::class, 'swelist'])
    ->name('newslist.sv');

/*
|--------------------------------------------------------------------------
| PP (Project Proposals)
|--------------------------------------------------------------------------
*/
//Project Proposals Home
//Route::get('/', [\App\Http\Controllers\ProposalController::class, 'pp'])->name('pp.home');

Route::prefix('pp')->name('pp.')->group(function () {
    // Create + submit
    Route::get('new', [\App\Http\Controllers\ProposalController::class, 'create'])->name('create');
    Route::post('submit', [\App\Http\Controllers\ProposalController::class, 'submit'])->name('submit');

    // Public / direct access by slug
    Route::get('{slug}', [\App\Http\Controllers\ProposalHomeController::class, 'pp'])
        ->where('slug', '[A-Za-z0-9\-]+')
        ->name('show');

    // Proposal lifecycle (prefer route model binding: {proposal})
    Route::get('complete/{proposal}', [\App\Http\Controllers\ProposalController::class, 'pp_complete'])->name('complete');
    Route::get('continue/{proposal}', [\App\Http\Controllers\ProposalController::class, 'pp_continue'])->name('continue');
    Route::get('edit/{proposal}', [\App\Http\Controllers\ProposalController::class, 'pp_edit'])->name('edit');
    Route::get('resume/{proposal}', [\App\Http\Controllers\ProposalController::class, 'pp_resume'])->name('resume');

    // Upload area
    Route::get('upload/{proposal}', [\App\Http\Controllers\ProposalController::class, 'upload'])->name('upload');

    // Decision endpoint
    Route::post('decision', [\App\Http\Controllers\ProposalController::class, 'decision'])->name('decision');

    // Status pages
    Route::get('sent/{proposal}', [\App\Http\Controllers\ProposalReportController::class, 'pp_sent'])->name('sent');
    Route::get('granted/{proposal}', [\App\Http\Controllers\ProposalReportController::class, 'pp_granted'])->name('granted');
    Route::get('rejected/{proposal}', [\App\Http\Controllers\ProposalReportController::class, 'pp_rejected'])->name('rejected');

    // Reviews
    Route::prefix('review')->name('review.')->group(function () {
        Route::get('view/{proposal}', [\App\Http\Controllers\ReviewController::class, 'pp_view'])->name('view');
        Route::get('{proposal}', [\App\Http\Controllers\ReviewController::class, 'pp_review'])->name('show');
    });

    // Stats
    Route::prefix('stats')->name('stats.')->group(function () {
        Route::get('committed', [\App\Http\Controllers\StatsController::class, 'preapproved'])->name('committed'); // fixed spelling
        Route::get('approved', [\App\Http\Controllers\StatsController::class, 'approved'])->name('approved');
        Route::get('recalc', [\App\Http\Controllers\StatsController::class, 'recalcBudget'])->name('recalc');
    });
});

/*
|--------------------------------------------------------------------------
| Downloads / docs
|--------------------------------------------------------------------------
*/
Route::get('budget/{type}', [\App\Http\Controllers\ProposalDownloadController::class, 'budget'])
    ->whereIn('type', ['eng','swe','eu']) // allowed types
    ->name('budget.template');

Route::get('manual', [\App\Http\Controllers\ProposalDownloadController::class, 'usermanual'])->name('usermanual');

/*
|--------------------------------------------------------------------------
| Vice settings
|--------------------------------------------------------------------------
*/
Route::prefix('vice-settings')
    ->name('vice_settings.')
    ->controller(\App\Http\Controllers\ViceController::class)
    ->group(function () {
        // Show the settings page
        Route::get('/', 'settings')->name('index');
        // Handle "oh" submission
        Route::post('oh', 'oh')->name('oh');
        // Handle "form" submission
        Route::post('form', 'form')->name('form');
        // Handle "registrator" submission
        Route::post('registrator', 'registrator')->name('registrator');
    });
/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'pp'])->name('pp.index');

    // TODO
    Route::delete('pp/{proposal}', [\App\Http\Controllers\AdminController::class, 'pp_delete'])->name('pp.delete');
});

/*
|--------------------------------------------------------------------------
| Maintenance / Test
|--------------------------------------------------------------------------
*/
Route::get('test', [\App\Http\Controllers\TestController::class, 'test'])->name('test');

// These should be POST and protected by auth + authorization, and ideally only in local/staging.
Route::get('/dev/seed', [\App\Http\Controllers\ViceController::class, 'seed'])->name('proposal.seed');
Route::get('/dev/reset', [\App\Http\Controllers\ViceController::class, 'reset'])->name('proposal.reset');

