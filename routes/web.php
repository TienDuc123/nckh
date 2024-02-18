<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\CheckUsers;
use App\Http\Middleware\CheckAdmin;
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

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('home')->group(function(){
    // Đăng nhập
    Route::post('check_ac', [OrderController::class, 'check_ac'])->name('check_ac');
    Route::get('test', [OrderController::class, 'test'])->name('test');
    Route::get('logout', [OrderController::class, 'logout'])->name('logout');
    Route::get('dangky_ac', [OrderController::class, 'dangky_ac'])->name('dangky_ac');
    Route::get('dang_nhap', [OrderController::class, 'dang_nhap'])->name('dang_nhap');
    Route::post('dangky_tc', [OrderController::class, 'dangky_tc'])->name('dangky_tc');
});
    

Route::prefix('home')->middleware([CheckUsers::class])->group(function(){
     
    //Đơn hàng
    // Route::get('/', [OrderController::class, 'bieudo'])->name('bieudo')->middleware([CheckAdmin::class]);
    Route::get('/', [OrderController::class, 'bieudo'])->name('bieudo');
    Route::get('home2', [OrderController::class, 'index'])->name('oder');
    Route::get('data', [OrderController::class, 'data'])->name('data');
    Route::get('danhmuc', [OrderController::class, 'danhmuc'])->name('danhmuc');
    Route::get('data_danhmuc', [OrderController::class, 'data_danhmuc'])->name('data_danhmuc');
    Route::get('create_account', [OrderController::class, 'create_account'])->name('create_account');
    Route::get('dangky', [OrderController::class, 'dangky'])->name('dangky');
    Route::post('tao_tk', [OrderController::class, 'tao_tk'])->name('tao_tk');
    Route::post('update_ac', [OrderController::class, 'update_ac'])->name('update_ac');
    Route::post('delete_ac', [OrderController::class, 'delete_ac'])->name('delete_ac');
    Route::post('delete_ac_gh', [OrderController::class, 'delete_ac_gh'])->name('delete_ac_gh');
    Route::post('delete_ac_kh', [OrderController::class, 'delete_ac_kh'])->name('delete_ac_kh');
    Route::post('delete_ac_admin', [OrderController::class, 'delete_ac_admin'])->name('delete_ac_admin');
    Route::post('chang_pass', [OrderController::class, 'chang_pass'])->name('chang_pass');
    Route::post('chang_pass_gh', [OrderController::class, 'chang_pass_gh'])->name('chang_pass_gh');
    Route::post('chang_pass_kh', [OrderController::class, 'chang_pass_kh'])->name('chang_pass_kh');
    Route::post('chang_pass_admin', [OrderController::class, 'chang_pass_admin'])->name('chang_pass_admin');
    Route::get('create_customer/{id?}', [OrderController::class, 'create_customer'])->name('create_customer');
    Route::post('them_kh', [OrderController::class, 'them_kh'])->name('them_kh');
    Route::post('delet_tt', [OrderController::class, 'delet_tt'])->name('delet_tt');
    Route::get('xe_congty', [OrderController::class, 'xe_congty'])->name('xe_congty');
    Route::get('add_xecongty', [OrderController::class, 'add_xecongty'])->name('add_xecongty');
    Route::get('add_car/{id?}', [OrderController::class, 'add_car'])->name('add_car');
    Route::post('create_car', [OrderController::class, 'create_car'])->name('create_car');
    Route::post('delete_xecty', [OrderController::class, 'delete_xecty'])->name('delete_xecty');
    Route::get('them_don', [OrderController::class, 'them_don'])->name('them_don');
    Route::get('list_donhang', [OrderController::class, 'list_donhang'])->name('list_donhang');
    Route::get('create_order', [OrderController::class, 'create_order'])->name('create_order');
    Route::post('create_donhang', [OrderController::class, 'create_donhang'])->name('create_donhang');
    
    //Khách hàng
    Route::get('don_hangcho', [OrderController::class, 'don_hangcho'])->name('don_hangcho');
    Route::get('inf_donhangcho', [OrderController::class, 'inf_donhangcho'])->name('inf_donhangcho');
    Route::get('don_hoanthanh', [OrderController::class, 'don_hoanthanh'])->name('don_hoanthanh');
    Route::get('show_don', [OrderController::class, 'show_don'])->name('show_don');
    Route::get('hoandon', [OrderController::class, 'hoandon'])->name('hoandon');
    Route::get('huydon', [OrderController::class, 'huydon'])->name('huydon');


    // Nhân viên khách hàng duyệt yêu cầu

    Route::get('yeucau', [OrderController::class, 'yeucau'])->name('yeucau');
    Route::get('yeucau_khachhang', [OrderController::class, 'yeucau_khachhang'])->name('yeucau_khachhang');
    Route::post('feedback', [OrderController::class, 'feedback'])->name('feedback');
    Route::post('deal', [OrderController::class, 'deal'])->name('deal');
    Route::post('update_status', [OrderController::class, 'update_status'])->name('update_status');
    Route::post('update_hoandon', [OrderController::class, 'update_hoandon'])->name('update_hoandon');
    Route::post('update_huydon', [OrderController::class, 'update_huydon'])->name('update_huydon');

    // Nhân viên giao hàng

    Route::get('vanchuyen/{id?}', [OrderController::class, 'vanchuyen'])->name('vanchuyen');
    Route::get('giaohang', [OrderController::class, 'giaohang'])->name('giaohang');
    Route::get('update_donhang', [OrderController::class, 'update_donhang'])->name('update_donhang');
    Route::get('request_yeucau', [OrderController::class, 'request_yeucau'])->name('request_yeucau');
    Route::get('nhanhang', [OrderController::class, 'nhanhang'])->name('nhanhang');
    Route::get('capnhatdonhang_hk', [OrderController::class, 'capnhatdonhang_hk'])->name('capnhatdonhang_hk');
    Route::post('nhanhangup', [OrderController::class, 'nhanhangup'])->name('nhanhangup');
    Route::post('huydon_kh', [OrderController::class, 'huydon_kh'])->name('huydon_kh');

    // admin

    Route::get('list_car', [OrderController::class, 'list_car'])->name('list_car');
    Route::get('show_car', [OrderController::class, 'show_car'])->name('show_car');
    Route::post('data_nvgh', [OrderController::class, 'data_nvgh'])->name('data_nvgh');
    Route::post('delete_car', [OrderController::class, 'delete_car'])->name('delete_car');
    Route::get('list_car_del', [OrderController::class, 'list_car_del'])->name('list_car_del');
    Route::get('list_car_delete', [OrderController::class, 'list_car_delete'])->name('list_car_delete');
    Route::post('khoi_phuc', [OrderController::class, 'khoi_phuc'])->name('khoi_phuc');
    
    Route::post('tai_khoan', [OrderController::class, 'tai_khoan'])->name('tai_khoan');

    Route::get('themtaikhoan', [OrderController::class, 'themtaikhoan'])->name('themtaikhoan');
    Route::post('themtaikhoanmoi', [OrderController::class, 'themtaikhoanmoi'])->name('themtaikhoanmoi');
    Route::get('edit_tk/{id?}', [OrderController::class, 'edit_tk'])->name('edit_tk');
    Route::get('edit_tk_gh/{id?}', [OrderController::class, 'edit_tk_gh'])->name('edit_tk_gh');
    Route::get('edit_tk_kh/{id?}', [OrderController::class, 'edit_tk_kh'])->name('edit_tk_kh');
    Route::get('edit_tk_admin/{id?}', [OrderController::class, 'edit_tk_admin'])->name('edit_tk_admin');
    Route::post('updatetaikhoanmoi', [OrderController::class, 'updatetaikhoanmoi'])->name('updatetaikhoanmoi');
    Route::post('updatetaikhoanmoi_gh', [OrderController::class, 'updatetaikhoanmoi_gh'])->name('updatetaikhoanmoi_gh');
    Route::post('updatetaikhoanmoi_kh', [OrderController::class, 'updatetaikhoanmoi_kh'])->name('updatetaikhoanmoi_kh');
    Route::post('updatetaikhoanmoi_admin', [OrderController::class, 'updatetaikhoanmoi_admin'])->name('updatetaikhoanmoi_admin');

    
    
   
   // Biểu đồ

    Route::post('thongtindonhang', [OrderController::class, 'thongtindonhang'])->name('thongtindonhang');
    Route::post('data_chart', [OrderController::class, 'data_chart'])->name('data_chart');
    Route::post('data_chart_if', [OrderController::class, 'data_chart_if'])->name('data_chart_if');
    Route::post('dulieuluachon', [OrderController::class, 'dulieuluachon'])->name('dulieuluachon');
    Route::post('tuychonthoigian', [OrderController::class, 'tuychonthoigian'])->name('tuychonthoigian');

    // menu

    Route::get('list_client', [OrderController::class, 'list_client'])->name('list_client');
    Route::get('list_client_gh', [OrderController::class, 'list_client_gh'])->name('list_client_gh');
    Route::get('list_client_kh', [OrderController::class, 'list_client_kh'])->name('list_client_kh');
    Route::get('list_client_admin', [OrderController::class, 'list_client_admin'])->name('list_client_admin');
    Route::get('list_nv_kp', [OrderController::class, 'list_nv_kp'])->name('list_nv_kp');
    Route::get('list_nv_kp_gh', [OrderController::class, 'list_nv_kp_gh'])->name('list_nv_kp_gh');
    Route::get('list_nv_kp_kh', [OrderController::class, 'list_nv_kp_kh'])->name('list_nv_kp_kh');
    Route::get('list_nv_kp_admin', [OrderController::class, 'list_nv_kp_admin'])->name('list_nv_kp_admin');
    Route::get('khachhang_s', [OrderController::class, 'khachhang_s'])->name('khachhang_s');
    Route::post('khoi_phuc_nhanvien', [OrderController::class, 'khoi_phuc_nhanvien'])->name('khoi_phuc_nhanvien');
    Route::post('khoi_phuc_nhanvien_kh', [OrderController::class, 'khoi_phuc_nhanvien_kh'])->name('khoi_phuc_nhanvien_kh');
    Route::post('khoi_phuc_nhanvien_admin', [OrderController::class, 'khoi_phuc_nhanvien_admin'])->name('khoi_phuc_nhanvien_admin');
    Route::post('khoi_phuc_nhanvien_gh', [OrderController::class, 'khoi_phuc_nhanvien_gh'])->name('khoi_phuc_nhanvien_gh');
    Route::get('tt_khachhang', [OrderController::class, 'tt_khachhang'])->name('tt_khachhang');

    //nv
    Route::get('nvgh', [OrderController::class, 'nvgh'])->name('nvgh');
    Route::get('tt_nvgh', [OrderController::class, 'tt_nvgh'])->name('tt_nvgh');
    Route::get('khachhang', [OrderController::class, 'khachhang'])->name('khachhang');
    Route::get('ac_admin', [OrderController::class, 'ac_admin'])->name('ac_admin');
    Route::get('tt_admin', [OrderController::class, 'tt_admin'])->name('tt_admin');
    Route::post('name_ac', [OrderController::class, 'name_ac'])->name('name_ac');
    Route::get('tt_khachhang_ac', [OrderController::class, 'tt_khachhang_ac'])->name('tt_khachhang_ac');
    Route::get('excel_donhoanthanh', [OrderController::class, 'excel_donhoanthanh'])->name('excel_donhoanthanh');
    Route::get('excel_donhoan', [OrderController::class, 'excel_donhoan'])->name('excel_donhoan');
    Route::get('excel_donhuy', [OrderController::class, 'excel_donhuy'])->name('excel_donhuy');

    //Xe
    Route::get('excel_xect', [OrderController::class, 'excel_xect'])->name('excel_xect');

    Route::get('edit_donhang/{id?}', [OrderController::class, 'edit_donhang'])->name('edit_donhang');
    Route::post('updatedonhangcn', [OrderController::class, 'updatedonhangcn'])->name('updatedonhangcn');
   

});
    
    
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

