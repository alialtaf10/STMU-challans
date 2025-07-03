<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\UserController;



// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Route::get('/updated-fees/{student}/view', [App\Http\Controllers\StudentFeeController::class, 'view'])->name('challans.view');
Route::get('/students/{student}/challans/{semesterFee}', [ChallanController::class, 'show'])->name('challans.show');


// Authenticated Routes
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

//     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//     Route::get('/import-students', [StudentImportController::class, 'showImportForm'])->name('import.form');
//     Route::post('/import-students', [StudentImportController::class, 'import'])->name('import.process');

//     // Challan routes

//     Route::get('/students/challans', [ChallanController::class, 'index'])->name('challans.index');
//     Route::get('/students/{student}/generate-challan', [ChallanController::class, 'create'])->name('challans.create');

//     // Generate and display the challan
//     Route::get('/students/{student}/challans/{semesterFee}', [ChallanController::class, 'show'])->name('challans.show');

//     // Download PDF version
//     Route::get('/students/{student}/challans/{semesterFee}/download', [ChallanController::class, 'download'])->name('challans.download');

//     Route::post('/students/{student}/fees/store', [ChallanController::class, 'storeFees'])->name('challans.storeFees');

//     // Finance Staff

//     Route::get('/student-fees/updated', [App\Http\Controllers\StudentFeeController::class, 'approvedFees'])->name('student_fees.updated');

//     Route::get('/updated-fees/{student}/view', [App\Http\Controllers\StudentFeeController::class, 'view'])->name('challans.view');

//     Route::put('/student-fees/{id}/approve', [App\Http\Controllers\StudentFeeController::class, 'approve'])->name('student_fees.approve');

//     Route::get('/student-fees/approved', [App\Http\Controllers\StudentFeeController::class, 'approvedList'])->name('student_fees.approved_list');

//     Route::post('/student-fees/send-emails', [App\Http\Controllers\StudentFeeController::class, 'sendEmails'])->name('student_fees.send_emails');



// });

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);


    Route::get('/student-fees/updated', [App\Http\Controllers\StudentFeeController::class, 'approvedFees'])->name('student_fees.updated');
    Route::get('/student-fees-installment/updated', [App\Http\Controllers\StudentFeeController::class, 'approvedInstallmentStudents'])->name('student_fees-installment.updated');
    


    Route::put('/student-fees/{id}/approve', [App\Http\Controllers\StudentFeeController::class, 'approve'])->name('student_fees.approve');

    Route::get('/challans/excel-view', [ChallanController::class, 'excelView'])->name('challans.excel_view');
    Route::post('/challans/bulkApprove', [App\Http\Controllers\StudentFeeController::class, 'approveMultiple'])->name('fees.bulkAction');


    // Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});

Route::middleware(['auth', 'role:student_affairs'])->group(function () {
    // Route::get('/dashboard', [FinanceController::class, 'dashboard']);

    Route::get('/import-students', [StudentImportController::class, 'showImportForm'])->name('import.form');
    Route::post('/import-students', [StudentImportController::class, 'import'])->name('import.process');

    Route::get('/students/challans', [ChallanController::class, 'index'])->name('challans.index');
    Route::get('/students/{student}/generate-challan', [ChallanController::class, 'create'])->name('challans.create');
    Route::post('/students/{student}/fees/store', [ChallanController::class, 'storeFees'])->name('challans.storeFees');
    Route::post('/students/challans/approveAll', [ChallanController::class, 'approveChallans'])->name('students.approve');



    Route::get('/student-fees/approved', [App\Http\Controllers\StudentFeeController::class, 'approvedList'])->name('student_fees.approved_list');
    Route::get('/student-fees-installments/approved', [App\Http\Controllers\StudentFeeController::class, 'approvedStudentInstallments'])->name('student_fees_installments.approved_list');

    Route::post('/student-fees/send-emails', [App\Http\Controllers\StudentFeeController::class, 'sendEmails'])->name('student_fees.send_emails');

    // Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('/installments/send-email', [App\Http\Controllers\StudentFeeController::class, 'sendInstallmentEmail'])->name('installments.send-email');
    Route::get('/student-fees-installments/email-sent', [App\Http\Controllers\StudentFeeController::class, 'sentInstallmentList'])->name('student_fees_installment.email_sent');
    Route::get('/student-fees/email-sent', [App\Http\Controllers\StudentFeeController::class, 'sentEmailList'])->name('student_fees.email_sent');
    
    
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    // Route::get('/students/{student}/challans/{semesterFee}', [ChallanController::class, 'show'])->name('challans.show');

    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update');
    Route::get('/updated-fees/{student}/view', [App\Http\Controllers\StudentFeeController::class, 'view'])->name('challans.view');
    // Route::get('/students/{student}/challans/{semesterFee}', [ChallanController::class, 'show'])->name('challans.show');

    Route::get('challans/installments/{student}', [ChallanController::class, 'viewInstallments'])->name('challans.installments');
    Route::get('/students/{student}/installments/challan/{installmentNumber}', [ChallanController::class, 'generateInstallmentChallan'])
    ->name('challans.installment-challan');




});
