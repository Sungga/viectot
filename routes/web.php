<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckUserCandidate;
use App\Http\Middleware\CheckUserEmployer;
use App\Http\Middleware\CheckUserAdmin;

use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\AuthController;
use App\Http\Controllers\Pages\CvController;
use App\Http\Controllers\Pages\PaymentController;
use App\Http\Controllers\Pages\EmployerController;
use App\Http\Controllers\Pages\ChatController;
use App\Http\Controllers\Pages\AdminController;
use App\Http\Controllers\Pages\FacebookController;
use App\Http\Controllers\Pages\GoogleController;
use App\Http\Controllers\Pages\JobSuggestionController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/company/{id_company}', [HomeController::class, 'company'])->name('company.show');
Route::get('/branch/{id_branch}', [HomeController::class, 'branch'])->name('branch.show');
Route::get('/post/{id_post}', [HomeController::class, 'post'])->name('post.show');
Route::get('/listPost', [HomeController::class, 'listPost'])->name('listPost');
Route::get('/listPost/search', [HomeController::class, 'listPostSearch'])->name('listPost.search');
Route::get('/alert/{id}', [HomeController::class, 'alert'])->name('alert.show');
Route::get('/listAlert', [HomeController::class, 'listAlert'])->name('alert.list');
Route::get('/report/{id_post}', [HomeController::class, 'showFormReport'])->name('report.form');
Route::post('/report/{id_post}', [HomeController::class, 'report'])->name('report');
Route::get('/complaint/{id_post}', [HomeController::class, 'showFormComplaint'])->name('complaint.form');
Route::post('/complaint/{id_post}', [HomeController::class, 'complaint'])->name('complaint');

Route::get('/getTotalJobToday', [HomeController::class, 'getTotalJobToday'])->name('getTotalJobToday');
Route::get('/getTotalJob', [HomeController::class, 'getTotalJob'])->name('getTotalJob');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

// account routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgotPassword', [AuthController::class, 'showForgotPasswordForm'])->name('forgotPassword.form');
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/send-code', [AuthController::class, 'sendCode'])->name('sendCode');

// cv routers
// Route::get('cv/listCv', [CvController::class, 'listCv'])->name('listCv');
// Route::get('cv/makeCv', [CvController::class, 'makeCv'])->name('makeCv.form');

// candidate routes
Route::middleware([CheckUserCandidate::class])->group(function () {
    Route::get('cv/listCv', [CvController::class, 'listCv'])->name('listCv');
    // Route::get('cv', [CvController::class, 'listCv'])->name('listCv');
    Route::get('cv/makeCv', [CvController::class, 'showMakeCvForm'])->name('makeCv.form');
    Route::post('/cv/MakeDemoCv', [CvController::class, 'demoCv'])->name('makeCv.demo');
    Route::post('cv/makeCv', [CvController::class, 'makeCv'])->name('makeCv.store');
    Route::post('cv/uploadCv', [CvController::class, 'uploadCv'])->name('upload.cv');
    Route::get('cv/deleteCv/{id}', [CvController::class, 'deleteCv'])->name('delete.cv');
    Route::get('cv/changeMainCv/{id}', [CvController::class, 'changeMainCv'])->name('changeMain.cv');
    
    Route::get('/apply/{id_post}', [HomeController::class, 'showFormApply'])->name('apply.form');
    Route::post('/apply/{id_post}', [HomeController::class, 'apply'])->name('apply');
    // Route::get('/listApply/{id_post}', [HomeController::class, 'listApply'])->name('apply.list.pending');
    Route::get('/listApply', [HomeController::class, 'listApplyPending'])->name('apply.list.pending');
    Route::get('/listApplyReject', [HomeController::class, 'listApplyReject'])->name('apply.list.reject');
    Route::get('/listApplyPass', [HomeController::class, 'listApplyPass'])->name('apply.list.pass');

    // suggest jobs
    Route::get('/suggestJobs', [JobSuggestionController::class, 'suggestJobsForm'])->name('suggestJobs.form');
    Route::post('/api/suggest-job', [JobSuggestionController::class, 'suggestFromInput']);
    Route::get('/api/job-detail/{id}/{score}', [JobSuggestionController::class, 'getJobDetail']);

    // account
    Route::get('/addBasicInfo', [AuthController::class, 'showAddBasicInfoForm'])->name('addBasicInfo.form');
    Route::post('/addBasicInfo', [AuthController::class, 'addBasicInfo'])->name('addBasicInfo');
});

// Cổng thanh toán
Route::post('vnpay_payment', [PaymentController::class, 'vnpay_payment'])->name('payment');
Route::get('/vnpay_return', [PaymentController::class, 'vnpay_return'])->name('vnpay.return');
// Route::get('/vnpay/ipn', [PaymentController::class, 'vnpay_ipn'])->name('vnpay.ipn');
Route::get('/vnpay/test', [PaymentController::class, 'completePurchase'])->name('vnpay.test');

// Route::get('cv/makeCv', function(){ return view('cv.makeCv'); });

// employer routes
Route::middleware([CheckUserEmployer::class])->group(function () {
    Route::get('employer/check', function() { return session('user'); });
    Route::get('employer/listCv', [EmployerController::class, 'listCv'])->name('employer.listCv');
    Route::get('employer/candidateProfile', [EmployerController::class, 'candidateProfile'])->name('employer.candidateProfile');
    Route::get('employer/listBranch', [EmployerController::class, 'listBranch'])->name('employer.listBranch');
    Route::get('employer/formAddBranch', [EmployerController::class, 'showFormAddBranch'])->name('employer.formAddBranch');
    Route::post('employer/addCompany', [EmployerController::class, 'addCompany'])->name('employer.addCompany');
    Route::post('employer/updateCompany', [EmployerController::class, 'updateCompany'])->name('employer.updateCompany');
    Route::get('employer/deleteCompany/{id_company}', [EmployerController::class, 'deleteCompany'])->name('employer.deleteCompany');
    Route::post('employer/addBranch', [EmployerController::class, 'addBranch'])->name('employer.addBranch');
    Route::get('employer/deleteBranch/{id_branch}', [EmployerController::class, 'deleteBranch'])->name('employer.deleteBranch');
    Route::post('employer/updateBranch/', [EmployerController::class, 'updateBranch'])->name('employer.updateBranch');
    Route::get('employer/listPost', [EmployerController::class, 'listPost'])->name('employer.listPost');
    Route::get('employer/listExpiredPosts', [EmployerController::class, 'listExpiredPosts'])->name('employer.listExpiredPosts');
    Route::post('employer/addPost', [EmployerController::class, 'addPost'])->name('employer.addPost');
    Route::post('employer/updatePost', [EmployerController::class, 'updatePost'])->name('employer.updatePost');
    Route::get('employer/deletePost/{id_post}', [EmployerController::class, 'deletePost'])->name('employer.deletePost');
    Route::get('employer/listApply/{id_post}', [EmployerController::class, 'listApply'])->name('listApply');
    Route::post('employer/reject/{id_post}/{id_candidate}', [EmployerController::class, 'reject'])->name('reject');
    // Route::post('employer/pass/{id_post}/{id_candidate}', [EmployerController::class, 'pass'])->name('pass');
    Route::post('employer/pass/', [EmployerController::class, 'pass'])->name('pass');

    Route::post('employer/searchEmployer', [EmployerController::class, 'searchEmployer'])->name('searchEmployer');
    Route::post('employer/addBranchManager', [EmployerController::class, 'addBranchManager'])->name('employer.addBranchManager');

    // account
    Route::get('/addCompanyInfo', [AuthController::class, 'showAddCompanyInfoForm'])->name('addCompanyInfo.form');
});


// chat
// Route::get('/chat/{id_user}', [ChatController::class, 'index'])->name('chat');
Route::get('/chat/messages/{id_branch}/{id_candidate}/{id_apply}/{idMax}', [ChatController::class, 'getMessages']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
Route::get('/chat/listMessage', [ChatController::class, 'listMessage'])->name('listMessage');
Route::get('/chat/{type}/{id_branch}/{id_candidate}/{id_apply}', [ChatController::class, 'index'])->name('chat');

Route::middleware([CheckUserAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin');
    Route::get('/admin/listAlert', [AdminController::class, 'listAlert'])->name('admin.listAlert');
    Route::get('/admin/listAlertOk', [AdminController::class, 'listAlertOk'])->name('admin.listAlertOk');
    Route::get('/admin/alert/{id}', [AdminController::class, 'alert'])->name('admin.alert');
    Route::get('/admin/lockPost/{id}', [AdminController::class, 'lockPost'])->name('admin.lockPost');
    Route::get('/admin/rejectAlert/{id}', [AdminController::class, 'rejectAlert'])->name('admin.alert.reject');
    Route::get('/admin/unlockAlert/{id}', [AdminController::class, 'unlockPost'])->name('admin.unlockPost');
    Route::get('/admin/listCandidate', [AdminController::class, 'listCandidate'])->name('admin.listCandidate');
    Route::get('/admin/listEmployer', [AdminController::class, 'listEmployer'])->name('admin.listEmployer');
    Route::get('/admin/listPost', [AdminController::class, 'listPost'])->name('admin.listPost');
    Route::get('/admin/user', [AdminController::class, 'user'])->name('admin.user');
    Route::get('/admin/lockUser/{id_user}', [AdminController::class, 'lockUser'])->name('admin.lockUser');
    Route::get('/admin/unlockUser/{id_user}', [AdminController::class, 'unlockUser'])->name('admin.unlockUser');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/candidateProfile', [EmployerController::class, 'candidateProfile'])->name('admin.candidateProfile');
});


Route::get('/alertLockAccount', [AdminController::class, 'alertLockAccount'])->name('admin.alertLockAccount');


Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('toFacebook');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('callbackFacebook');


Route::view('/privacy-policy', 'privacy');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'googleCallback']);
Route::post('/auth/addRole/', [GoogleController::class, 'addRole'])->name('addRole');