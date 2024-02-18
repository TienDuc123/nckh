<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
class XectExport implements FromCollection, WithHeadings
{
    public function collection()
    {
    	$getStudent = [];
        $svs = DB::table('xe_congty')->get();
        		// ->leftjoin('users','users.id','nh_khachhang.nh_id_user')
        		// ->leftjoin('khach_hang','khach_hang.kh_id','users.id_nhanvien')->get();
        foreach($svs as $sv){
            
            $row = [
                $sv->xct_ten_xe,
            	$sv->xct_biensoxe,
                $sv->xct_loai_xe,
            	$sv->xct_sodangkiem,
                $sv->xct_handangkiem,
                $sv->xct_dinhmuc,
                $sv->xct_thongsokythuat,
                $sv->created_at,
            ];
            array_push($getStudent, $row);
        }
        return collect($getStudent);
    }

     public function headings() :array {
        return [
            "Tên xe",
            "Biển số xe",
            "Loại xe",
            "Số đăng kiểm",
            "Hạn đăng kiểm",
            "Định mức",
            "Thông số kỹ thuật",
            "Ngày sử dụng",
        ];
    }
}


?>