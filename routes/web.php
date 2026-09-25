<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EvaluationFactorController;
use App\Http\Controllers\PositionEvaluationController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryScaleController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\PositionApprovalController;
use App\Http\Controllers\PublicVacancyController;
use App\Http\Controllers\PublicApplicationController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ApplicantRankingController;
use App\Http\Controllers\HiringController;
use App\Http\Controllers\RecruitmentDashboardController;
use App\Http\Controllers\RecruitmentReportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;


/*
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('public.landing');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth', 'role:hr_admin,hr_administrator,hr_manager'])->group(function () {
    Route::get('/hr/dashboard', [DashboardController::class, 'index'])
        ->name('hr.dashboard');
});

Route::middleware(['auth', 'role:evaluator'])->group(function () {
    Route::get('/evaluator/dashboard', [DashboardController::class, 'index'])
        ->name('evaluator.dashboard');
});

Route::middleware(['auth', 'role:committee_member,committee'])->group(function () {
    Route::get('/committee/dashboard', [DashboardController::class, 'index'])
        ->name('committee.dashboard');
});


/*
|--------------------------------------------------------------------------
| Admin Test
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin-test', function () {
        return 'Admin access successful!';
    })->name('admin.test');

});


/*
|--------------------------------------------------------------------------
| Department Routes
|--------------------------------------------------------------------------
*/

// Routes available to authenticated users
Route::middleware(['auth'])->group(function () {

    Route::get('/departments', [DepartmentController::class, 'index'])
        ->name('departments.index');

});
Route::middleware(['auth'])->group(function () {

    Route::get('/departments', [DepartmentController::class, 'index'])
        ->name('departments.index');

    Route::get('/departments/create', [DepartmentController::class, 'create'])
        ->middleware('role:admin')
        ->name('departments.create');

    Route::get('/departments/{department}', [DepartmentController::class, 'show'])
        ->name('departments.show');

});


// Routes available only to administrators
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::post('/departments', [DepartmentController::class, 'store'])
        ->name('departments.store');

    Route::get('/departments/{department}', [DepartmentController::class, 'show'])
        ->name('departments.show');

    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])
        ->name('departments.edit');

    Route::put('/departments/{department}', [DepartmentController::class, 'update'])
        ->name('departments.update');

    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
        ->name('departments.destroy');

});


/*
|--------------------------------------------------------------------------
| Position Routes
|--------------------------------------------------------------------------
*/

// Routes available to authenticated users
Route::middleware(['auth'])->group(function () {

    Route::get('/positions', [PositionController::class, 'index'])
        ->name('positions.index');

    Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

});
Route::middleware(['auth'])->group(function () {

    Route::get('/positions', [PositionController::class, 'index'])
        ->name('positions.index');

    Route::get('/positions/create', [PositionController::class, 'create'])
        ->middleware('role:admin')
        ->name('positions.create');

    Route::get('/positions/{position}', [PositionController::class, 'show'])
        ->name('positions.show');

    Route::get('/reports/position-evaluation', [
    ReportController::class,
    'positionEvaluation'
])->name('reports.position-evaluation');
Route::get('/reports/position-inventory', [
    ReportController::class,
    'positionInventory'
])->name('reports.position-inventory');
Route::get('/reports/grade-distribution', [
    ReportController::class,
    'gradeDistribution'
])->name('reports.grade-distribution');
 
 Route::get('/reports/evaluation-progress', [
    ReportController::class,
    'evaluationProgress'
])->name('reports.evaluation-progress');

Route::get('/reports/evaluator-performance', [
    ReportController::class,
    'evaluatorPerformance'
])->name('reports.evaluator-performance');
  

Route::patch(
    'salary-scales/{salaryScale}/activate',
    [SalaryScaleController::class, 'activate']
)->name('salary-scales.activate');

Route::patch(
    'salary-scales/{salaryScale}/deactivate',
    [SalaryScaleController::class, 'deactivate']
)->name('salary-scales.deactivate');
    
Route::resource(
    'salary-scales',
    SalaryScaleController::class
);
  Route::resource(
        'vacancies',
        VacancyController::class
    );
    Route::post(
    'vacancies/{vacancy}/submit-approval',
    [VacancyController::class, 'submitForApproval']
)->name('vacancies.submit-approval');

Route::post(
    'vacancies/{vacancy}/approve',
    [VacancyController::class, 'approve']
)->name('vacancies.approve');

Route::post(
    'vacancies/{vacancy}/publish',
    [VacancyController::class, 'publish']
)->name('vacancies.publish');

Route::post(
    'vacancies/{vacancy}/close',
    [VacancyController::class, 'close']
)->name('vacancies.close');

Route::post(
    'vacancies/{vacancy}/cancel',
    [VacancyController::class, 'cancel']
)->name('vacancies.cancel');

Route::post(
    '/positions/{position}/submit',
    [PositionApprovalController::class, 'submit']
)->name('positions.submit');

Route::post(
    '/positions/{position}/start-evaluation',
    [PositionApprovalController::class, 'startEvaluation']
)->name('positions.start-evaluation');

Route::post(
    '/positions/{position}/committee-review',
    [PositionApprovalController::class, 'committeeReview']
)->name('positions.committee-review');

Route::post(
    '/positions/{position}/hr-review',
    [PositionApprovalController::class, 'hrReview']
)->name('positions.hr-review');

Route::post(
    '/positions/{position}/approve',
    [PositionApprovalController::class, 'approve']
)->name('positions.approve');

Route::post(
    '/positions/{position}/reject',
    [PositionApprovalController::class, 'reject']
)->name('positions.reject');

Route::middleware('auth')->group(function () {

    Route::get(
        '/applications',
        [ApplicationController::class, 'index']
    )->name('applications.index');

    Route::get(
        '/applications/{application}',
        [ApplicationController::class, 'show']
    )->name('applications.show');

    Route::patch(
        '/applications/{application}/status',
        [ApplicationController::class, 'updateStatus']
    )->name('applications.status');

    Route::get(
        '/applications/{application}/documents/{document}/download',
        [ApplicationController::class, 'downloadDocument']
    )->name('applications.documents.download');

    Route::get(
    '/recruitment',
    [RecruitmentController::class, 'dashboard']
)->name('recruitment.dashboard');

Route::get(
    '/interviews',
    [InterviewController::class, 'index']
)->name('interviews.index');

Route::get(
    '/applications/{application}/interview/create',
    [InterviewController::class, 'create']
)->name('interviews.create');

Route::post(
    '/applications/{application}/interview',
    [InterviewController::class, 'store']
)->name('interviews.store');

Route::get(
    '/interviews/{interview}',
    [InterviewController::class, 'show']
)->name('interviews.show');

Route::post(
    '/interviews/{interview}/complete',
    [InterviewController::class, 'complete']
)->name('interviews.complete');

Route::post(
    '/interviews/{interview}/cancel',
    [InterviewController::class, 'cancel']
)->name('interviews.cancel');

Route::get(
    '/interviews/{interview}/scoring',
    [InterviewController::class, 'scoring']
)->name('interviews.scoring');

Route::post(
    '/interviews/{interview}/scores',
    [InterviewController::class, 'saveScores']
)->name('interviews.scores.save');

Route::post(
    '/interviews/{interview}/recommendation',
    [InterviewController::class, 'recommendation']
)->name('interviews.recommendation');

Route::get(
    '/vacancies/{vacancy}/applicant-ranking',
    [
        ApplicantRankingController::class,
        'index',
    ]
)->name('applicant-rankings.index');

Route::post(
    '/applications/{application}/select',
    [
        ApplicationController::class,
        'select',
    ]
)->name('applications.select');

Route::post(
    '/applications/{application}/reject',
    [
        ApplicationController::class,
        'reject',
    ]
)->name('applications.reject');
Route::post(
    '/applications/{application}/hire',
    [
        HiringController::class,
        'hire',
    ]
)->name('applications.hire');

Route::post(
    '/applications/{application}/cancel-hiring',
    [
        HiringController::class,
        'cancelHiring',
    ]
)->name('applications.cancel-hiring');
Route::get(
    '/recruitment/dashboard',
    [
        RecruitmentDashboardController::class,
        'index',
    ]
)->name('recruitment.dashboard');
Route::get(
    '/recruitment/reports',
    [RecruitmentReportController::class, 'index']
)->name('recruitment.reports');

Route::get(
    '/recruitment/reports/export',
    [RecruitmentReportController::class, 'export']
)->name('recruitment.reports.export');
 Route::get(
    '/notifications',
    [NotificationController::class, 'index']
)->name('notifications.index');

Route::post(
    '/notifications/{notification}/read',
    [NotificationController::class, 'read']
)->name('notifications.read');

Route::post(
    '/notifications/read-all',
    [NotificationController::class, 'readAll']
)->name('notifications.read-all');

});

    
});



// Routes available only to administrators
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::post('/positions', [PositionController::class, 'store'])
        ->name('positions.store');

    Route::get('/position-evaluation-statistics',[PositionEvaluationController::class, 'statistics'])
        ->name('position-evaluation.statistics');

    Route::get('/positions/{position}', [PositionController::class, 'show'])
        ->name('positions.show');

    Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])
        ->name('positions.edit');

    Route::put('/positions/{position}', [PositionController::class, 'update'])
        ->name('positions.update');
    Route::get(
    '/positions/{position}/evaluate',
    [PositionEvaluationController::class, 'create']
)->name('positions.evaluate');

Route::post(
    '/positions/{position}/evaluate',
    [PositionEvaluationController::class, 'store']
)->name('positions.evaluate.store');

Route::get(
    '/positions/{position}/evaluation/{evaluation}',
    [PositionEvaluationController::class, 'show']
)->name('positions.evaluation.show');

    Route::patch(
    '/positions/{position}/deactivate',
    [PositionController::class, 'deactivate']
)->name('positions.deactivate');

Route::patch(
    '/positions/{position}/activate',
    [PositionController::class, 'activate']
)->name('positions.activate');

    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])
        ->name('positions.destroy');
    
    Route::get(
    '/positions/{position}/evaluations',
    [PositionEvaluationController::class, 'index']
)->name('positions.evaluations.index');

Route::delete(
    '/positions/{position}/evaluation/{evaluation}',
    [PositionEvaluationController::class, 'destroy']
)->name('positions.evaluation.destroy');

Route::get(
    '/position-rankings',
    [PositionEvaluationController::class, 'rankings']
)->name('position-rankings.index');
   Route::resource('grades', GradeController::class);
   




});


Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource(
        'evaluation-factors',
        EvaluationFactorController::class
    )->except(['show']);

});

/*
|--------------------------------------------------------------------------
| Evaluation Criteria Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get(
        '/evaluation-factors/{evaluation_factor}/criteria',
        [EvaluationFactorController::class, 'criteria']
    )->name('evaluation-factors.criteria');

    Route::get(
        '/evaluation-factors/{evaluation_factor}/criteria/create',
        [EvaluationFactorController::class, 'createCriteria']
    )->name('evaluation-factors.criteria.create');

    Route::post(
        '/evaluation-factors/{evaluation_factor}/criteria',
        [EvaluationFactorController::class, 'storeCriteria']
    )->name('evaluation-factors.criteria.store');

    Route::get(
        '/evaluation-factors/{evaluation_factor}/criteria/{criterion}/edit',
        [EvaluationFactorController::class, 'editCriteria']
    )->name('evaluation-factors.criteria.edit');

    Route::put(
        '/evaluation-factors/{evaluation_factor}/criteria/{criterion}',
        [EvaluationFactorController::class, 'updateCriteria']
    )->name('evaluation-factors.criteria.update');

    Route::delete(
        '/evaluation-factors/{evaluation_factor}/criteria/{criterion}',
        [EvaluationFactorController::class, 'destroyCriteria']
    )->name('evaluation-factors.criteria.destroy');

});
 
  /*
|--------------------------------------------------------------------------
| Public Vacancy Portal
|--------------------------------------------------------------------------
*/
Route::get('/public/vacancies', [PublicVacancyController::class, 'index'])
    ->name('public.vacancies.index');

Route::get('/public/vacancies/{vacancy}', [PublicVacancyController::class, 'show'])
    ->name('public.vacancies.show');

Route::get('/public/positions/{position}', [PublicVacancyController::class, 'position'])
    ->name('public.positions.show');


/*
|--------------------------------------------------------------------------
| Public Application
|--------------------------------------------------------------------------
*/

Route::get(
    '/public/vacancies/{vacancy}/apply',
    [PublicApplicationController::class, 'create']
)->name('public.applications.create');
Route::post(
    '/public/vacancies/{vacancy}/apply',
    [PublicApplicationController::class, 'store']
)->name('public.applications.store');

Route::get(
    '/public/applications/{application}/confirmation',
    [PublicApplicationController::class, 'confirmation']
)->name('public.applications.confirmation');
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';