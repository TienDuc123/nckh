<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Illuminate\Support\Facades\Auth;
class DonhoanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
    	$getStudent = [];
        if (Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            $svs = DB::table('nh_khachhang')
            ->leftJoin('users', 'users.id', '=', 'nh_khachhang.nh_id_user')
            ->leftJoin('khach_hang', 'khach_hang.kh_id', '=', 'users.id_nhanvien')
            ->where(function($query) {
                $query->where('nh_trangthai', 'từ chối nhận')
                    ->where('nh_id_user', '=', Auth::user()->id);
            })
            ->orWhere(function($query) {
                $query->where('nh_trangthai', 'không liên hệ được')
                    ->where('nh_id_user', '=', Auth::user()->id);
            })
            ->get(); 
        }
        else if(Auth::user()->permissions == 1 ){
            $svs = DB::table('nh_khachhang')
            ->leftJoin('users', 'users.id', '=', 'nh_khachhang.nh_id_user')
            ->leftJoin('khach_hang', 'khach_hang.kh_id', '=', 'users.id_nhanvien')
            ->where(function($query) {
                $query->where('nh_trangthai', 'từ chối nhận')
                    ->where('nh_id_user', '=', Auth::user()->id);
            })
            ->orWhere(function($query) {
                $query->where('nh_trangthai', 'không liên hệ được')
                    ->where('nh_id_user', '=', Auth::user()->id);
            })
            ->get();  
        }
        else{
            $svs = DB::table('nh_khachhang')
            ->leftJoin('users', 'users.id', '=', 'nh_khachhang.nh_id_user')
            ->leftJoin('khach_hang', 'khach_hang.kh_id', '=', 'users.id_nhanvien')
            ->where(function($query) {
                $query->where('nh_trangthai', 'từ chối nhận')
                    ->where('nh_id_user', '=', Auth::user()->id);
            })
            ->orWhere(function($query) {
                $query->where('nh_trangthai', 'không liên hệ được')
                    ->where('nh_id_user', '=', Auth::user()->id);
            })
            ->get(); 
        }
           
        foreach($svs as $sv){
            
            $row = [
            	$sv->kh_ten,
            	$sv->email,
                $sv->nh_masanpham,
                $sv->nh_tenkhachhang,
                $sv->nh_SĐT,
                $sv->nh_diachi,
                $sv->nh_noilayhang,
                $sv->nh_tenhanghoa,
                $sv->nh_khoiluong,
                $sv->nh_soluong,
                $sv->nh_phiship,
                $sv->nh_ghichu,
                $sv->nh_phanhoi,
                $sv->nh_created_at,
                $sv->nh_updated_at,
            ];
            array_push($getStudent, $row);
        }
        return collect($getStudent);
    }

     public function headings() :array {
        return [
            "Tên Khách hàng",
            "Số điện thoại khách hàng",
            "Mã sản phẩm",
            "Tên người nhận",
            "Số điện thoại người nhận",
            "Địa chỉ giao hàng",
            "Địa chỉ nhận hàng",
            "Tên hàng hóa",
            "Khối lượng",
            "Số lượng",
            "Phí vận chuyển",
            "Ghi chú",
            "Phản hồi",
            "Ngày vận chuyển",
            "Ngày hoàn đơn",
        ];
    }
}


?>