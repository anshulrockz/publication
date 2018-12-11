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

//User Transaction
Route::resource('mytest', 'TestController');
Route::get('datatable/getdata', 'TestController@getPosts')->name('datatable/getdata');
Route::any('importcsv', 'ExpenseController@uploadCsv');
Route::post('print-out/{id}', 'PaymentVendorController@printTables');

Route::get('check-2', function () {
    $users = DB::connection("mysql2")->table("customer_details")->get();
    //see you connections established or not.
    dd($users); 
});

Route::get('register-company', function () {
    return view('auth.register-company');
});

Route::get('/', function () {
    return view('welcome');
});

// Route::match(['get', 'post'], 'register', function(){
//     return redirect('/');
// });

Auth::routes();

//Route::get('/', 'HomeController@index')->name('home');
// Route::group(['middleware' => 'CheckClaimUser'], function() {

	Route::post('claim/status-change', 'ClaimController@status_change');
	Route::resource('claim', 'ClaimController');
// });
//Claim Category
Route::resource('/claim-categories', 'ClaimCategoryController');
Route::get('/sub-claim-ajax/ajax', 'SubClaimCategoryController@id_ajax');
Route::resource('/sub-claim-categories', 'SubClaimCategoryController');

//Route::get('/', 'HomeController@index')->name('home');
Route::get('/dashboard', 'HomeController@index')->name('home');

//Reports
Route::get('report/expenses', 'ReportController@expense');
Route::get('/report/deposits', 'ReportController@deposit');
Route::get('/report/cheques', 'ReportController@cheque');
Route::get('/report/claims', 'ReportController@claim');
Route::get('/report/vendor-tds', 'ReportController@tds');
Route::get('report/received-payments', 'ReportController@received_payment');

//Expense
Route::get('user-deposit/payeename', 'DepositController@payeename');
Route::get('user-deposit/payeebalance', 'DepositController@payeeBalance');

//Deposits
Route::post('/deposits/return/{id}', 'DepositController@return');
Route::resource('/deposits', 'DepositController');

//Payment Vendor
Route::get('payment-vendors/ajax','PaymentVendorController@id_ajax');
Route::post('/payment-vendors/summary/{id}', 'PaymentVendorController@summary');
Route::resource('/payment-vendors', 'PaymentVendorController');

//Payment Others
Route::get('payment/others/cheque-status/{id}', 'ReceivedPaymentController@chequeStatus');
Route::post('payment/others/change-status', 'ReceivedPaymentController@changeStatus');
Route::resource('received-payments', 'ReceivedPaymentController');

//Job details
Route::resource('job-details', 'JobDetailController');

//Item
Route::resource('items', 'ItemController');

//Purchase
Route::resource('purchase', 'PurchaseController');

//Sale
Route::resource('sale', 'SaleController');

//Challan
Route::resource('challan', 'ChallanController');

//Payment History
Route::resource('payment/history', 'PaymentHistoryController');

//User Transaction
Route::resource('user-transactions', 'UserTransactionController');

//Expense
Route::get('expenses/partyname', 'ExpenseController@partyname');
Route::get('expenses/partygstin', 'ExpenseController@partyGSTIN');
Route::get('expenses/paid/{id}', 'ExpenseController@changetopaid');
Route::get('expenses/cancel/{id}', 'ExpenseController@cancel');
Route::resource('/expenses', 'ExpenseController');

//Sub Expense Category
Route::get('/expense-categories/ajax','ExpenseCategoryController@id_ajax');
Route::get('/subexpenses/ajax','SubExpenseController@id_ajax');



// Route::group(['middleware' => 'CheckAdmin'], function() {
	
	//Reports
	// Route::get('/report/deposits', 'ReportController@deposit');
	//Route::get('/report/expenses', 'ReportController@expense');
	Route::get('/report/assets', 'ReportController@asset');
	//Route::get('/report/ledgers', 'ReportController@ledger');
	//Route::get('/report/assets/expiry', 'ReportController@expiry');
	//Route::get('/report/overall', 'ReportController@overall');
	// Route::get('/report/datatable', 'ReportController@datatable_ajax');

	//Asset
	Route::get('assets/old/ajax_voucher_no', 'AssetController@ajax_voucher_no');
	Route::get('assets/new/ajax_voucher_no', 'AssetNewController@ajax_voucher_no');
	Route::resource('/assets/new', 'AssetNewController');
	Route::resource('/assets/old', 'AssetController');

	//Users
	Route::get('users/activate-user/{id}','UserController@activateUser');
	Route::get('/users/ajax','UserController@id_ajax');
	Route::resource('/users', 'UserController');
	
	//Workshop
	Route::get('/workshops/ajax','WorkshopController@id_ajax');

	//Bank
	Route::get('banks/ajax','BankController@id_ajax');
	Route::resource('banks','BankController');

	//Vendor
    Route::get('vendors/ajax','VendorController@id_ajax');
	Route::resource('vendors','VendorController');

	//Sub Sub Expense Category
	//Route::get('/subsubexpenses/ajax','SubSubExpenseController@id_ajax');
	
	//Sub Asset Category
	Route::get('/subassets/ajax','SubAssetController@id_ajax');

	// Route::group(['middleware' => 'CheckSuperUser'], function() {

		//company
		Route::get('cities', 'CompanyController@cities');
		Route::resource('companies', 'CompanyController');
		
		//Workshop
		Route::resource('/locations', 'WorkshopController');

		//Location - Ajax
		//Route::get('/locations/ajax','LocationController@id_ajax');
		//Route::resource('/locations', 'LocationController');

		//Department
		Route::resource('/descriptions', 'DescriptionController');

		//Tax
		Route::resource('/taxes', 'TaxController');

		//Designation
		Route::resource('/designations', 'DesignationController');

		//Employee(user) Type
		Route::resource('/employee-types', 'EmployeeTypeController');

		//Expense Category
		Route::resource('expense-categories', 'ExpenseCategoryController');

		//Sub Expense Category
		Route::resource('sub-expense-categories', 'SubExpenseController');

		//Sub Sub Expense Category - Ajax
		//Route::resource('expense-categories/expense-category', 'SubSubExpenseController');
		
		//Purchase Category
		Route::resource('/purchase-categories', 'PurchaseCategoryController');
		
		//Sub Purchase Category
		Route::get('sub-purchase-categories/fetch-by-id-ajax', 'SubPurchaseController@ajax');
		Route::resource('sub-purchase-categories', 'SubPurchaseController');
		
		//Asset Category
		Route::resource('/asset-categories', 'AssetCategoryController');

		//Sub Asset Category
		Route::resource('/subassets', 'SubAssetController');
		
	// });

// });


