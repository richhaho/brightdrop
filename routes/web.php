<?php

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
Route::get('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset');
Route::match(['get', 'post'], 'register', function(){
    return redirect('/home');
});
 
Auth::routes();

Route::get('/', function () {
     return redirect('/home');
 });

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
 

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index');

    Route::group(['middleware' => 'role:Admin|Worker|Contact|Payroll|Account'], function (){

    });

    Route::group(['middleware' => 'role:Worker', 'namespace' => 'Worker'], function (){

        Route::get('/worker/logTime', 'TimeSheetController@logTime')->name('worker.logtime');
        Route::post('/worker/storeTime', 'TimeSheetController@storeTime')->name('worker.storetime');

        Route::get('/worker/needsApproval', 'TimeSheetController@needsApproval')->name('worker.needsApproval');
        Route::post('/worker/submitNeedsApproval', 'TimeSheetController@submitNeedsApproval')->name('worker.submitNeedsApproval');
        
        Route::get('/worker/paidTimeOff', 'PTOController@paidTimeOff')->name('worker.paidTimeOff');
        Route::get('/worker/pendingPTO', 'PTOController@pendingPTO')->name('worker.pendingPTO');      
        Route::get('/worker/summaryPTO', 'PTOController@summaryPTO')->name('worker.summaryPTO');   
        Route::get('/worker/pastPTO', 'PTOController@pastPTO')->name('worker.pastPTO');
        Route::post('/worker/submitPTO', 'PTOController@submitPTO')->name('worker.submitPTO');
        Route::get('/worker/exist_pto', 'PTOController@exist_pto')->name('worker.exist_pto');

        Route::get('/worker/reimbursementRequest', 'ReimbursementController@reimbursementRequest')->name('worker.reimbursementRequest');
        Route::get('/worker/pendingReimbursement', 'ReimbursementController@pendingReimbursement')->name('worker.pendingReimbursement');
        Route::get('/worker/summaryReimbursement', 'ReimbursementController@summaryReimbursement')->name('worker.summaryReimbursement');
        
        Route::post('/worker/submit_reimbursementRequest', 'ReimbursementController@submit_reimbursementRequest')->name('worker.submit_reimbursementRequest');


        Route::get('/worker/paymentSummaries', 'WorkerController@paymentSummaries');
        Route::get('/worker/downloadSummaries/{id}', 'WorkerController@downloadSummaries')->name('worker.downloadpdf');
        Route::get('/worker/download', 'WorkerController@download')->name('worker.download');
         
        Route::get('/worker/holidaySchedule', 'WorkerController@holidaySchedule');
        


        Route::get('/worker/profileWorker', 'WorkerController@profile')->name('worker.profile');
        Route::post('/worker/updateWorker', 'WorkerController@update')->name('worker.update');
        Route::get('/worker/assignedWorkerCurrency', 'WorkerController@assignedWorkerCurrency')->name('worker.assignedWorkerCurrency');

    });
    Route::group(['middleware' => 'role:Account', 'namespace' => 'Account'], function (){
         
        Route::get('/accountManager/createWorker', 'WorkerController@create')->name('account.worker.create');
        Route::post('/accountManager/storeWorker', 'WorkerController@store')->name('account.worker.store');
        Route::post('/accountManager/updateWorker', 'WorkerController@update')->name('account.worker.update');

        Route::get('/accountManager/searchWorker', 'WorkerController@search')->name('account.worker.search');
        Route::post('/accountManager/removeWorker', 'WorkerController@removeWorker')->name('account.worker.removeWorker');
        Route::post('/accountManager/setfilterWorker', 'WorkerController@setfilter')->name('account.worker.setfilter');
        Route::get('/accountManager/resetfilterWorker', 'WorkerController@resetfilter')->name('account.worker.resetfilter');

        Route::get('/accountManager/searchWorkerHired', 'WorkerController@searchHired')->name('account.worker.searchHired');
        Route::post('/accountManager/setfilterWorkerHired', 'WorkerController@setfilterHired')->name('account.worker.setfilterHired');
        Route::get('/accountManager/resetfilterWorkerHired', 'WorkerController@resetfilterHired')->name('account.worker.resetfilterHired');

        Route::get('/accountManager/profileWorker/{id}', 'WorkerController@profile')->name('account.worker.profile');
        Route::post('/accountManager/sendVideoProfile/', 'WorkerController@sendVideoProfile')->name('account.worker.sendVideoProfile');

        Route::get('/accountManager/workerResetPassword', 'WorkerController@sendResetPasswordLink')->name('account.worker.resetpassword');

        Route::get('/accountManager/special_worker', 'WorkerController@special_worker')->name('account.worker.special_worker');;
        Route::get('/accountManager/worker/downloadSummaries/{id}', 'WorkerController@downloadSummaries')->name('account.worker.downloadpdf');
        Route::get('/accountManager/worker/download', 'WorkerController@download')->name('account.worker.download');
        Route::get('/accountManager/worker/downloadCSV', 'WorkerController@download_CSV_Data')->name('account.worker.downloadcsv');


        Route::get('/accountManager/createClient', 'ClientController@create');
        Route::post('/accountManager/storeClient', 'ClientController@store')->name('account.client.store');;
        Route::post('/accountManager/updateClient', 'ClientController@update')->name('account.client.update');;
        Route::get('/accountManager/searchClient', 'ClientController@search')->name('account.client.search');
        Route::get('/accountManager/profileClient/{id}', 'ClientController@profile')->name('account.client.profile');
        Route::post('/accountManager/removeClient', 'ClientController@removeClient')->name('account.client.removeClient');
        Route::post('/accountManager/setfilterClient', 'ClientController@setfilter')->name('account.client.setfilter');
        Route::get('/accountManager/resetfilterClient', 'ClientController@resetfilter')->name('account.client.resetfilter');
        Route::get('/accountManager/client/activeWorkers', 'ClientController@activeWorkers')->name('account.client.activeWorkers');
        Route::get('/accountManager/client/allWorkers', 'ClientController@allWorkers')->name('account.client.allWorkers');
     
        Route::get('/accountManager/createContact', 'ContactController@create')->name('account.contact.create'); 
        Route::post('/accountManager/storeContact', 'ContactController@store')->name('account.contact.store'); 
        
        Route::get('/accountManager/searchContact', 'ContactController@search')->name('account.contact.search');
        Route::post('/accountManager/setfilterContact', 'ContactController@setfilter')->name('account.contact.setfilter');
        Route::get('/accountManager/resetfilterContact', 'ContactController@resetfilter')->name('account.contact.resetfilter');
        Route::post('/accountManager/removeContact', 'ContactController@removeContact')->name('account.contact.removeContact');
        Route::get('/accountManager/profileContact/{id}', 'ContactController@profile')->name('account.contact.profile');
        Route::post('/accountManager/updateContact', 'ContactController@update')->name('account.contact.update');
        Route::get('/accountManager/special_contact', 'ContactController@special_contact')->name('account.contact.special_contact');;
        Route::get('/accountManager/ContactResetPassword', 'ContactController@sendResetPasswordLink')->name('account.contact.resetpassword');
        

        Route::get('/accountManager/needsApproval', 'TimeSheetController@needsApproval')->name('account.timesheet.needsApproval');
        Route::post('/accountManager/submitApproveTimesheet', 'TimeSheetController@submitApprove')->name('account.timesheet.submitApprove');
        Route::get('/accountManager/declineTimecard', 'TimeSheetController@declineTimecard')->name('account.timesheet.declineTimecard');

        Route::get('/accountManager/currentTimesheets', 'TimeSheetController@current')->name('account.timesheet.currentTimesheets');
        Route::post('/accountManager/submitCurrentTimesheets', 'TimeSheetController@submitCurrent')->name('account.timesheet.submitCurrent');
        Route::get('/accountManager/pendingWorkerApproval', 'TimeSheetController@pendingWorkerApproval')->name('account.timesheet.pendingWorkerApproval');;
        Route::post('/accountManager/submitPendingWorkerApproval', 'TimeSheetController@submitPendingWorkerApproval')->name('account.timesheet.submitPendingWorkerApproval');;
        Route::get('/accountManager/pastTimesheets', 'TimeSheetController@pastTimesheets')->name('account.timesheet.pastTimesheets');;
        Route::match(['get', 'post'], '/accountManager/timesheet/setfilter', 'TimeSheetController@setfilter')->name('account.timesheet.setfilter');
        Route::get('/accountManager/timesheet/resetfilter', 'TimeSheetController@resetfilter')->name('account.timesheet.resetfilter');
        
        Route::get('/accountManager/PTONeedsApproval', 'PTOController@PTONeedsApproval')->name('account.PTONeedsApproval');;
        Route::post('/accountManager/approvePTO', 'PTOController@approvePTO')->name('account.approvePTO');;
        Route::post('/accountManager/declinePTO', 'PTOController@declinePTO')->name('account.declinePTO');;

        Route::get('/accountManager/PTOOverride', 'PTOController@PTOOverride')->name('account.PTOOverride');
        Route::get('/accountManager/PTOsummary', 'PTOController@PTOsummary')->name('account.PTOsummary');
        Route::get('/accountManager/PTOpast', 'PTOController@PTOpast')->name('account.PTOpast');

        Route::post('/accountManager/submitPTO', 'PTOController@submitPTO')->name('account.submitPTO');
        Route::get('/accountManager/exist_pto', 'PTOController@exist_pto')->name('account.exist_pto');
        Route::match(['get', 'post'], '/accountManager/pto/setfilter', 'PTOController@setfilter')->name('account.pto.setfilter');
        Route::get('/accountManager/pto/resetfilter', 'PTOController@resetfilter')->name('account.pto.resetfilter');


        Route::get('/accountManager/ReimbursementNeedsApproval', 'ReimbursementController@ReimbursementNeedsApproval')->name('account.ReimbursementNeedsApproval');
        Route::get('/accountManager/ReimbursementPast', 'ReimbursementController@ReimbursementPast')->name('account.ReimbursementPast');
        Route::get('/accountManager/ReimbursementArchive', 'ReimbursementController@ReimbursementArchive')->name('account.ReimbursementArchive');

        Route::post('/accountManager/submit_reimbursementApproval', 'ReimbursementController@submit_reimbursementApproval')->name('account.submit_reimbursementApproval');
        Route::get('/accountManager/decline_reimbursementApproval', 'ReimbursementController@decline_reimbursementApproval')->name('account.decline_reimbursementApproval');

        Route::get('/accountManager/downloadReimbursement', 'ReimbursementController@download')->name('account.downloadReimbursement');

        

        Route::get('/accountManager/createAdjustment', 'AdjustmentController@create')->name('account.adjustment.create');
        Route::get('/accountManager/activeAdjustment', 'AdjustmentController@active')->name('account.adjustment.active');
        Route::get('/accountManager/pastAdjustment', 'AdjustmentController@past')->name('account.adjustment.past');
        Route::get('/accountManager/pendingAdjustment', 'AdjustmentController@pending')->name('account.adjustment.pending');

        Route::post('/accountManager/submitAdjustmentOneTime', 'AdjustmentController@submit_onetime')->name('account.adjustment.submit_onetime');
        Route::post('/accountManager/submitAdjustmentRecurring', 'AdjustmentController@submit_recurring')->name('account.adjustment.submit_recurring');
        Route::post('/accountManager/removeAdjustmentOnetime', 'AdjustmentController@remove_onetime')->name('account.adjustment.remove_onetime');
        Route::post('/accountManager/removeAdjustmentRecurring', 'AdjustmentController@remove_recurring')->name('account.adjustment.remove_recurring');
        Route::post('/accountManager/updateAdjustmentOnetime', 'AdjustmentController@update_onetime')->name('account.adjustment.update_onetime');
        Route::post('/accountManager/updateAdjustmentRecurring', 'AdjustmentController@update_recurring')->name('account.adjustment.update_recurring');

        Route::get('/accountManager/openCashAdvance', 'CashAdvanceController@openCashAdvance')->name('account.openCashAdvance');
        Route::get('/accountManager/pastCashAdvance', 'CashAdvanceController@pastCashAdvance')->name('account.pastCashAdvance');

        Route::get('/accountManager/viewReport/{id}', 'CashAdvanceController@viewReport')->name('account.payroll.viewReport');

        Route::get('/accountManager/createPosition', 'PositionController@create')->name('account.position.create');
        Route::post('/accountManager/position/store', 'PositionController@store')->name('account.position.store');
        Route::get('/accountManager/editPosition/{id}', 'PositionController@edit')->name('account.position.edit');        
        Route::post('/accountManager/position/{id}/update', 'PositionController@update')->name('account.position.update');
        Route::get('/accountManager/position/{id}/copy', 'PositionController@copy')->name('account.position.copy');
        Route::get('/accountManager/openPositions', 'PositionController@open')->name('account.position.open');
        Route::get('/accountManager/pastPositions', 'PositionController@past')->name('account.position.past');
        Route::post('/accountManager/canditate/save', 'CandidateController@saveCandidate')->name('account.candidate.save');
        Route::post('/accountManager/canditate/remove', 'CandidateController@removeCandidate')->name('account.candidate.remove');
        Route::post('/accountManager/canditate/final-decision', 'CandidateController@finalDecision')->name('account.candidate.finaldecision');
        Route::post('/accountManager/canditate/move-to-group', 'CandidateController@moveToGroup')->name('account.candidate.move');
        Route::post('/accountManager/canditate/update-note', 'CandidateController@updateNote')->name('account.candidate.note.update');
        Route::post('/accountManager/canditate/update-order', 'CandidateController@updateOrder')->name('accountManager.candidate.order.update');

        Route::get('/accountManager/hired/needs-finalized', 'HiredController@needsFinalized')->name('account.hired.needs_finalized');
        Route::get('/accountManager/hired/pending-review', 'HiredController@pendingReview')->name('account.hired.pending_review');
        Route::get('/accountManager/hired/needs-setup', 'HiredController@needsSetup')->name('account.hired.needs_setup');
        Route::get('/accountManager/hired/completed', 'HiredController@completed')->name('account.hired.completed');
        Route::post('/accountManager/hired/save', 'HiredController@saveHired')->name('account.hired.save');
        Route::post('/accountManager/hired/update-one', 'HiredController@updateOne')->name('account.hired.update_one');
        Route::post('/accountManager/hired/remove', 'HiredController@remove')->name('account.hired.remove');
        Route::post('/accountManager/hired/update-status', 'HiredController@updateStatus')->name('account.hired.update_status');
        Route::post('/accountManager/hired/upload-ica', 'HiredController@uploadICA')->name('account.hired.upload_ica');
        Route::get('/accountManager/hired/download-ica', 'HiredController@downloadICA')->name('account.hired.download_ica');
        
        Route::get('/accountManager/declined/pending-notice', 'DeclinedController@pendingNotice')->name('account.declined.pending_notice');
        Route::get('/accountManager/declined/completed', 'DeclinedController@completed')->name('account.declined.completed');
        Route::post('/accountManager/declined/save', 'DeclinedController@saveDeclined')->name('account.declined.save');
        Route::post('/accountManager/declined/update-one', 'DeclinedController@updateOne')->name('account.declined.update_one');
        Route::post('/accountManager/declined/remove', 'DeclinedController@remove')->name('account.declined.remove');
        Route::post('/accountManager/declined/update-status', 'DeclinedController@updateStatus')->name('account.declined.update_status');
     });
    Route::group(['middleware' => 'role:Payroll', 'namespace' => 'Payroll'], function (){  
        Route::get('/payrollManager/immediatePayWU', 'PayrollController@immediateWU')->name('payroll.payroll.immediateWU');
        Route::get('/payrollManager/immediatePayVeem', 'PayrollController@immediateVeem')->name('payroll.payroll.immediateVeem');
        Route::get('/payrollManager/biWeeklyWU', 'PayrollController@biWeeklyWU')->name('payroll.payroll.biWeeklyWU');
        Route::get('/payrollManager/biWeeklyVeem', 'PayrollController@biWeeklyVeem')->name('payroll.payroll.biWeeklyVeem');
        Route::get('/payrollManager/viewClosed', 'PayrollController@viewClosed')->name('payroll.payroll.viewClosed');
        Route::match(['get', 'post'], '/payrollManager/payroll/setfilter', 'PayrollController@setfilter')->name('payroll.payroll.setfilter');
        Route::get('/payrollManager/payroll/resetfilter', 'PayrollController@resetfilter')->name('payroll.payroll.resetfilter');

        Route::get('/payrollManager/viewReport/{id}', 'PayrollController@viewReport')->name('payroll.payroll.viewReport');

        Route::post('/payrollManager/removePayment', 'PayrollController@remove')->name('payroll.payment.remove');
        Route::get('/payrollManager/editPayment/{id}', 'PayrollController@edit')->name('payroll.payment.edit');
        Route::post('/payrollManager/updatePayment', 'PayrollController@update')->name('payroll.payment.update');

        Route::post('/payrollManager/pay', 'PayrollController@pay')->name('payroll.payroll.pay');
        Route::post('/payrollManager/change_payment_method', 'PayrollController@changePaymentMethod')->name('payroll.payroll.change');
        Route::get('/payrollManager/sendSummary', 'PayrollController@sendSummary')->name('payroll.payroll.sendSummary');


        Route::get('/payrollManager/invoicesManual', 'InvoiceController@manual')->name('payroll.invoices.manual');
        Route::get('/payrollManager/invoicesAutomatic', 'InvoiceController@automatic')->name('payroll.invoices.automatic');
        Route::get('/payrollManager/invoicesNeedsSent', 'InvoiceController@needsSent')->name('payroll.invoices.needsSent');
        Route::get('/payrollManager/invoicesPendingClientApproval', 'InvoiceController@pendingClientApproval')->name('payroll.invoices.pendingClientApproval');
        Route::get('/payrollManager/invoicesNeedsProcessed', 'InvoiceController@needsProcessed')->name('payroll.invoices.needsProcessed');
        Route::get('/payrollManager/invoicesBankVerifications', 'InvoiceController@bankVerifications')->name('payroll.invoices.bankVerifications');
        Route::get('/payrollManager/invoicesViewClosed', 'InvoiceController@viewClosed')->name('payroll.invoices.viewClosed');

        Route::post('/payrollManager/removeInvoice', 'InvoiceController@remove')->name('payroll.invoices.remove');
        Route::get('/payrollManager/editInvoice/{id}', 'InvoiceController@edit')->name('payroll.invoices.edit');
        Route::post('/payrollManager/updateInvoice', 'InvoiceController@update')->name('payroll.invoices.update');

         Route::post('/payrollManager/invoicesFinalized', 'InvoiceController@finalized')->name('payroll.invoices.finalized');   
         Route::post('/payrollManager/invoicesSendClient', 'InvoiceController@send_client')->name('payroll.invoices.send_client');
         Route::post('/payrollManager/invoicesMarkasSent', 'InvoiceController@mark_sent')->name('payroll.invoices.mark_sent');
         Route::post('/payrollManager/invoicesRecalculate', 'InvoiceController@recalculate')->name('payroll.invoices.recalculate');
         Route::get('/payrollManager/downloadInvoice/{id}', 'InvoiceController@download')->name('payroll.invoices.download');
         Route::post('/payrollManager/deleteInvoice', 'InvoiceController@delete')->name('payroll.invoices.delete');

         Route::post('/payrollManager/invoicesApproveByClient', 'InvoiceController@approvebyclient')->name('payroll.invoices.approvebyclient');
         Route::post('/payrollManager/invoicesDeclineByClient', 'InvoiceController@declinebyclient')->name('payroll.invoices.declinebyclient');

         Route::post('/payrollManager/invoicesVerifyBank', 'InvoiceController@bank_verify')->name('payroll.invoices.bank_verify');
         Route::post('/payrollManager/invoicesProcess', 'InvoiceController@process')->name('payroll.invoices.process');

        Route::get('/payrollManager/createAdjustment', 'AdjustmentController@create')->name('payroll.adjustment.create');
        Route::get('/payrollManager/activeAdjustment', 'AdjustmentController@active')->name('payroll.adjustment.active');
        Route::get('/payrollManager/pastAdjustment', 'AdjustmentController@past')->name('payroll.adjustment.past');
        Route::get('/payrollManager/pendingAdjustment', 'AdjustmentController@pending')->name('payroll.adjustment.pending');
        Route::get('/payrollManager/needApprovalAdjustment', 'AdjustmentController@needsApproval')->name('payroll.adjustment.needsApproval');

        Route::post('/payrollManager/submitAdjustmentOneTime', 'AdjustmentController@submit_onetime')->name('payroll.adjustment.submit_onetime');
        Route::post('/payrollManager/submitAdjustmentRecurring', 'AdjustmentController@submit_recurring')->name('payroll.adjustment.submit_recurring');
        Route::post('/payrollManager/removeAdjustmentOnetime', 'AdjustmentController@remove_onetime')->name('payroll.adjustment.remove_onetime');
        Route::post('/payrollManager/removeAdjustmentRecurring', 'AdjustmentController@remove_recurring')->name('payroll.adjustment.remove_recurring');
        Route::post('/payrollManager/updateAdjustmentOnetime', 'AdjustmentController@update_onetime')->name('payroll.adjustment.update_onetime');
        Route::post('/payrollManager/updateAdjustmentRecurring', 'AdjustmentController@update_recurring')->name('payroll.adjustment.update_recurring');
        Route::get('/payrollManager/special_worker', 'AdjustmentController@special_worker')->name('payroll.worker.special_worker');;
        

        Route::get('/payrollManager/needsApproval', 'TimeSheetController@needsApproval')->name('payroll.timesheet.needsApproval');
        Route::post('/payrollManager/submitApproveTimesheet', 'TimeSheetController@submitApprove')->name('payroll.timesheet.submitApprove');
        Route::get('/payrollManager/declineTimecard', 'TimeSheetController@declineTimecard')->name('payroll.timesheet.declineTimecard');

        Route::get('/payrollManager/currentTimesheets', 'TimeSheetController@current')->name('payroll.timesheet.currentTimesheets');
        Route::post('/payrollManager/submitCurrentTimesheets', 'TimeSheetController@submitCurrent')->name('payroll.timesheet.submitCurrent');
        Route::get('/payrollManager/pendingWorkerApproval', 'TimeSheetController@pendingWorkerApproval')->name('payroll.timesheet.pendingWorkerApproval');;
        Route::post('/payrollManager/submitPendingWorkerApproval', 'TimeSheetController@submitPendingWorkerApproval')->name('payroll.timesheet.submitPendingWorkerApproval');;
        Route::get('/payrollManager/pastTimesheets', 'TimeSheetController@pastTimesheets')->name('payroll.timesheet.pastTimesheets');
        Route::match(['get', 'post'], '/payrollManager/timesheet/setfilter', 'TimeSheetController@setfilter')->name('payroll.timesheet.setfilter');
        Route::get('/payrollManager/timesheet/resetfilter', 'TimeSheetController@resetfilter')->name('payroll.timesheet.resetfilter');

        Route::get('/payrollManager/PTONeedsApproval', 'PTOController@PTONeedsApproval')->name('payroll.PTONeedsApproval');;
        Route::post('/payrollManager/approvePTO', 'PTOController@approvePTO')->name('payroll.approvePTO');;
        Route::post('/payrollManager/declinePTO', 'PTOController@declinePTO')->name('payroll.declinePTO');;

        Route::get('/payrollManager/PTOOverride', 'PTOController@PTOOverride')->name('payroll.PTOOverride');
        Route::get('/payrollManager/PTOsummary', 'PTOController@PTOsummary')->name('payroll.PTOsummary');
        Route::post('/payrollManager/updatePTOsummary', 'PTOController@updatePTOsummary')->name('payroll.updatePTOsummary');
        Route::get('/payrollManager/PTOpast', 'PTOController@PTOpast')->name('payroll.PTOpast');
        Route::match(['get', 'post'], '/payrollManager/pto/setfilter', 'PTOController@setfilter')->name('payroll.pto.setfilter');
        Route::get('/payrollManager/pto/resetfilter', 'PTOController@resetfilter')->name('payroll.pto.resetfilter');

        Route::post('/payrollManager/submitPTO', 'PTOController@submitPTO')->name('payroll.submitPTO');
        Route::get('/payrollManager/exist_pto', 'PTOController@exist_pto')->name('payroll.exist_pto');

        Route::get('/payrollManager/createCashAdvance', 'CashAdvanceController@createCashAdvance')->name('payroll.createCashAdvance');
        Route::get('/payrollManager/openCashAdvance', 'CashAdvanceController@openCashAdvance')->name('payroll.openCashAdvance');
        Route::get('/payrollManager/pastCashAdvance', 'CashAdvanceController@pastCashAdvance')->name('payroll.pastCashAdvance');

        Route::post('/payrollManager/submitWorkerCashAdvance', 'CashAdvanceController@submitCashAdvance')->name('payroll.submitCashAdvance');
        Route::post('/payrollManager/updateWorkerCashAdvance', 'CashAdvanceController@updateCashAdvance')->name('payroll.updateCashAdvance');
        Route::get('/payrollManager/removeWorkerCashAdvance/{id}', 'CashAdvanceController@removeCashAdvance')->name('payroll.removeCashAdvance');


        Route::get('/payrollManager/adjustmentOneTime', 'AdjustmentController@adjustmentOneTime')->name('payroll.needsApproval.adjustmentOneTime');
        Route::post('/payrollManager/approveAdjustmentOneTime', 'AdjustmentController@approve_adjustmentOneTime')->name('payroll.needsApproval.approve_adjustmentOneTime');
        Route::post('/payrollManager/declineAdjustmentOneTime', 'AdjustmentController@decline_adjustmentOneTime')->name('payroll.needsApproval.decline_adjustmentOneTime');

        Route::get('/payrollManager/adjustmentRecurring', 'AdjustmentController@adjustmentRecurring')->name('payroll.needsApproval.adjustmentRecurring');
        Route::post('/payrollManager/approveadjustmentRecurring', 'AdjustmentController@approve_adjustmentRecurring')->name('payroll.needsApproval.approve_adjustmentRecurring');
        Route::post('/payrollManager/declineadjustmentRecurring', 'AdjustmentController@decline_adjustmentRecurring')->name('payroll.needsApproval.decline_adjustmentRecurring');

        Route::get('/payrollManager/updateCommets', 'PayrollManagerController@updateCommets')->name('payroll.updateCommets');

        Route::get('/payrollManager/client/activeWorkers', 'PayrollManagerController@activeWorkers')->name('payroll.client.activeWorkers');
        Route::get('/payrollManager/client/allWorkers', 'PayrollManagerController@allWorkers')->name('payroll.client.allWorkers');

 

     });
    Route::group(['middleware' => 'role:Contact', 'namespace' => 'Contact'], function (){     
        //Route::get('/contact/needsApproval', 'ContactController@needsApproval');
        Route::get('/contact/pastInvoices', 'ContactController@pastInvoices');
        Route::get('/contact/holidaySchedule', 'ContactController@holidaySchedule');
        Route::get('/contact/PTOInformation', 'ContactController@PTOInformation');
        Route::get('/contact/downloadInvoice/{id}', 'ContactController@download')->name('contact.invoices.download');

        Route::get('/contact/createPosition', 'PositionController@create')->name('contact.position.create');
        Route::post('/contact/position/store', 'PositionController@store')->name('contact.position.store');
        Route::get('/contact/editPosition/{id}', 'PositionController@edit')->name('contact.position.edit');        
        Route::post('/contact/position/{id}/update', 'PositionController@update')->name('contact.position.update');
        Route::get('/contact/position/{id}/copy', 'PositionController@copy')->name('contact.position.copy');
        Route::get('/contact/openPositions', 'PositionController@open')->name('contact.position.open');
        Route::post('/contact/canditate/save', 'CandidateController@saveCandidate')->name('contact.candidate.save');
        Route::post('/contact/canditate/remove', 'CandidateController@removeCandidate')->name('contact.candidate.remove');
        Route::post('/contact/canditate/final-decision', 'CandidateController@finalDecision')->name('contact.candidate.finaldecision');
        Route::post('/contact/canditate/move-to-group', 'CandidateController@moveToGroup')->name('contact.candidate.move');
        Route::post('/contact/canditate/update-note', 'CandidateController@updateNote')->name('contact.candidate.note.update');
        Route::post('/contact/canditate/update-order', 'CandidateController@updateOrder')->name('contact.candidate.order.update');

     });
    Route::group(['middleware' => 'role:Admin', 'namespace' => 'Admin'], function (){
        Route::get('/admin/cron/run', 'AdminController@cronRun')->name('admin.cron.run');;

        Route::get('/admin/createAccountManager', 'AccountManagerController@create')->name('admin.accountmanager.create');;
        Route::post('/admin/storeAccountManager', 'AccountManagerController@store')->name('admin.accountmanager.store');;
        Route::post('/admin/updateAccountManager', 'AccountManagerController@update')->name('admin.accountmanager.update');;
        Route::get('/admin/searchAccountManager', 'AccountManagerController@search')->name('admin.accountmanager.search');
        Route::get('/admin/profileAccountManager/{id}', 'AccountManagerController@profile')->name('admin.accountmanager.profile');
        Route::post('/admin/setfilterAccountManager', 'AccountManagerController@setfilter')->name('admin.accountmanager.setfilter');
        Route::get('/admin/resetfilterAccountManager', 'AccountManagerController@resetfilter')->name('admin.accountmanager.resetfilter');
        Route::post('/admin/removeAccountManager', 'AccountManagerController@remove')->name('admin.accountmanager.remove');
        Route::get('/admin/AccountManagerResetPassword', 'AccountManagerController@sendResetPasswordLink')->name('admin.accountmanager.resetpassword');


        Route::get('/admin/createAdmin', 'AdminController@create')->name('admin.admin.create');;
        Route::get('/admin/searchAdmin', 'AdminController@search')->name('admin.admin.search');
        Route::get('/admin/profileAdmin/{id}', 'AdminController@profile')->name('admin.admin.profile');;
        Route::post('/admin/storeAdmin', 'AdminController@store')->name('admin.admin.store');;
        Route::post('/admin/updateAdmin', 'AdminController@update')->name('admin.admin.update');;

        Route::post('/admin/setfilterAdmin', 'AdminController@setfilter')->name('admin.admin.setfilter');
        Route::get('/admin/resetfilterAdmin', 'AdminController@resetfilter')->name('admin.admin.resetfilter');
        Route::post('/admin/removeAdmin', 'AdminController@remove')->name('admin.admin.remove');
        Route::get('/admin/AdminResetPassword', 'AdminController@sendResetPasswordLink')->name('admin.admin.resetpassword');
        Route::get('/admin/createPayrollManager', 'PayrollManagerController@create')->name('admin.payroll.create'); 
        Route::get('/admin/searchPayrollManager', 'PayrollManagerController@search')->name('admin.payroll.search');
        Route::get('/admin/profilePayrollManager/{id}', 'PayrollManagerController@profile')->name('admin.payroll.profile');

        Route::post('/admin/storePayrollManager', 'PayrollManagerController@store')->name('admin.payroll.store');;
        Route::post('/admin/updatePayrollManager', 'PayrollManagerController@update')->name('admin.payroll.update');;

        Route::post('/admin/setfilterPayrollManager', 'PayrollManagerController@setfilter')->name('admin.payroll.setfilter');
        Route::get('/admin/resetfilterPayrollManager', 'PayrollManagerController@resetfilter')->name('admin.payroll.resetfilter');
        Route::post('/admin/removePayrollManager', 'PayrollManagerController@remove')->name('admin.payroll.remove');
        Route::get('/admin/PayrollResetPassword', 'PayrollManagerController@sendResetPasswordLink')->name('admin.payroll.resetpassword');


        Route::get('/admin/createWorker', 'WorkerController@create')->name('admin.worker.create');
        Route::post('/admin/storeWorker', 'WorkerController@store')->name('admin.worker.store');
        Route::post('/admin/updateWorker', 'WorkerController@update')->name('admin.worker.update');

        Route::get('/admin/searchWorker', 'WorkerController@search')->name('admin.worker.search');
        Route::post('/admin/removeWorker', 'WorkerController@removeWorker')->name('admin.worker.removeWorker');
        Route::post('/admin/setfilterWorker', 'WorkerController@setfilter')->name('admin.worker.setfilter');
        Route::get('/admin/resetfilterWorker', 'WorkerController@resetfilter')->name('admin.worker.resetfilter');

        Route::get('/admin/searchWorkerHired', 'WorkerController@searchHired')->name('admin.worker.searchHired');
        Route::post('/admin/setfilterWorkerHired', 'WorkerController@setfilterHired')->name('admin.worker.setfilterHired');
        Route::get('/admin/resetfilterWorkerHired', 'WorkerController@resetfilterHired')->name('admin.worker.resetfilterHired');

        Route::get('/admin/profileWorker/{id}', 'WorkerController@profile')->name('admin.worker.profile');
        Route::post('/admin/sendVideoProfile/', 'WorkerController@sendVideoProfile')->name('admin.worker.sendVideoProfile');

        Route::get('/admin/special_worker', 'WorkerController@special_worker')->name('admin.worker.special_worker');;
        Route::get('/admin/worker/downloadSummaries/{id}', 'WorkerController@downloadSummaries')->name('admin.worker.downloadpdf');
        Route::get('/admin/workerResetPassword', 'WorkerController@sendResetPasswordLink')->name('admin.worker.resetpassword');
        Route::get('/admin/worker/download', 'WorkerController@download')->name('admin.worker.download');
        Route::get('/admin/worker/downloadCSV', 'WorkerController@download_CSV_Data')->name('admin.worker.downloadcsv');


        Route::get('/admin/createClient', 'ClientController@create');
        Route::post('/admin/storeClient', 'ClientController@store')->name('admin.client.store');;
        Route::post('/admin/updateClient', 'ClientController@update')->name('admin.client.update');;
        Route::get('/admin/searchClient', 'ClientController@search')->name('admin.client.search');
        Route::get('/admin/profileClient/{id}', 'ClientController@profile')->name('admin.client.profile');
        Route::post('/admin/removeClient', 'ClientController@removeClient')->name('admin.client.removeClient');
        Route::post('/admin/setfilterClient', 'ClientController@setfilter')->name('admin.client.setfilter');
        Route::get('/admin/resetfilterClient', 'ClientController@resetfilter')->name('admin.client.resetfilter');
        Route::get('/admin/client/activeWorkers', 'ClientController@activeWorkers')->name('admin.client.activeWorkers');
        Route::get('/admin/client/allWorkers', 'ClientController@allWorkers')->name('admin.client.allWorkers');
        Route::get('/admin/client/assignedClients', 'ClientController@assignedClients')->name('admin.client.assignedClients');
     
        Route::get('/admin/createContact', 'ContactController@create')->name('admin.contact.create'); 
        Route::post('/admin/storeContact', 'ContactController@store')->name('admin.contact.store'); 
        
        Route::get('/admin/searchContact', 'ContactController@search')->name('admin.contact.search');
        Route::post('/admin/setfilterContact', 'ContactController@setfilter')->name('admin.contact.setfilter');
        Route::get('/admin/resetfilterContact', 'ContactController@resetfilter')->name('admin.contact.resetfilter');
        Route::post('/admin/removeContact', 'ContactController@removeContact')->name('admin.contact.removeContact');
        Route::get('/admin/profileContact/{id}', 'ContactController@profile')->name('admin.contact.profile');
        Route::post('/admin/updateContact', 'ContactController@update')->name('admin.contact.update');
        Route::get('/admin/special_contact', 'ContactController@special_contact')->name('admin.contact.special_contact');;
        Route::get('/admin/ContactResetPassword', 'ContactController@sendResetPasswordLink')->name('admin.contact.resetpassword');

        Route::get('/admin/holidaySchedule', 'AdminController@holidaySchedule')->name('admin.holidaySchedule');;
        Route::post('/admin/addholiday', 'AdminController@addholiday')->name('admin.addholiday');;
        Route::post('/admin/updateholiday', 'AdminController@updateholiday')->name('admin.updateholiday');;
        Route::post('/admin/deleteholiday', 'AdminController@deleteholiday')->name('admin.deleteholiday');;

        Route::get('/admin/globalFileds', 'AdminController@globalFileds')->name('admin.globalFileds');;
        Route::post('/admin/updateglobalFileds', 'AdminController@updateGlobalFileds')->name('admin.updateGlobalFileds');;

        Route::get('/admin/immediatePayWU', 'PayrollController@immediateWU')->name('admin.payroll.immediateWU');
        Route::get('/admin/immediatePayVeem', 'PayrollController@immediateVeem')->name('admin.payroll.immediateVeem');
        Route::get('/admin/biWeeklyWU', 'PayrollController@biWeeklyWU')->name('admin.payroll.biWeeklyWU');
        Route::get('/admin/biWeeklyVeem', 'PayrollController@biWeeklyVeem')->name('admin.payroll.biWeeklyVeem');
        Route::get('/admin/viewClosed', 'PayrollController@viewClosed')->name('admin.payroll.viewClosed');
        Route::match(['get', 'post'], '/admin/payroll/setfilter', 'PayrollController@setfilter')->name('admin.payroll.setfilter');
        Route::get('/admin/payroll/resetfilter', 'PayrollController@resetfilter')->name('admin.payroll.resetfilter');
        
        Route::post('/admin/removePayment', 'PayrollController@remove')->name('admin.payment.remove');
        Route::get('/admin/editPayment/{id}', 'PayrollController@edit')->name('admin.payment.edit');
        Route::post('/admin/updatePayment', 'PayrollController@update')->name('admin.payment.update');

        Route::get('/admin/viewReport/{id}', 'PayrollController@viewReport')->name('admin.payroll.viewReport');
        Route::post('/admin/pay', 'PayrollController@pay')->name('admin.payroll.pay');
        Route::post('/admin/change_payment_method', 'PayrollController@changePaymentMethod')->name('admin.payroll.change');
        Route::get('/admin/sendSummary', 'PayrollController@sendSummary')->name('admin.payroll.sendSummary');

        Route::get('/admin/invoicesManual', 'InvoiceController@manual')->name('admin.invoices.manual');
        Route::get('/admin/invoicesAutomatic', 'InvoiceController@automatic')->name('admin.invoices.automatic');
        Route::get('/admin/invoicesNeedsSent', 'InvoiceController@needsSent')->name('admin.invoices.needsSent');
        Route::get('/admin/invoicesPendingClientApproval', 'InvoiceController@pendingClientApproval')->name('admin.invoices.pendingClientApproval');
        Route::get('/admin/invoicesNeedsProcessed', 'InvoiceController@needsProcessed')->name('admin.invoices.needsProcessed');
        Route::get('/admin/invoicesBankVerifications', 'InvoiceController@bankVerifications')->name('admin.invoices.bankVerifications');
        Route::get('/admin/invoicesViewClosed', 'InvoiceController@viewClosed')->name('admin.invoices.viewClosed');

        Route::post('/admin/removeInvoice', 'InvoiceController@remove')->name('admin.invoices.remove');
        Route::get('/admin/editInvoice/{id}', 'InvoiceController@edit')->name('admin.invoices.edit');
        Route::post('/admin/updateInvoice', 'InvoiceController@update')->name('admin.invoices.update');

         Route::post('/admin/invoicesFinalized', 'InvoiceController@finalized')->name('admin.invoices.finalized');
         Route::post('/admin/invoicesSendClient', 'InvoiceController@send_client')->name('admin.invoices.send_client');
         Route::post('/admin/invoicesMarkasSent', 'InvoiceController@mark_sent')->name('admin.invoices.mark_sent');
         Route::post('/admin/invoicesRecalculate', 'InvoiceController@recalculate')->name('admin.invoices.recalculate');
         Route::get('/admin/downloadInvoice/{id}', 'InvoiceController@download')->name('admin.invoices.download');
         Route::post('/admin/deleteInvoice', 'InvoiceController@delete')->name('admin.invoices.delete');

         Route::post('/admin/invoicesApproveByClient', 'InvoiceController@approvebyclient')->name('admin.invoices.approvebyclient');
         Route::post('/admin/invoicesDeclineByClient', 'InvoiceController@declinebyclient')->name('admin.invoices.declinebyclient');

         Route::post('/admin/invoicesVerifyBank', 'InvoiceController@bank_verify')->name('admin.invoices.bank_verify');
         Route::post('/admin/invoicesProcess', 'InvoiceController@process')->name('admin.invoices.process');

        Route::get('/admin/createAdjustment', 'AdjustmentController@create')->name('admin.adjustment.create');
        Route::get('/admin/activeAdjustment', 'AdjustmentController@active')->name('admin.adjustment.active');
        Route::get('/admin/pastAdjustment', 'AdjustmentController@past')->name('admin.adjustment.past');
        Route::get('/admin/pendingAdjustment', 'AdjustmentController@pending')->name('admin.adjustment.pending');
        Route::get('/admin/needApprovalAdjustment', 'AdjustmentController@needsApproval')->name('admin.adjustment.needsApproval');

        Route::post('/admin/submitAdjustmentOneTime', 'AdjustmentController@submit_onetime')->name('admin.adjustment.submit_onetime');
        Route::post('/admin/submitAdjustmentRecurring', 'AdjustmentController@submit_recurring')->name('admin.adjustment.submit_recurring');
        Route::post('/admin/removeAdjustmentOnetime', 'AdjustmentController@remove_onetime')->name('admin.adjustment.remove_onetime');
        Route::post('/admin/removeAdjustmentRecurring', 'AdjustmentController@remove_recurring')->name('admin.adjustment.remove_recurring');
        Route::post('/admin/updateAdjustmentOnetime', 'AdjustmentController@update_onetime')->name('admin.adjustment.update_onetime');
        Route::post('/admin/updateAdjustmentRecurring', 'AdjustmentController@update_recurring')->name('admin.adjustment.update_recurring');

        Route::get('/admin/needsApproval', 'TimeSheetController@needsApproval')->name('admin.timesheet.needsApproval');
        Route::post('/admin/submitApproveTimesheet', 'TimeSheetController@submitApprove')->name('admin.timesheet.submitApprove');
        Route::get('/admin/declineTimecard', 'TimeSheetController@declineTimecard')->name('admin.timesheet.declineTimecard');
        Route::get('/admin/removeTimecard', 'TimeSheetController@removeTimecard')->name('admin.timesheet.removeTimecard');

        Route::get('/admin/currentTimesheets', 'TimeSheetController@current')->name('admin.timesheet.currentTimesheets');;
        Route::post('/admin/submitCurrentTimesheets', 'TimeSheetController@submitCurrent')->name('admin.timesheet.submitCurrent');;
        Route::get('/admin/pendingWorkerApproval', 'TimeSheetController@pendingWorkerApproval')->name('admin.timesheet.pendingWorkerApproval');;
        Route::post('/admin/submitPendingWorkerApproval', 'TimeSheetController@submitPendingWorkerApproval')->name('admin.timesheet.submitPendingWorkerApproval');;
        Route::get('/admin/pastTimesheets', 'TimeSheetController@pastTimesheets')->name('admin.timesheet.pastTimesheets');
        Route::match(['get', 'post'], '/admin/timesheet/setfilter', 'TimeSheetController@setfilter')->name('admin.timesheet.setfilter');
        Route::get('/admin/timesheet/resetfilter', 'TimeSheetController@resetfilter')->name('admin.timesheet.resetfilter');

        Route::get('/admin/PTONeedsApproval', 'PTOController@PTONeedsApproval')->name('admin.PTONeedsApproval');;
        Route::post('/admin/approvePTO', 'PTOController@approvePTO')->name('admin.approvePTO');;
        Route::post('/admin/declinePTO', 'PTOController@declinePTO')->name('admin.declinePTO');;

        Route::get('/admin/PTOOverride', 'PTOController@PTOOverride')->name('admin.PTOOverride');
        Route::get('/admin/PTOsummary', 'PTOController@PTOsummary')->name('admin.PTOsummary');
        Route::get('/admin/PTOpast', 'PTOController@PTOpast')->name('admin.PTOpast');
        Route::post('/admin/updatePTOsummary', 'PTOController@updatePTOsummary')->name('admin.updatePTOsummary');
        Route::post('/admin/submitPTO', 'PTOController@submitPTO')->name('admin.submitPTO');
        Route::get('/admin/exist_pto', 'PTOController@exist_pto')->name('admin.exist_pto');
        Route::match(['get', 'post'], '/admin/pto/setfilter', 'PTOController@setfilter')->name('admin.pto.setfilter');
        Route::get('/admin/pto/resetfilter', 'PTOController@resetfilter')->name('admin.pto.resetfilter');

        Route::get('/admin/createCashAdvance', 'CashAdvanceController@createCashAdvance')->name('admin.createCashAdvance');
        Route::get('/admin/openCashAdvance', 'CashAdvanceController@openCashAdvance')->name('admin.openCashAdvance');
        Route::get('/admin/pastCashAdvance', 'CashAdvanceController@pastCashAdvance')->name('admin.pastCashAdvance');

        Route::post('/admin/submitWorkerCashAdvance', 'CashAdvanceController@submitCashAdvance')->name('admin.submitCashAdvance');
        Route::post('/admin/updateWorkerCashAdvance', 'CashAdvanceController@updateCashAdvance')->name('admin.updateCashAdvance');
        Route::get('/admin/removeWorkerCashAdvance/{id}', 'CashAdvanceController@removeCashAdvance')->name('admin.removeCashAdvance');

        Route::get('/admin/adjustmentOneTime', 'AdjustmentController@adjustmentOneTime')->name('admin.needsApproval.adjustmentOneTime');
        Route::post('/admin/approveAdjustmentOneTime', 'AdjustmentController@approve_adjustmentOneTime')->name('admin.needsApproval.approve_adjustmentOneTime');
        Route::post('/admin/declineAdjustmentOneTime', 'AdjustmentController@decline_adjustmentOneTime')->name('admin.needsApproval.decline_adjustmentOneTime');

        Route::get('/admin/adjustmentRecurring', 'AdjustmentController@adjustmentRecurring')->name('admin.needsApproval.adjustmentRecurring');
        Route::post('/admin/approveadjustmentRecurring', 'AdjustmentController@approve_adjustmentRecurring')->name('admin.needsApproval.approve_adjustmentRecurring');
        Route::post('/admin/declineadjustmentRecurring', 'AdjustmentController@decline_adjustmentRecurring')->name('admin.needsApproval.decline_adjustmentRecurring');

        Route::get('/admin/updateCommets', 'PayrollManagerController@updateCommets')->name('admin.updateCommets');
        

        Route::get('/admin/createPosition', 'PositionController@create')->name('admin.position.create');
        Route::post('/admin/position/store', 'PositionController@store')->name('admin.position.store');
        Route::get('/admin/editPosition/{id}', 'PositionController@edit')->name('admin.position.edit');        
        Route::post('/admin/position/{id}/update', 'PositionController@update')->name('admin.position.update');
        Route::get('/admin/position/{id}/copy', 'PositionController@copy')->name('admin.position.copy');
        Route::get('/admin/openPositions', 'PositionController@open')->name('admin.position.open');
        Route::get('/admin/pastPositions', 'PositionController@past')->name('admin.position.past');
        Route::post('/admin/canditate/save', 'CandidateController@saveCandidate')->name('admin.candidate.save');
        Route::post('/admin/canditate/remove', 'CandidateController@removeCandidate')->name('admin.candidate.remove');
        Route::post('/admin/canditate/final-decision', 'CandidateController@finalDecision')->name('admin.candidate.finaldecision');
        Route::post('/admin/canditate/move-to-group', 'CandidateController@moveToGroup')->name('admin.candidate.move');
        Route::post('/admin/canditate/update-note', 'CandidateController@updateNote')->name('admin.candidate.note.update');
        Route::post('/admin/canditate/update-order', 'CandidateController@updateOrder')->name('admin.candidate.order.update');

        Route::get('/admin/hired/needs-finalized', 'HiredController@needsFinalized')->name('admin.hired.needs_finalized');
        Route::get('/admin/hired/pending-review', 'HiredController@pendingReview')->name('admin.hired.pending_review');
        Route::get('/admin/hired/needs-setup', 'HiredController@needsSetup')->name('admin.hired.needs_setup');
        Route::get('/admin/hired/completed', 'HiredController@completed')->name('admin.hired.completed');
        Route::post('/admin/hired/save', 'HiredController@saveHired')->name('admin.hired.save');
        Route::post('/admin/hired/update-one', 'HiredController@updateOne')->name('admin.hired.update_one');
        Route::post('/admin/hired/remove', 'HiredController@remove')->name('admin.hired.remove');
        Route::post('/admin/hired/update-status', 'HiredController@updateStatus')->name('admin.hired.update_status');
        Route::post('/admin/hired/upload-ica', 'HiredController@uploadICA')->name('admin.hired.upload_ica');
        Route::get('/admin/hired/download-ica', 'HiredController@downloadICA')->name('admin.hired.download_ica');
        
        Route::get('/admin/declined/pending-notice', 'DeclinedController@pendingNotice')->name('admin.declined.pending_notice');
        Route::get('/admin/declined/completed', 'DeclinedController@completed')->name('admin.declined.completed');
        Route::post('/admin/declined/save', 'DeclinedController@saveDeclined')->name('admin.declined.save');
        Route::post('/admin/declined/update-one', 'DeclinedController@updateOne')->name('admin.declined.update_one');
        Route::post('/admin/declined/remove', 'DeclinedController@remove')->name('admin.declined.remove');
        Route::post('/admin/declined/update-status', 'DeclinedController@updateStatus')->name('admin.declined.update_status');
    });

});





