<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use DB;
class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
    	$getStudent = [];
        if (Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            $svs = DB::table('nh_khachhang')
        		->leftjoin('users','users.id','=','nh_khachhang.nh_id_user')
        		->leftjoin('khach_hang','khach_hang.kh_id','=','users.id_nhanvien')
                ->where('nh_trangthai','=','thành công')
                ->get();
        }
        else if(Auth::user()->permissions == 1 ){
            $svs = DB::table('nh_khachhang')
            ->leftjoin('users','users.id','=','nh_khachhang.nh_id_user')
            ->leftjoin('khach_hang','khach_hang.kh_id','=','users.id_nhanvien')
            ->where('nh_trangthai','=','thành công')
            ->where('nh_id_user','=',Auth::user()->id)
            ->get();
        }
        else{
            $svs = DB::table('nh_khachhang')
            ->leftjoin('users','users.id','=','nh_khachhang.nh_id_user')
            ->leftjoin('khach_hang','khach_hang.kh_id','=','users.id_nhanvien')
            ->where('nh_trangthai','=','thành công')
            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
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
                $sv->nh_tienthuho,
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
            "Tiền nhận hộ",
            "Ngày vận chuyển",
            "Ngày hoàn thành",
        ];
    }
}


?>