<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Hi_FPT\AppController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UnitsController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\ProductUnitsController;
use App\Http\Controllers\Admin\StockTransactionController;
use App\Http\Controllers\Admin\OrderController;


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


Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::prefix('home')->group(function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/getDataChart', 'HomeController@getDataChart')->name('home.getDataChart');
        Route::get('/getPaymentErrorTableData', 'HomeController@getPaymentErrorTableData')->name('home.getPaymentErrorTableData');
    });
    Route::prefix('file')->controller(FileController::class)->group(function () {
        Route::any('/uploadImageExternal', 'uploadImageExternal')->name('uploadImageExternalB');
        Route::post('/importPhone', 'importPhone')->name('file.importPhone');

    });
    Route::namespace('Admin')->group(function () {
        Route::prefix('settings')->group(function () {
            Route::get('/', 'SettingsController@index')->name('settings.index');
            Route::get('/2', 'SettingsController@index2')->name('settings.index2');
            Route::get('/edit/{id}', 'SettingsController@edit')->name('settings.edit');
            Route::get('/create', 'SettingsController@create')->name('settings.create');
            Route::post('/store', 'SettingsController@store')->name('settings.store');
            Route::put('/update/{id}', 'SettingsController@update')->name('settings.update');
            Route::post('/destroy', 'SettingsController@destroy')->name('settings.destroy');
            Route::get('/initDatatable', 'SettingsController@initDatatable')->name('settings.initDatatable');
        });

        Route::prefix('setting')->group(function () {
            Route::get('/', 'GeneralSettingsController@index')->name('general_settings.index');
            Route::post('/edit', 'GeneralSettingsController@postEdit')->name('general_settings.edit');
            Route::post('/saveUriSetting', 'GeneralSettingsController@saveUriSetting')->name('general_settings.saveUriSetting');
            Route::post('/sendMailManually', 'GeneralSettingsController@sendMailManually')->name('general_settings.sendMailManually');
        });

        Route::get('/start-queue-worker', function () {
            // Use exec() to start the queue worker in the background
            exec('nohup php artisan queue:work > /dev/null 2>&1 &');
            return 'Gửi mail thành công! Vui lòng đợi 2 phút để nhận được mail';
        })->name('runQueue');

        Route::prefix('user')->group(function () {
            Route::get('/', 'UserController@index')->name('user.index');
            Route::get('/edit/{id}', 'UserController@edit')->name('user.edit');
            Route::get('/create', 'UserController@create')->name('user.create');
            Route::post('/store', 'UserController@store')->name('user.store');
            Route::post('/login', 'UserController@login')->name('user.login');
            Route::put('/update/{id}', 'UserController@update')->name('user.update');
            Route::post('/destroy', 'UserController@destroy')->name('user.destroy');
        });

        Route::prefix('products')->group(function () {
            Route::get('/import-view', [ProductsController::class, 'importView'])->name('products.import.view');
            Route::post('/import', [ProductsController::class, 'import'])->name('products.import');
            Route::get('/', 'ProductsController@index')->name('products.index');
            Route::get('/edit/{id}', 'ProductsController@edit')->name('products.edit');
            Route::get('/create', 'ProductsController@create')->name('products.create');
            Route::post('/store', 'ProductsController@store')->name('products.store');
            Route::post('/login', 'ProductsController@login')->name('products.login');
            Route::put('/update/{id}', 'ProductsController@update')->name('products.update');
            Route::post('/destroy', 'ProductsController@destroy')->name('products.destroy');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', 'CategoriesController@index')->name('categories.index');
            Route::get('/edit/{id}', 'CategoriesController@edit')->name('categories.edit');
            Route::get('/create', 'CategoriesController@create')->name('categories.create');
            Route::post('/show', 'CategoriesController@show')->name('categories.show');
            Route::post('/store', 'CategoriesController@store')->name('categories.store');
            Route::post('/login', 'CategoriesController@login')->name('categories.login');
            Route::put('/update/{id}', 'CategoriesController@update')->name('categories.update');
            Route::post('/destroy', 'CategoriesController@destroy')->name('categories.destroy');
        });

        Route::prefix('categories-parent')->group(function () {
            Route::get('/', 'CategoriesParentController@index')->name('categoriesparent.index');
            Route::get('/edit/{id}', 'CategoriesParentController@edit')->name('categoriesparent.edit');
            Route::post('/show', 'CategoriesParentController@show')->name('categoriesparent.show');
            Route::get('/create', 'CategoriesParentController@create')->name('categoriesparent.create');
            Route::post('/store', 'CategoriesParentController@store')->name('categoriesparent.store');
            Route::put('/update/{id}', 'CategoriesParentController@update')->name('categoriesparent.update');
            Route::post('/destroy', 'CategoriesParentController@destroy')->name('categoriesparent.destroy');
            Route::post('/change-status', 'CategoriesParentController@changeStatus')->name('categoriesparent.change-status');
            Route::get('/initDatatable', 'CategoriesParentController@initDatatable')->name('categoriesparent.initDatatable');
        });

        Route::prefix('brand')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('brand.index');
            Route::get('/import-view', [BrandController::class, 'importView'])->name('brand.import.view');
            Route::post('/import', [BrandController::class, 'import'])->name('brand.import');
            Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
            Route::post('/show', [BrandController::class, 'show'])->name('brand.show');
            Route::get('/create', [BrandController::class, 'create'])->name('brand.create');
            Route::post('/store', [BrandController::class, 'store'])->name('brand.store');
            Route::put('/update/{id}', [BrandController::class, 'update'])->name('brand.update');
            Route::post('/destroy', [BrandController::class, 'destroy'])->name('brand.destroy');
            Route::post('/change-status', [BrandController::class, 'changeStatus'])->name('brand.change-status');
            Route::get('/initDatatable', [BrandController::class, 'initDatatable'])->name('brand.initDatatable');
        });

        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomersController::class, 'index'])->name('customers.index');
            Route::get('/import-view', [CustomersController::class, 'importView'])->name('customers.import.view');
            Route::post('/import', [CustomersController::class, 'import'])->name('customers.import');
            Route::get('/edit/{id}', [CustomersController::class, 'edit'])->name('customers.edit');
            Route::post('/show', [CustomersController::class, 'show'])->name('customers.show');
            Route::get('/create', [CustomersController::class, 'create'])->name('customers.create');
            Route::post('/store', [CustomersController::class, 'store'])->name('customers.store');
            Route::put('/update/{id}', [CustomersController::class, 'update'])->name('customers.update');
            Route::post('/destroy', [CustomersController::class, 'destroy'])->name('customers.destroy');
            Route::post('/change-status', [CustomersController::class, 'changeStatus'])->name('customers.change-status');
            Route::get('/initDatatable', [CustomersController::class, 'initDatatable'])->name('customers.initDatatable');
        });

        Route::prefix('units')->group(function () {
            Route::get('/', [UnitsController::class, 'index'])->name('units.index');
            Route::get('/import-view', [UnitsController::class, 'importView'])->name('units.import.view');
            Route::post('/import', [UnitsController::class, 'import'])->name('units.import');
            Route::get('/edit/{id}', [UnitsController::class, 'edit'])->name('units.edit');
            Route::post('/show', [UnitsController::class, 'show'])->name('units.show');
            Route::get('/create', [UnitsController::class, 'create'])->name('units.create');
            Route::post('/store', [UnitsController::class, 'store'])->name('units.store');
            Route::put('/update/{id}', [UnitsController::class, 'update'])->name('units.update');
            Route::post('/destroy', [UnitsController::class, 'destroy'])->name('units.destroy');
            Route::post('/change-status', [UnitsController::class, 'changeStatus'])->name('units.change-status');
            Route::get('/initDatatable', [UnitsController::class, 'initDatatable'])->name('units.initDatatable');
        });
        
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('orders.index');
            Route::get('/import-view', [OrderController::class, 'importView'])->name('orders.import.view');
            Route::post('/import', [OrderController::class, 'import'])->name('orders.import');
            Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('orders.edit');
            Route::post('/show', [OrderController::class, 'show'])->name('orders.show');
            Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
            Route::post('/store', [OrderController::class, 'store'])->name('orders.store');
            Route::put('/update/{id}', [OrderController::class, 'update'])->name('orders.update');
            Route::post('/destroy', [OrderController::class, 'destroy'])->name('orders.destroy');
            Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

            Route::get('/initDatatable', [OrderController::class, 'initDatatable'])->name('orders.initDatatable');
        });

        Route::prefix('product-units')->group(function () {
            Route::get('/', [ProductUnitsController::class, 'index'])->name('product-units.index');
            Route::get('/import-view', [ProductUnitsController::class, 'importView'])->name('product-units.import.view');
            Route::post('/import', [ProductUnitsController::class, 'import'])->name('product-units.import');
            Route::get('/edit/{id}', [ProductUnitsController::class, 'edit'])->name('product-units.edit');
            Route::post('/show', [ProductUnitsController::class, 'show'])->name('product-units.show');
            Route::get('/create', [ProductUnitsController::class, 'create'])->name('product-units.create');
            Route::post('/store', [ProductUnitsController::class, 'store'])->name('product-units.store');
            Route::put('/update/{id}', [ProductUnitsController::class, 'update'])->name('product-units.update');
            Route::post('/destroy', [ProductUnitsController::class, 'destroy'])->name('product-units.destroy');
            Route::post('/change-status', [ProductUnitsController::class, 'changeStatus'])->name('product-units.change-status');
            Route::get('/initDatatable', [ProductUnitsController::class, 'initDatatable'])->name('product-units.initDatatable');
        });

        Route::prefix('comment')->group(function () {
            Route::get('/', [CommentController::class, 'index'])->name('comment.index');
            Route::post('/show', [CommentController::class, 'show'])->name('comment.show');
            Route::post('/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');
            Route::post('/change-status', [CommentController::class, 'changeStatus'])->name('comment.change-status');
            Route::get('/initDatatable', [CommentController::class, 'initDatatable'])->name('comment.initDatatable');
        });

        Route::prefix('stock-transaction')->group(function () {
            Route::get('/', [StockTransactionController::class, 'index'])->name('stock-transaction.index');
            Route::get('/import-view', [StockTransactionController::class, 'importView'])->name('stock-transaction.import.view');
            Route::post('/import-excel', [StockTransactionController::class, 'importExcel'])->name('stock-transaction.import.excel');
            Route::post('/storeImport', [StockTransactionController::class, 'storeImport'])->name('stock-transaction.storeImport');
            Route::post('/storeExport', [StockTransactionController::class, 'storeExport'])->name('stock-transaction.storeExport');
            Route::get('/get-product-units/{productId}', [StockTransactionController::class, 'getProductUnits']);
            Route::post('/show', [StockTransactionController::class, 'show'])->name('stock-transaction.show');
            Route::get('/import', [StockTransactionController::class, 'import'])->name('stock-transaction.import');
            Route::get('/list', [StockTransactionController::class, 'list'])->name('stock-transaction.list');
            Route::get('/export', [StockTransactionController::class, 'export'])->name('stock-transaction.export');
            Route::get('/initDatatable', [StockTransactionController::class, 'initDatatable'])->name('comment.initDatatable');
        });

        Route::prefix('vouchers')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('vouchers.index');
            Route::get('/edit/{id}', [VoucherController::class, 'edit'])->name('vouchers.edit');
            Route::post('/show', [VoucherController::class, 'show'])->name('vouchers.show');
            Route::get('/create', [VoucherController::class, 'create'])->name('vouchers.create');
            Route::post('/store', [VoucherController::class, 'store'])->name('vouchers.store');
            Route::put('/update/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
            Route::post('/destroy', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
            Route::post('/change-status', [VoucherController::class, 'changeStatus'])->name('vouchers.change-status');
            Route::get('/initDatatable', [VoucherController::class, 'initDatatable'])->name('vouchers.initDatatable');
        });

        Route::prefix('warehouse')->group(function () {
            Route::get('/', [WarehouseController::class, 'index'])->name('warehouse.index');
            Route::get('/import-view', [WarehouseController::class, 'importView'])->name('warehouse.import.view');
            Route::post('/import', [WarehouseController::class, 'import'])->name('warehouse.import');
            Route::get('/edit/{id}', [WarehouseController::class, 'edit'])->name('warehouse.edit');
            Route::post('/show', [WarehouseController::class, 'show'])->name('warehouse.show');
            Route::get('/create', [WarehouseController::class, 'create'])->name('warehouse.create');
            Route::post('/store', [WarehouseController::class, 'store'])->name('warehouse.store');
            Route::put('/update/{id}', [WarehouseController::class, 'update'])->name('warehouse.update');
            Route::post('/destroy', [WarehouseController::class, 'destroy'])->name('warehouse.destroy');
            Route::post('/change-status', [WarehouseController::class, 'changeStatus'])->name('warehouse.change-status');
            Route::get('/initDatatable', [WarehouseController::class, 'initDatatable'])->name('warehouse.initDatatable');
        });

        Route::prefix('stock')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('stock.index');
            Route::get('/edit/{id}', [StockController::class, 'edit'])->name('stock.edit');
            Route::post('/show', [StockController::class, 'show'])->name('stock.show');
            Route::get('/create', [StockController::class, 'create'])->name('stock.create');
            Route::post('/store', [StockController::class, 'store'])->name('stock.store');
            Route::put('/update/{id}', [StockController::class, 'update'])->name('stock.update');
            Route::post('/destroy', [StockController::class, 'destroy'])->name('stock.destroy');
            Route::post('/change-status', [StockController::class, 'changeStatus'])->name('stock.change-status');
            Route::get('/initDatatable', [StockController::class, 'initDatatable'])->name('stock.initDatatable');
        });

        Route::prefix('supplier')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
            Route::get('/import-view', [SupplierController::class, 'importView'])->name('supplier.import.view');
            Route::post('/import', [SupplierController::class, 'import'])->name('supplier.import');
            Route::get('/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
            Route::post('/show', [SupplierController::class, 'show'])->name('supplier.show');
            Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
            Route::post('/store', [SupplierController::class, 'store'])->name('supplier.store');
            Route::put('/update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
            Route::post('/destroy', [SupplierController::class, 'destroy'])->name('supplier.destroy');
            Route::post('/change-status', [SupplierController::class, 'changeStatus'])->name('supplier.change-status');
            Route::get('/initDatatable', [SupplierController::class, 'initDatatable'])->name('supplier.initDatatable');
        });

        Route::prefix('groups')->group(function () {
            Route::get('/', 'GroupsController@index')->name('groups.index');
            Route::get('/edit/{id?}', 'GroupsController@edit')->name('groups.edit');
            Route::get('/create', 'GroupsController@create')->name('groups.create');
            Route::post('/save', 'GroupsController@save')->name('groups.store');
            Route::post('/destroy', 'GroupsController@destroy')->name('groups.destroy');
            Route::get('/getList', 'GroupsController@getList')->name('groups.getList');
        });
        Route::prefix('modules')->group(function () {
            Route::get('/', 'ModulesController@index')->name('modules.index');
            Route::get('/edit/{id}', 'ModulesController@edit')->name('modules.edit');
            Route::post('/show', 'ModulesController@show')->name('modules.show');
            Route::get('/create', 'ModulesController@create')->name('modules.create');
            Route::post('/store', 'ModulesController@store')->name('modules.store');
            Route::post('/update/{id}', 'ModulesController@update')->name('modules.update');
            Route::post('/destroy', 'ModulesController@destroy')->name('modules.destroy');
            Route::get('/initDatatable', 'ModulesController@initDatatable')->name('modules.initDatatable');
        });

        Route::prefix('groupmodule')->group(function () {
            Route::get('/', 'GroupmoduleController@index')->name('groupmodule.index');
            Route::get('/edit/{id}', 'GroupmoduleController@edit')->name('groupmodule.edit');
            Route::get('/create', 'GroupmoduleController@create')->name('groupmodule.create');
            Route::post('/store', 'GroupmoduleController@store')->name('groupmodule.store');
            Route::put('/update/{id}', 'GroupmoduleController@update')->name('groupmodule.update');
            Route::post('/destroy', 'GroupmoduleController@destroy')->name('groupmodule.destroy');
            Route::get('/initDatatable', 'GroupmoduleController@initDatatable')->name('groupmodule.initDatatable');
        });
        Route::prefix('roles')->group(function () {
            Route::get('/', 'RolesController@index')->name('roles.index');
            Route::get('/edit/{id?}', 'RolesController@edit')->name('roles.edit');
            Route::get('/create', 'RolesController@create')->name('roles.create');
            Route::post('/save', 'RolesController@save')->name('roles.save');
            Route::post('/destroy', 'RolesController@destroy')->name('roles.destroy');
            Route::get('/getList', 'RolesController@getList')->name('roles.getList');

        });

        Route::prefix('role')->group(function () {
            Route::get('/', 'RoleController@index')->name('role.index');
            Route::get('/edit/{id?}', 'RoleController@edit')->name('role.edit');
            Route::post('/update/{id?}', 'RoleController@update')->name('role.update');
            Route::get('/create', 'RoleController@create')->name('role.create');
            Route::post('/store', 'RoleController@store')->name('role.store');
            Route::post('/destroy', 'RoleController@destroy')->name('role.destroy');
        });

        Route::prefix('logactivities')->group(function () {
            Route::get('/', 'LogactivitiesController@index')->name('logactivities.index');
            Route::post('/clearLog', 'LogactivitiesController@clearLog')->name('logactivities.clearLog');
            Route::delete('/destroy/{id}', 'LogactivitiesController@destroy')->name('logactivities.destroy');
            Route::get('/initDatatable', 'LogactivitiesController@initDatatable')->name('logactivities.initDatatable');
        });

    });
    Route::namespace('Hdi')->group(function () {
        Route::prefix('checklistmanage')->group(function () {
            Route::get('/', 'ChecklistmanageController@index')->name('checklistmanage.index');
            Route::post('/sendStaff', 'ChecklistmanageController@sendStaff')->name('checklistmanage.sendStaff');
            Route::post('/completeChecklist', 'ChecklistmanageController@completeChecklist')->name('checklistmanage.completeChecklist');
        });
        Route::prefix('closehelprequest')->group(function () {
            Route::get('/', 'ClosehelprequestController@index')->name('closehelprequest.index');
            Route::post('/getListReportByContract', 'ClosehelprequestController@getListReportByContract')->name('closehelprequest.getListReportByContract');
            Route::post('/closeRequest', 'ClosehelprequestController@closeRequest')->name('closehelprequest.closeRequest');
        });

        Route::prefix('checkuserinfo')->group(function () {
            Route::get('/{info?}', 'CheckUserInfoController@index')->name('checkuserinfo.index');
            // Route::get('','CheckUserInfoController@checkUserInfo')->name('checkuserinfo.checkUserInfo');
        });

    });
    Route::namespace('Hi_FPT')->group(function () {
        Route::prefix('manageotp')->group(function () {
            Route::get('/', 'ManageotpController@index')->name('manageotp.index');
            Route::get('/handle/{phone?}/{action?}', 'ManageotpController@handle')->name('manageotp.handle');
        });
        Route::prefix('hidepayment')->group(function () {
            Route::get('/', 'HidepaymentController@index')->name('hidepayment.index');
            Route::post('/hide', 'HidepaymentController@hide')->name('hidepayment.hide');
            Route::get('/initDatatable', 'HidepaymentController@initDatatable')->name('hidepayment.initDatatable');
        });
        Route::prefix('modeminfo')->group(function () {
            Route::get('/', 'ModeminfoController@index')->name('modeminfo.index');
            Route::get('/searchByContractNoOrId', 'ModeminfoController@searchByContractNoOrId')->name('modeminfo.searchByContractNoOrId');
            Route::get('/initDatatable', 'ModeminfoController@initDatatable')->name('modeminfo.initDatatable');
        });

        Route::prefix('IpMacOnline')->group(function () {
            Route::get('/', 'ModeminfoController@search')->name('IpMacOnline.index');
            Route::get('/searchByObjId', 'ModeminfoController@searchByObjId')->name('IpMacOnline.searchByObjId');
        });

        Route::prefix('iconmanagement')->group(function () {
            Route::get('/', 'IconmanagementController@index')->name('iconmanagement.index');
            Route::get('/edit/{id?}', 'IconmanagementController@edit')->name('iconmanagement.edit');
            Route::post('/save', 'IconmanagementController@save')->name('iconmanagement.save');
            Route::get('/detail/{id?}', 'IconmanagementController@detail')->name('iconmanagement.detail');
            Route::post('/upload', 'IconmanagementController@upload')->name('iconmanagement.upload');
            Route::post('/destroy', 'IconmanagementController@destroy')->name('iconmanagement.destroy');
            // Route::get('/searchByContractNoOrId','IconmanagementController@searchByContractNoOrId')->name('iconmanagement.searchByContractNoOrId');
            Route::get('/initDatatable', 'IconmanagementController@initDatatable')->name('iconmanagement.initDatatable');
        });

        Route::prefix('iconcategory')->group(function () {
            Route::get('/', 'IconcategoryController@index')->name('iconcategory.index');
            Route::get('/edit/{id?}', 'IconcategoryController@edit')->name('iconcategory.edit');
            Route::post('/save', 'IconcategoryController@save')->name('iconcategory.save');
            Route::get('/detail/{id?}', 'IconcategoryController@detail')->name('iconcategory.detail');
            Route::post('/upload', 'IconcategoryController@upload')->name('iconcategory.upload');
            Route::post('/destroy', 'IconcategoryController@destroy')->name('iconcategory.destroy');
            // Route::get('/searchByContractNoOrId','IconmanagementController@searchByContractNoOrId')->name('iconmanagement.searchByContractNoOrId');
            Route::get('/initDatatable', 'IconcategoryController@initDatatable')->name('iconcategory.initDatatable');
        });

        Route::prefix('iconconfig')->group(function () {
            Route::get('/', 'IconconfigController@index')->name('iconconfig.index');
            Route::get('/edit/{id?}', 'IconconfigController@edit')->name('iconconfig.edit');
            Route::post('/save', 'IconconfigController@save')->name('iconconfig.save');
            Route::get('/detail/{id?}', 'IconconfigController@detail')->name('iconconfig.edit');
            Route::post('/upload', 'IconconfigController@upload')->name('iconconfig.upload');
            Route::post('/destroy', 'IconconfigController@destroy')->name('iconconfig.destroy');
            // Route::get('/searchByContractNoOrId','IconconfigController@searchByContractNoOrId')->name('iconconfig.searchByContractNoOrId');
            Route::get('/initDatatable', 'IconconfigController@initDatatable')->name('iconconfig.initDatatable');
        });

        Route::prefix('iconapproved')->group(function () {
            Route::get('/', 'IconapprovedController@index')->name('iconapproved.index');
            Route::get('/edit/{id?}', 'IconapprovedController@edit')->name('iconapproved.edit');
            Route::post('/save', 'IconapprovedController@save')->name('iconapproved.save');
            Route::get('/detail/{id?}', 'IconapprovedController@detail')->name('iconapproved.detail');
            Route::post('/upload', 'IconapprovedController@upload')->name('iconapproved.upload');
            Route::post('/destroyByApprovedRole', 'IconapprovedController@destroyByApprovedRole')->name('iconapproved.destroyByApprovedRole');
            Route::get('/destroy/{id?}', 'IconapprovedController@destroy')->name('iconapproved.destroy');
            Route::get('/initDatatable', 'IconapprovedController@initDatatable')->name('iconapproved.initDatatable');
        });

        Route::prefix('supportcode')->group(function () {
            Route::get('/', 'SupportCodeController@index')->name('supportcode.index');
            Route::post('/open-support-code', 'SupportCodeController@openSupportCode')->name('supportcode.openSupportCode');
            Route::get('/log', 'SupportCodeController@log')->name('supportcode.log');

        });
        Route::prefix('laptop-orders')->group(function () {
            Route::get('/', 'LaptopOrdersController@index')->name('laptop-orders.index');
            Route::post('/', 'LaptopOrdersController@index')->name('laptop-orders.index');
            Route::get('/edit/{id?}', 'LaptopOrdersController@edit')->name('laptop-orders.edit');
            Route::post('/update/{id?}', 'LaptopOrdersController@update')->name('laptop-orders.update');
        });

        Route::prefix('helper')->group(function () {
            Route::get('/', 'HelperController@index')->name('helper.index');
            Route::get('/create', 'HelperController@create')->name('helper.create');
            Route::post('/store', 'HelperController@store')->name('helper.store');
            Route::get('/edit/{id?}', 'HelperController@edit')->name('helper.edit');
            Route::put('/update/{id?}', 'HelperController@update')->name('helper.update');
            Route::delete('/destroy/{id?}', 'HelperController@destroy')->name('helper.destroy');

        });

        Route::prefix('supportsystem')->group(function () {
            Route::get('/', 'SupportSystemController@index')->name('supportsystem.index');
            Route::get('/create', 'SupportSystemController@create')->name('supportsystem.create');
            Route::post('/store', 'SupportSystemController@store')->name('supportsystem.store');
            Route::get('/edit/{id?}', 'SupportSystemController@edit')->name('supportsystem.edit');
            Route::put('/update/{id?}', 'SupportSystemController@update')->name('supportsystem.update');
            Route::delete('/destroy/{id?}', 'SupportSystemController@destroy')->name('supportsystem.destroy');
            Route::post('/upload', 'SupportSystemController@upload')->name('supportsystem.upload');
            // Route::post('/upload', 'SupportSystemController@upload')->name('supportsystem.upload');

        });

        Route::prefix('customer_locations')->group(function () {
            Route::get('/', 'CustomerLocationsController@index')->name('customer_locations.index');
            Route::get('/create', 'CustomerLocationsController@create')->name('customer_locations.create');
            Route::post('/store', 'CustomerLocationsController@store')->name('customer_locations.store');
            Route::get('/edit/{customer_location_id?}', 'CustomerLocationsController@edit')->name('customer_locations.edit');
            Route::put('/update/{customer_location_id?}', 'CustomerLocationsController@update')->name('customer_locations.update');
            Route::delete('/destroy/{customer_location_id?}', 'CustomerLocationsController@destroy')->name('customer_locations.destroy');

        });

        Route::prefix('ftel_branch')->group(function () {
            Route::get('/', 'FtelBranchController@index')->name('ftel_branch.index');
            Route::get('/create', 'FtelBranchController@create')->name('ftel_branch.create');
            Route::post('/store', 'FtelBranchController@store')->name('ftel_branch.store');
            Route::get('/edit/{customer_location_id?}', 'FtelBranchController@edit')->name('ftel_branch.edit');
            Route::put('/update/{customer_location_id?}', 'FtelBranchController@update')->name('ftel_branch.update');
            Route::delete('/destroy/{customer_location_id?}', 'FtelBranchController@destroy')->name('ftel_branch.destroy');

        });

        /*
         * Route Public
         * Create by trinhhdp
        */


        Route::prefix('bannermanage')->group(function () {
            Route::get('/', 'BannerController@index')->name('bannermanage.index');
            Route::post('/store', 'BannerController@store')->name('bannermanage.store');
            Route::get('/export/{id}', 'BannerController@export_click_phone')->name('bannermanage.export');
            Route::post('/update/{id}', 'BannerController@update')->name('bannermanage.update');
            Route::post('/updateordering', 'BannerController@update_order')->name('bannermanage.updateOrder');
            Route::post('/update-banner-fconnect', 'BannerController@update_banner_fconnect')->name('bannermanage.update_banner_fconnect');
            Route::get('/show/{id}', 'BannerController@show')->name('bannermanage.view');
        });

        // Route::prefix('app')->group(function () {
        //     Route::get('/', [AppController::class, 'index'])->name('app.index');
        //     Route::post('/', [AppController::class, 'index'])->name('app.index');
        //     Route::post('/chart', [AppController::class, 'postChart'])->name('app.post.chart');
        //     Route::get('/export', [AppController::class, 'export'])->name('app.export');
        //     Route::get('download/{filename}', function ($filename) {
        //         return response()->download(public_path('file/' . $filename), $filename);
        //     });
        // });

        // Route::prefix('section-log')->group(function () {
        //     Route::get('/', [SectionLogController::class, 'index'])->name('sectionLog.index');
        //     Route::get('/store', [SectionLogController::class, 'store'])->name('sectionLog.store');
        // });


        // Route::prefix('statistics')->group(function () {
        //     Route::get('/', [StatisticController::class, 'index'])->name('statistics.index');
        // });

        // Route::prefix('ftel-phone')->controller(FtelPhoneController::class)->group(function () {
        //     Route::get('/', 'index')->name('ftel_phone.index');
        //     Route::get('/create', 'create')->name('ftel_phone.create');
        //     Route::post('/create', 'create')->name('ftel_phone.store');
        //     Route::post('/show', 'show')->name('ftel_phone.show');
        //     Route::post('/update/{id}', 'update')->name('ftel_phone.update');
        // });

        // Route::prefix('screen')->group(function () {
        //     Route::get('/', [ScreenController::class, 'index'])->name('screen.index');
        //     Route::get('/create', [ScreenController::class, 'create'])->name('screen.create');
        //     Route::post('/store', [ScreenController::class, 'store'])->name('screen.store');
        //     Route::get('/edit/{id}', [ScreenController::class, 'show'])->name('screen.edit');
        //     Route::post('/update/{id}', [ScreenController::class, 'update'])->name('screen.update');
        //     Route::post('/delete/', [ScreenController::class, 'delete'])->name('screen.delete');
        // });

        // Route::prefix('deeplink')->group(function () {
        //     Route::get('/', [DeeplinkController::class, 'index'])->name('deeplink.index');
        //     Route::get('/create', [DeeplinkController::class, 'create'])->name('deeplink.create');
        //     Route::post('/store', [DeeplinkController::class, 'store'])->name('deeplink.store');
        //     Route::get('/edit/{id}', [DeeplinkController::class, 'show'])->name('deeplink.edit');
        //     Route::post('/update/{id}', [DeeplinkController::class, 'update'])->name('deeplink.update');
        //     Route::post('/delete/{id}', [DeeplinkController::class, 'delete'])->name('deeplink.delete');
        // });

        // Route::prefix('behavior')->group(function () {
        //     Route::get('/', [BehaviorController::class, 'index'])->name('behavior.index');
        //     Route::post('/', [BehaviorController::class, 'index'])->name('behavior.index');
        //     Route::post('/store', [BehaviorController::class, 'store'])->name('behavior.post');
        //     Route::post('/analysis', [BehaviorController::class, 'analysis'])->name('behavior.analysis');
        // });

        // Route::prefix('get-phone-number')->group(function () {
        //     Route::get('/', [GetPhoneNumberController::class, 'index'])->name('getPhoneNumber.index');
        //     Route::post('/store', [GetPhoneNumberController::class, 'store'])->name('getPhoneNumber.store');
        // });

        // Route::prefix('payment-support')->controller(PaymentSupportController::class)->group(function () {
        //     Route::get('/', 'index')->name('paymentSupport.index');
        //     Route::post('/update/{id}', 'update')->name('paymentSupport.update');
        //     Route::get('/show/{id}', 'show')->name('paymentSupport.show');
        // });

        // Route::prefix('render-deeplink')->group(function () {
        //     Route::get('/', [RenderDeeplinkController::class, 'index'])->name('renderDeeplink.index');
        //     Route::post('/', [RenderDeeplinkController::class, 'store'])->name('renderDeeplink.post');
        // });

        // Route::prefix('testv2')->controller(Test::class)->group(function () {
        //     Route::get('/admin', 'index')->name('test.index');
        //     Route::get('/create', 'create')->name('test.create');
        // });

        // Route::prefix('user-analytics')->controller(UserAnalyticController::class)->group(function () {
        //     Route::get('/', 'index')->name('tracking.userAnalytics');
        //     Route::post('/', 'index')->name('tracking.userAnalytics');
        // });

        // Route::prefix('employees-updates')->group(function () {
        //     Route::get('/', [UpdateEmployeesFromExcelFileController::class, 'index'])->name('employees_updates.index');
        //     Route::get('/create', [UpdateEmployeesFromExcelFileController::class, 'create'])->name('employees_updates.create');
        //     Route::post('/store', [UpdateEmployeesFromExcelFileController::class, 'stores'])->name('employees_updates.store');
        //     Route::get('/edit/{id}', [UpdateEmployeesFromExcelFileController::class, 'edit'])->name('employees_updates.edit');
        //     Route::post('/update/{id}', [UpdateEmployeesFromExcelFileController::class, 'update'])->name('employees_updates.update');
        //     Route::post('/check', [UpdateEmployeesFromExcelFileController::class, 'check'])->name('employees_updates.check');
        //     Route::post('/import', [UpdateEmployeesFromExcelFileController::class, 'import'])->name('employees_updates.import');
        //     Route::get('/initDatatable', [UpdateEmployeesFromExcelFileController::class, 'initDatatable'])->name('employees_updates.initDatatable');
        // });

        Route::prefix('unlockdeleteuser')->group(function () {
            Route::get('/', 'UnlockDeleteUserLogsController@index')->name('unlockdeleteuser.index');
            Route::get('/handle/{phone?}', 'UnlockDeleteUserLogsController@handle')->name('unlockdeleteuser.handle');
        });

        Route::prefix('applogfilter')->group(function () {
            Route::get('/', 'AppLogController@index')->name('applogfilter.index');
        });

        // Route::prefix('employees')->group(function () {
        //     Route::get('/', [EmployeesController::class, 'index'])->name('employees.index');
        //     Route::get('/create', [EmployeesController::class, 'create'])->name('employees.create');
        //     Route::post('/store', [EmployeesController::class, 'store'])->name('employees.store');
        //     Route::get('/edit/{id}', [EmployeesController::class, 'edit'])->name('employees.edit');
        //     Route::put('/update/{id}', [EmployeesController::class, 'update'])->name('employees.update');
        // });

        Route::prefix('import-log-report-customer-info-marketing')->group(function () {
            Route::get('/', 'ReportCustomerMarketingController@index')->name('importlogreportcustomerinfomarketing.index');
            Route::post('/upload-file', 'ReportCustomerMarketingController@uploadFile')->name('importlogreportcustomerinfomarketing.uploadFile');
            Route::get('/export-result/{id}', 'ReportCustomerMarketingController@exportResult')->name('importlogreportcustomerinfomarketing.exportResult');
        });
    });
    Route::prefix('profile')->group(function () {
        Route::post('/changePassword', 'ProfileController@changePassword')->name('profile.changePassword');
        Route::post('/updateprofile', 'ProfileController@updateprofile')->name('profile.updateprofile');
    });

    Route::namespace('SmsWorld')->group(function () {
        Route::prefix('smsworld')->group(function () {
            Route::any('/{phonecode?}/{phone?}/{date?}', 'OtpController@logs')->name('smsworld.logs');
        });
    });

    Route::namespace('Report')->group(function () {
        Route::prefix('appinstallreport')->group(function () {
            Route::get('/', 'AppinstallreportController@index')->name('appinstallreport.index');
            Route::get('/initDatatableByDate', 'AppinstallreportController@initDatatableByDate')->name('appinstallreport.initDatatableByDate');
            Route::get('/initDatatableByWeek', 'AppinstallreportController@initDatatableByWeek')->name('appinstallreport.initDatatableByWeek');
            Route::get('/initDatatableByMonth', 'AppinstallreportController@initDatatableByMonth')->name('appinstallreport.initDatatableByMonth');
            Route::post('/export', 'AppinstallreportController@export')->name('appinstallreport.export');
        });
        Route::prefix('reportsalebydate')->group(function () {
            Route::get('/', 'SalereportbydateController@index')->name('reportsalebydate.index');
        });
        Route::prefix('reportsalebydatedoanhthu')->group(function () {
            Route::get('/', 'SalereportbydatedoanhthuController@index')->name('reportsalebydatedoanhthu.index');
        });
        Route::prefix('errorpaymentchart')->group(function () {
            Route::get('/', 'ErrorpaymentchartController@index')->name('errorpaymentchart.index');
            Route::post('/getPaymentErrorUserSystem', 'ErrorpaymentchartController@getPaymentErrorUserSystem')->name('errorpaymentchart.getPaymentErrorUserSystem');
            Route::post('/getPaymentErrorDetail', 'ErrorpaymentchartController@getPaymentErrorDetail')->name('errorpaymentchart.getPaymentErrorDetail');
        });
        Route::prefix('laptopordersbyproduct')->group(function () {
            $request = $_GET;
            Route::get('/', 'ReportLaptopOrdersByProductController@index')->name('laptopordersbyproduct.index');
            Route::get('/renderncctable', 'ReportLaptopOrdersByProductController@renderNccTable')->name('laptopordersbyproduct.renderNccTable');
            Route::get('/renderProductTable', 'ReportLaptopOrdersByProductController@renderProductTable')->name('laptopordersbyproduct.renderProductTable');
            Route::get('/renderMerchantTable', 'ReportLaptopOrdersByProductController@renderMerchantTable')->name('laptopordersbyproduct.renderMerchantTable');
        });
        Route::prefix('salereportdatamultiservice')->group(function () {
            $request = $_GET;
            switch (@$request['submitbutton']) {
                case 'Search':
                    Route::get('/', 'SaleReportDataMultiServiceController@index')->name('salereportdatamultiservice.index');
                    break;
                case 'Phone export':
                    Route::get('/', 'SaleReportDataMultiServiceController@exportPhoneOnly')->name('salereportdatamultiservice.exportphoneonly');
                    break;
                case 'All export':
                    Route::get('/', 'SaleReportDataMultiServiceController@exportAll')->name('salereportdatamultiservice.exportall');
                    break;
                default:
                    Route::get('/', 'SaleReportDataMultiServiceController@index')->name('salereportdatamultiservice.index');
            }
        });
        Route::prefix('reporttrackingcusbehaviormonthly')->group(function () {
            Route::get('/', 'ReportTrackingCusBehaviorMonthlyController@index')->name('reporttrackingcusbehaviormonthly.index');
            Route::get('/getDataActiveMonthly', 'ReportTrackingCusBehaviorMonthlyController@getDataActiveMonthly')->name('reporttrackingcusbehaviormonthly.getDataActiveMonthly');
            Route::get('/getDataActivePttbMonthly', 'ReportTrackingCusBehaviorMonthlyController@getDataActivePttbMonthly')->name('reporttrackingcusbehaviormonthly.getDataActivePttbMonthly');
            Route::get('/paymentMonthly', 'ReportTrackingCusBehaviorMonthlyController@paymentMonthly')->name('reporttrackingcusbehaviormonthly.paymentMonthly');
            Route::get('/newServiceRegister', 'ReportTrackingCusBehaviorMonthlyController@newServiceRegister')->name('reporttrackingcusbehaviormonthly.newServiceRegister');
            Route::get('/upgradeServiceRegister', 'ReportTrackingCusBehaviorMonthlyController@upgradeServiceRegister')->name('reporttrackingcusbehaviormonthly.upgradeServiceRegister');
            Route::get('/activeNet', 'ReportTrackingCusBehaviorMonthlyController@activeNet')->name('reporttrackingcusbehaviormonthly.activeNet');
            Route::get('/chatData', 'ReportTrackingCusBehaviorMonthlyController@chatData')->name('reporttrackingcusbehaviormonthly.chatData');
        });
        Route::prefix('dau-wau-mau-report')->group(function () {
            Route::get('/', 'DauWauMauReportController@index')->name('dauwaumaureport.index');
        });
    });
}
);
