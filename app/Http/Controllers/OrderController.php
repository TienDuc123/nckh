<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Yajra\Datatables\DatatablesServiceProvider;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\DonhoanExport;
use App\Exports\DonhuyExport;
use App\Exports\XectExport;

class OrderController extends Controller
{
    public function index(){
        return view('admin.home');
    }
    public function data(Request $req){
        $data = DB::table('donhang');
        return DataTables::of($data)
            ->addColumn('donhang', function($dt){
                return $dt->donhang;
            })
            ->addColumn('ten_xe', function($dt){
                $name_car = DB::table('nha_xe')
                                ->select('nx_ten_nha_xe')
                                ->where('nx_id',$dt->dh_id_nha_xe)
                                ->first();
                if($name_car){
                    return $name_car->ten_nha_xe;
                }else{
                    return 'Không có thông tin';
                }
            })
            ->addColumn('xe_congty', function($dt){
                $name_car_ct = DB::table('xe_congty')
                                ->select('xct_biensoxe')
                                ->where('xct_id',$dt->dh_id_xecongty)
                                ->first();
                if($name_car_ct){
                    return $name_car_ct->xct_biensoxe;
                }else{
                    return 'Không có thông tin';
                }
            })
            ->addColumn('khach_hang', function($dt){
                $KH = DB::table('khach_hang')
                            ->select('kh_ten')
                            ->where('kh_id',$dt->id_khach_hang)
                            ->first();
                if($KH){
                    return $KH->kh_ten;
                }else{
                    return 'Không có thông tin';
                }
            })
            ->addColumn('nha_xe', function($dt){
                $NX = DB::table('nha_xe')
                        ->select('nx_ten_nha_xe')
                        ->where('nx_id',$dt->dh_id_nha_xe)
                        ->first();
                if($NX){
                    return $NX->nx_ten_nha_xe;
                }else{
                    return 'Không có thông tin';
                }
            })
            ->addColumn('action', function($dt){
                $action = '<a href="">Xóa</a>'.'</br>'.'<a href="">Chỉnh sửa</a>';
                return $action;
            })
                ->make(true);
    }

    public function danhmuc(Request $req){
        return view('admin.danhmuc');
    }

    public function data_danhmuc(Request $req){
        $data = DB::table('khach_hang')
                    ->whereNotNull('deleted_at');
        return DataTables::of($data)
                ->addColumn('action', function($dt){
                    $action = '<button class="delete_inf" dt-id="'.$dt->kh_id.'">Xóa</button>'.'</br>'.'<a href="'.route('create_customer',$dt->kh_id).'" class="edit_inf">Chỉnh sửa</a>';
                    return $action;
                })
                ->make(true);
    }

    public function create_account(){

        return view('admin.dangky');
    }

    public function dangky(){
        $data = DB::table('nhan_vien')
                    ->where('deleted_at',null)
                    ->orderBy('created_at','desc');
        return DataTables::of($data)

                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->id)
                                ->where('permissions',2)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })
                ->addColumn('loainv',function($dt){
                    $loainv = DB::table('users')
                                ->select('permissions')
                                ->where('id_nhanvien',$dt->id)
                                ->first();
                    if(isset($loainv->permissions)){
                        if ($loainv->permissions == 2) {
                            return "NVTDKH";
                        }else{
                            return 'Không thấy dữ liệu';
                        }
                    }else{
                        return 'Không thấy dữ liệu';
                    }
                    
                })

                ->addColumn('anhcmt',function($dt){
                    $nhanvien = '<a href="'.$dt->anh_cmt.'" target="_blank"><img src="'.$dt->anh_cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    
                })
                ->addColumn('actions',function($dt){
                    $actions = '<a class="upadate_ac" href="'.route('edit_tk',$dt->id).'" d-id="'.$dt->id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-pen-to-square"></i></a>'.'</br>'.'<button class="upadate_pass" d-id="'.$dt->id.'"data-bs-placement="top" title="Đổi mật khẩu"><i class="fa-solid fa-lock"></i></button>'.'</br>'.'<button class="delete_ac" d-id="'.$dt->id.'"><i class="fa-solid fa-trash-can"data-bs-placement="top" title="Khóa tài khoản"></i></button>';
                    return $actions;
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function tao_tk(Request $req){
        if($req->select == 1){
            
            $save_loainv = 1;
            if($req->loai_nv == 'ADMIN'){
                $save_nv = DB::table('admin')
                        ->insertGetid([
                            'ten_admin' => $req->name_nv,
                            'ma_admin' => $req->ma_nv,
                            'ngay_sinh' => $req->ngay_s,
                            'gioi_tinh' => $req->gender,
                            'dia_chi' => $req->address,
                            'cmt' => $req->cmt,
                            // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            
                        ]);
                $save_user = DB::table('users')
                                ->insert([
                                    'email' => $req->account,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save_nv,
                                    'permissions' => 4,
                                ]);
            }else if($req->loai_nv == 'NVTDKH'){

                $save_nv = DB::table('nhan_vien')
                        ->insertGetid([
                            'ten_nhanvien' => $req->name_nv,
                            'ma_nhanvien' => $req->ma_nv,
                            'ngay_sinh' => $req->ngay_s,
                            'gioi_tinh' => $req->gender,
                            'dia_chi' => $req->address,
                            'anh_cmt' => $req->cmt,
                            // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            
                        ]);
                $save_user = DB::table('users')
                                ->insert([
                                    'email' => $req->account,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save_nv,
                                    'permissions' => 2,
                                ]);
            }else if($req->loai_nv == 'NVGH'){
                $save_nv = DB::table('nhanvien_giaohang')
                        ->insertGetid([
                            'gh_tennvgh' => $req->name_nv,
                            'gh_manvgh' => $req->ma_nv,
                            'gh_ngaysinh' => $req->ngay_s,
                            'gh_gioitinh' => $req->gender,
                            'gh_diachigh' => $req->address,
                            'gh_cmt' => $req->cmt,
                            // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            
                        ]);
                $save_user = DB::table('users')
                                ->insert([
                                    'email' => $req->account,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save_nv,
                                    'permissions' => 3,
                                ]);
                }else{
                    $save_nv = DB::table('khach_hang')
                        ->insertGetid([
                            'kh_ten' => $req->name_nv,
                            'kh_Address' => $req->address,
                            // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'kh_created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            
                        ]);
                $save_user = DB::table('users')
                                ->insert([
                                    'email' => $req->account,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save_nv,
                                    'permissions' => 1,
                                ]);
                }
            
        }elseif($req->select == 2){
            // $save_loainv = DB::table('loai_nhanvien')
            //             ->where('id',$req->id_loainhanvien)
            //             ->update([
            //                 'tenloainhanvien' => $req->loai_nv,
            //             ]);
            $save_nv = DB::table('nhan_vien')
                        ->where('id',$req->id_nhanviens)
                        ->update([
                            'ten_nhanvien' => $req->name_nv,
                            'ngay_sinh' => $req->ngay_s,
                            'gioi_tinh' => $req->gender,
                            'dia_chi' => $req->address,
                            'sdt' => $req->phone_number,
                            
                        ]);
            $save_user = DB::table('users')
                            ->where('id_nhanvien',$req->id_nhanviens)
                            ->update([
                                'username' => $req->account,
                                'passwork' => $req->passwork,
                            ]);
        }
        
        return view('admin.dangky');
    }

    public function update_ac(Request $req){
        $data = DB::table('nhan_vien')
                    ->select('nhan_vien.*','user.username','user.passwork','loai_nhanvien.tenloainhanvien','loai_nhanvien.id as id_loainhanvien')
                    ->leftjoin('user','user.id_nhanvien','=','nhan_vien.id')
                    ->leftjoin('loai_nhanvien','loai_nhanvien.id','=','nhan_vien.id_loainhanvien')
                    ->where('nhan_vien.id',$req->id_nhanvien)
                    ->first();
        return $data;
    }

    public function delete_ac(Request $req){

        $save = DB::table('nhan_vien')
                    ->where('id',$req->id_nhanvien)
                    ->update([
                        'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);

        $user = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',2)
                    ->first();
        $khoa_ac = DB::table('khoa_ac')
                    ->where('id_user',$user->id)
                    ->update([
                        "khoa_acxz" => 0,
                        "ghi_chu" => $req->note,
                    ]);
    }

    public function delete_ac_gh(Request $req){

        $save = DB::table('nhanvien_giaohang')
                    ->where('gh_id',$req->id_nhanvien)
                    ->update([
                        'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);

        $user = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',3)
                    ->first();
        $khoa_ac = DB::table('khoa_ac')
                    ->where('id_user',$user->id)
                    ->update([
                        "khoa_acxz" => 0,
                        "ghi_chu" => $req->note,
                    ]);
    }

    public function delete_ac_kh(Request $req){

        $save = DB::table('khach_hang')
                    ->where('kh_id',$req->id_nhanvien)
                    ->update([
                        'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);

        $user = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',1)
                    ->first();
        $khoa_ac = DB::table('khoa_ac')
                    ->where('id_user',$user->id)
                    ->update([
                        "khoa_acxz" => 0,
                        "ghi_chu" => $req->note,
                    ]);
    }

    public function delete_ac_admin(Request $req){

        $save = DB::table('admin')
                    ->where('id',$req->id_nhanvien)
                    ->update([
                        'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);

        $user = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',4)
                    ->first();
        $khoa_ac = DB::table('khoa_ac')
                    ->where('id_user',$user->id)
                    ->update([
                        "khoa_acxz" => 0,
                        "ghi_chu" => $req->note,
                    ]);
    }


    public function chang_pass(Request $req){
        if($req->pass != $req->pass2){
            return 0;
        }else{
            $save = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',2)
                    ->update([
                        'password' => Hash::make($req->pass),
                    ]);
            return 1;
        }
        
    }

    public function chang_pass_gh(Request $req){
        if($req->pass != $req->pass2){
            return 0;
        }else{
            $save = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',3)
                    ->update([
                        'password' => Hash::make($req->pass),
                    ]);
            return 1;
        }
        
    }

    public function chang_pass_kh(Request $req){
        if($req->pass != $req->pass2){
            return 0;
        }else{
            $save = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',1)
                    ->update([
                        'password' => Hash::make($req->pass),
                    ]);
            return 1;
        }
        
    }

    public function chang_pass_admin(Request $req){
        if($req->pass != $req->pass2){
            return 0;
        }else{
            $save = DB::table('users')
                    ->where('id_nhanvien',$req->id_nhanvien)
                    ->where('permissions',4)
                    ->update([
                        'password' => Hash::make($req->pass),
                    ]);
            return 1;
        }
        
    }

    public function create_customer(Request $req){
            $khach_hang = DB::table('khach_hang')
                            ->where('kh_id',$req->id)
                            ->first();

        return view('admin.them_khach_hang')
                        ->with([
                            'khach_hang' => $khach_hang,
                        ]);
    }

    public function them_kh(Request $req){
        if ($req->loai == 'Công ty') {
            $name = 'CTY.';
        }else{
            $name = 'CN.';
        }

        if($req->acction == 'create_kh'){
            $khach_hang = DB::table('khach_hang')
                        ->insert([
                            'kh_ma_dinhdanh' => $name.$req->sdt,
                            'kh_loai' => $req->loai,
                            'kh_ten' => $req->ten_kh,
                            'kh_ten_viettat' => $req->ten_vt,
                            'kh_Address' => $req->dia_chi,
                            'kh_zalo' => $req->zalo,
                            'kh_loaitinnhan' => $req->loai_tn,
                            'kh_sdt' => $req->sdt,
                            'kh_mail' => $req->mail,
                            'kh_sodangky_kd' => $req->sdkkd,
                            'kh_sodu_dinhky' => $req->sodudk,
                            'kh_pt_thanhtoan' => $req->pttt,
                            'kh_hanmuc_ttso' => $req->hmtt,
                            'kh_trang_thai' => $req->trang_thai,
                            'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
        }else if($req->acction == 'update_kh'){
            $khach_hang = DB::table('khach_hang')
                            ->where('kh_id',$req->id_kh)
                            ->update([
                                'kh_ma_dinhdanh' => $name.$req->sdt,
                                'kh_loai' => $req->loai,
                                'kh_ten' => $req->ten_kh,
                                'kh_ten_viettat' => $req->ten_vt,
                                'kh_Address' => $req->dia_chi,
                                'kh_zalo' => $req->zalo,
                                'kh_loaitinnhan' => $req->loai_tn,
                                'kh_sdt' => $req->sdt,
                                'kh_mail' => $req->mail,
                                'kh_sodangky_kd' => $req->sdkkd,
                                'kh_sodu_dinhky' => $req->sodudk,
                                'kh_pt_thanhtoan' => $req->pttt,
                                'kh_hanmuc_ttso' => $req->hmtt,
                                'kh_trang_thai' => $req->trang_thai,
                            ]);
        }
        
        // return view('admin.danhmuc')->with('success','Thêm mới thành công');

        return redirect()->route('danhmuc');
    }

    public function delet_tt(Request $req){

         $data = DB::table('khach_hang')
                    ->where('kh_id',$req->id_kh)
                    ->update([
                        'deleted_at' => null,
                    ]);

        return 1;
    }

    public function xe_congty(){

        return view('admin.xe_congty');
    }

    public function add_xecongty(){
        $data = DB::table('xe_congty')
                    ->leftjoin('nhanvien_giaohang','xe_congty.xct_id','=','nhanvien_giaohang.gh_id')
                    ->whereNotNull('xct_deleted_at');
       return DataTables::of($data)
                ->addColumn('action', function($dt){
                    $action = '<button class="delete_inf" dt-id="'.$dt->xct_id.'">Xóa</button>'.'</br>'.'<a href="'.route('add_car',$dt->xct_id).'" class="edit_inf">Chỉnh sửa</a>';

                    return $action;
                })  
                ->make(true);    
    }


    public function add_car(Request $req){

        $xe_congty = DB::table('xe_congty')
                        ->leftjoin('nhanvien_giaohang','nhanvien_giaohang.gh_id','=','xe_congty.id_nvgh')
                        ->where('xct_id',$req->id)
                        ->first();
             
        $nhanvienlx = DB::table('nhanvien_giaohang')
                        ->where('deleted_at',null)
                        ->get();
        // var_dump($nhanvienlx);die;
        return view('admin.them_xe')
                        ->with([
                            'khach_hang' => $xe_congty,
                            'nhanvienlx' => $nhanvienlx,
                        ]);
    }

    public function create_car(Request $req){

        if(isset($_FILES['anhminhhoa'])){
            $tenxe = $req->tenxe;
            //Thư mục bạn lưu file upload
            $randomString = bin2hex(random_bytes(5));
            $target_dir = "uploads/";
            //Đường dẫn lưu file trên server
            $target_file   = $target_dir .$randomString. basename($_FILES["anhminhhoa"]["name"]);                
            $allowUpload   = true;

            //Lấy phần mở rộng của file (txt, jpg, png,...)
            $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
            //Những loại file được phép upload
            $allowtypes    = array('txt', 'dat', 'data');
            //Kích thước file lớn nhất được upload (bytes)
            $maxfilesize   = 10000000;//10MB

            //1. Kiểm tra file có bị lỗi không?
            if ($_FILES["anhminhhoa"]['error'] != 0) {
                $update = DB::table('xe_congty')
                                    ->where('xct_id',$req->id_kh)
                                    ->update([
                                        'xct_ten_xe' => $tenxe,
                                        'xct_biensoxe' => $req->biensoxe,
                                        'xct_loai_xe' => $req->loaixe,
                                        'xct_sodangkiem' => $req->sodangkiem,
                                        'xct_handangkiem' => $req->handangkiem,
                                        'xct_dinhmuc' => $req->dinhmuc,
                                        'xct_thongsokythuat' => $req->thongsokythuat,
                                        'id_nvgh' => $req->id_gh,

                                    ]);
                return redirect()->route('list_car');
            }

            //2. Kiểm tra loại file upload có được phép không?
            // if (!in_array($fileType, $allowtypes )) {
            //     echo "<br>Only allow for uploading .txt, .dat or .data files.";
            //     $allowUpload = false;
            // }
            
            //3. Kiểm tra kích thước file upload có vượt quá giới hạn cho phép
            if ($_FILES["anhminhhoa"]["size"] > $maxfilesize) {
                echo "<br>Size of the uploaded file must be smaller than $maxfilesize bytes.";
                $allowUpload = false;
            }

            //4. Kiểm tra file đã tồn tại trên server chưa?
            // if (file_exists($target_file)) {
            //     echo "<br>The file name already exists on the server.";
            //     $allowUpload = false;
            // }

            if ($allowUpload) {

                
                //Lưu file vào thư mục được chỉ định trên server
                if (move_uploaded_file($_FILES["anhminhhoa"]["tmp_name"], $target_file)) {
                   
                
                    $path = URL::to('/') . '/' . $target_file;

                    $data = [
                                'xct_ten_xe' => $tenxe,
                                'xct_biensoxe' => $req->biensoxe,
                                'xct_loai_xe' => $req->loaixe,
                                'xct_sodangkiem' => $req->sodangkiem,
                                'xct_handangkiem' => $req->handangkiem,
                                'xct_dinhmuc' => $req->dinhmuc,
                                'xct_thongsokythuat' => $req->thongsokythuat,
                                'xct_anh' => $path,
                                'id_nvgh' => $req->id_gh,
                                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                // 'xct_deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            ];
                    
                    if($req->acction == 'create_kh'){
                        $create = DB::table('xe_congty')
                                    ->insertGetid($data);

                       
                        return redirect()->route('list_car');
                        
                    }else{
                        $update = DB::table('xe_congty')
                                    ->where('xct_id',$req->id_kh)
                                    ->update($data);
                        

                        return redirect()->route('list_car');
                    }


                } 
            }
        }

        
        

        return 1;

    }

    public function delete_xecty(Request $req){

        $del = DB::table('xe_congty')
                ->where('xct_id',$req->id_xecty)
                ->update([
                    'xct_deleted_at' => null,
                ]);

        return 1;
    }

    public function them_don(){

        return view('admin.them_don');
    }

    public function list_donhang(Request $req){

        $data = DB::table('nh_khachhang');

        return DataTables::of($data)
                ->addColumn('action',function($dt){
                     $action = '<button class="delete_inf" dt-id="'.$dt->nh_id.'">Xóa</button>'.'</br>'.'<a href="'.route('add_car',$dt->nh_id).'" class="edit_inf">Chỉnh sửa</a>';
                     return $action;
                })
                    ->make(true);

    }

    public function create_order(){

        return view('admin.create_order');
    }

    public function create_donhang(Request $req){
           
            // $image = $req->file('anhminhhoa');
            // $image->store('public/css/img');

            if(isset($_FILES['anhminhhoa'])){
                $randomString = bin2hex(random_bytes(5));
                //Thư mục bạn lưu file upload
                $target_dir = "uploads/";
                //Đường dẫn lưu file trên server
                $target_file   = $target_dir .$randomString. basename($_FILES["anhminhhoa"]["name"]);                
                $allowUpload   = true;

                //Lấy phần mở rộng của file (txt, jpg, png,...)
                $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
                //Những loại file được phép upload
                $allowtypes    = array('txt', 'dat', 'data');
                //Kích thước file lớn nhất được upload (bytes)
                $maxfilesize   = 10000000;//10MB

                //1. Kiểm tra file có bị lỗi không?
                if ($_FILES["anhminhhoa"]['error'] != 0) {
                    echo "<br>The uploaded file is error or no file selected.";
                    die;
                }

                //2. Kiểm tra loại file upload có được phép không?
                // if (!in_array($fileType, $allowtypes )) {
                //     echo "<br>Only allow for uploading .txt, .dat or .data files.";
                //     $allowUpload = false;
                // }
                
                //3. Kiểm tra kích thước file upload có vượt quá giới hạn cho phép
                if ($_FILES["anhminhhoa"]["size"] > $maxfilesize) {
                    echo "<br>Size of the uploaded file must be smaller than $maxfilesize bytes.";
                    $allowUpload = false;
                }

                //4. Kiểm tra file đã tồn tại trên server chưa?
                // if (file_exists($target_file)) {
                //     echo "<br>The file name already exists on the server.";
                //     $allowUpload = false;
                // }

                if ($allowUpload) {
                    $tenkhach = $req->tenkhach;
                    //Lưu file vào thư mục được chỉ định trên server
                    if (move_uploaded_file($_FILES["anhminhhoa"]["tmp_name"], $target_file)) {
                        
                        // echo "<br>File ". basename( $_FILES["anhminhhoa"]["name"])." uploaded successfully.";
                        // echo "The file saved at " . $target_file;
                        $path = URL::to('/') . '/' . $target_file;
                        
                        $productId = uniqid();
                        $chech_trung  = DB::table('nh_khachhang')->get();
                        if($chech_trung->contains($productId)){
                            $productId = uniqid();
                        }

                        $data = [
                                    'nh_masanpham' => strtoupper($productId),
                                    'nh_tenkhachhang' => $tenkhach,
                                    'nh_SĐT' => $req->sdt,
                                    'nh_noilayhang' => $req->noilayhang,
                                    'nh_diachi' => $req->diachi,
                                    'nh_tenhanghoa' => $req->tenhanghoa,
                                    'nh_khoiluong' => $req->khoiluong,
                                    'nh_soluong' => $req->soluong,
                                    'nh_ghichu' => $req->ghichu,
                                    'nh_phiship' => $req->phiship,
                                    'nh_tienthuho' => $req->money_thuho,
                                    'nh_trangthai' => 'chờ',
                                    'nh_anhminhhoa' => $path,
                                    'nh_id_khachhang' => Auth::user()->id_nhanvien,
                                    'nh_id_user' => Auth::user()->id,
                                    // 'nh_deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'nh_created_at' => Carbon::now('Asia/Ho_Chi_Minh'),

                                    // 'nh_id_khachhang' => $req->phiship,
                                ];


                        $save = DB::table('nh_khachhang')
                                ->insert($data);


                    } 
                }
            }


            
            
        
        return redirect()->route('don_hangcho');
    }

    public function yeucau(Request $req){

        return view('admin.yeucau');
    }

    public function yeucau_khachhang(Request $req){
        
        
                $data = DB::table('nh_khachhang')
                        ->where('check',null)
                        // ->where('nh_id_user',Auth::user()->id)
                        ->where('nh_deleted_at',null)
                        ->orderBy('nh_created_at','desc');
                return DataTables::of($data)
                    ->addColumn('name_kh', function($dt){
                        $name = DB::table('khach_hang')
                                    ->select('kh_ten')
                                    ->where('kh_id',$dt->nh_id_khachhang)
                                    ->first();
                        if($name){
                            return $name->kh_ten;
                        }else{
                            return '';
                        }
                    })
                    ->addColumn('anhminhhoa',function($dt){
                            if($dt->nh_anhminhhoa != ''){
                                return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                            }else{
                                return "";
                            }
                    })
                    ->addColumn('sdt_kh', function($dt){
                        $phone = DB::table('users')
                                    ->select('email')
                                    ->where('id',$dt->nh_id_user)
                                    ->first();
                        if($phone){
                            return $phone->email;
                        }else{
                            return '';
                        }
                    })
                    ->addColumn('yeucau', function($dt){
                        $action = '<a href="'.route('vanchuyen',$dt->nh_id).'" class="edit_inf" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-cloud-arrow-up" data-bs-placement="top" title="Chọn xe"></i></a>'.'</br>'.'<button class="delete_inf" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-trash-can" data-bs-placement="top" title="Hủy đơn hàng"></i></button>';
                        return $action;
                    })
                    ->addColumn('ten_khach_h', function($dt){
                        $name_kh = DB::table('khach_hang')
                                    ->select('kh_ten')
                                    ->where('kh_id',$dt->nh_id_khachhang)
                                    ->first();
                        if($name_kh){
                            return $name_kh->kh_ten;
                        }else{
                            return '';
                        }
                })
                    ->rawColumns(['yeucau','anhminhhoa'])
                    ->make(true);
        
        
    }

    public function don_hangcho(){
        
        return view('admin.don_hangcho');
    }

    public function inf_donhangcho(Request $req){
        if(Auth::user()->permissions == 1){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','!=','thành công')
                    ->where('nh_trangthai','!=','xóa')
                    ->where('nh_trangthai','!=','khác')
                    ->where('nh_trangthai','!=','từ chối nhận')
                    ->where('nh_trangthai','!=','không liên hệ được')
                    ->where('nh_id_user','=',Auth::user()->id)
                    ->orderBy('nh_created_at', 'desc');

            return DataTables::of($data)

                    ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })

                    ->addColumn('trang_thai',function($dt){
                        if($dt->nh_trangthai == 'chờ'){
                            return 'Đang xét duyệt';
                        }else if($dt->nh_trangthai == 'duyệt'){
                            return 'Đang vận chuyển';
                        }else if($dt->nh_trangthai == 'xóa'){
                            return 'Đơn hàng không được nhận';
                        }
                    })
                    ->rawColumns(['anhminhhoa'])
                    ->make(true);
        }else if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            $data = DB::table('nh_khachhang')
            ->where('nh_trangthai','!=','thành công')
            ->where('nh_trangthai','!=','xóa')
            ->where('nh_trangthai','!=','khác')
            ->where('nh_trangthai','!=','từ chối nhận')
            ->where('nh_trangthai','!=','không liên hệ được')
            ->orderBy('nh_created_at', 'desc');

            return DataTables::of($data)
                ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })

                ->addColumn('trang_thai',function($dt){
                    if($dt->nh_trangthai == 'chờ'){
                        return 'Đang xét duyệt';
                    }else if($dt->nh_trangthai == 'duyệt'){
                        return 'Đang vận chuyển';
                    }else if($dt->nh_trangthai == 'xóa'){
                        return 'Đơn hàng không được nhận';
                    }
                })
                ->rawColumns(['anhminhhoa'])


                ->make(true);
        }else if(Auth::user()->permissions == 3){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','!=','thành công')
                    ->where('nh_trangthai','!=','xóa')
                    ->where('nh_trangthai','!=','khác')
                    ->where('nh_trangthai','!=','từ chối nhận')
                    ->where('nh_trangthai','!=','không liên hệ được')
                    ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                    ->orderBy('nh_created_at', 'desc');

            return DataTables::of($data)

                    ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })

                    ->addColumn('trang_thai',function($dt){
                        if($dt->nh_trangthai == 'chờ'){
                            return 'Đang xét duyệt';
                        }else if($dt->nh_trangthai == 'duyệt'){
                            return 'Đang vận chuyển';
                        }else if($dt->nh_trangthai == 'xóa'){
                            return 'Đơn hàng không được nhận';
                        }
                    })
                    ->rawColumns(['anhminhhoa'])
                    ->make(true);
        }
        
    }

    public function feedback(Request $req){
            $update = DB::table('nh_khachhang')
                            ->where('nh_id',$req->id_kh)
                            ->update([
                                    'nh_phanhoi' => $req->phanhoi,
                                    'nh_trangthai' => 'xóa',
                                    'nh_deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            ]);
        return 1;
    }


    public function vanchuyen(Request $req){

        return view('admin.vanchuyen')->with([ 'id_nh' => $req->id]);
    }

    public function giaohang(){
        
        $data = DB::table('xe_congty');

        return DataTables::of($data)
            ->addColumn('tenlaixe',function($dt){
                $name = DB::table('nhanvien_giaohang')
                            ->select('gh_tennvgh')
                            ->where('gh_id',$dt->id_nvgh)
                            ->first();
                if ($name) {
                    return $name->gh_tennvgh;
                }else{
                    return '';
                }
            })

            ->addColumn('manhanvien',function($dt){
                $manv = DB::table('nhanvien_giaohang')
                            ->select('gh_manvgh')
                            ->where('gh_id',$dt->id_nvgh)
                            ->first();
                if ($manv) {
                    return $manv->gh_manvgh;
                }else{
                    return '';
                }
            })

            ->addColumn('sdt',function($dt){
                $names = DB::table('nhanvien_giaohang')
                            ->select('gh_id')
                            ->where('gh_id',$dt->id_nvgh)
                            ->first();
                if($names){
                    $phone = DB::table('users')
                            ->select('email')
                            ->where('id_nhanvien',$names->gh_id)
                            ->first();
                }
                
                if($phone){
                    return $phone->email;
                }else{
                    return '';
                }
            })

            ->addColumn('gender',function($dt){
                $gender = DB::table('nhanvien_giaohang')
                            ->select('gh_gioitinh')
                            ->where('gh_id_xecongty',$dt->xct_id)
                            ->first();
                if($gender){
                    return $gender->gh_gioitinh;
                }else{
                    return '';
                }
            })

            ->addColumn('trang_thai', function($dt){
                $check = false;
                $trangthai = DB::table('nh_khachhang')
                                ->where('id_nhanviengh',$dt->id_nvgh)
                                ->get();
                foreach($trangthai as $val){
                    if($val->nh_trangthai == 'duyệt'){
                        $check = true;
                    }
                }
               
                    if($check){
                        return 'Đang vận chuyển';
                    }else{
                        return 'Xe chống';
                    }
               
                
            })

            ->addColumn('dia_diem',function($dt){
                $diadiem = DB::table('nhanvien_giaohang')
                            ->select('gh_diachigh')
                            ->where('gh_id_xecongty',$dt->xct_id)
                            ->first();
                $action = '<button style="border: none; outline: none; background: none;" onclick = "show_map()"><i class="fa-solid fa-eye" data-bs-placement="top" title="Xem vị trí" dt-id="'.$dt->xct_id.'"></i>';
                if($diadiem){
                    return $diadiem->gh_diachigh.'  '.$action;
                }else{
                    return $action;
                }
            })

            ->addColumn('actions', function($dt){
                $id_user = DB::table('nhanvien_giaohang')
                            ->select('gh_id')
                            ->where('gh_id',$dt->id_nvgh)
                            ->first();
                if(isset($id_user->gh_id)){
                    $id_user = $id_user->gh_id;
                }else{
                    $id_user = "";
                }

                $action = '<a href="'.route('don_hangcho').'" class="edit_inf" dt-id="'.$dt->xct_id.'" dt-gh="'.$id_user.'"><i class="fa-solid fa-clipboard-check" data-bs-placement="top" title="Phân đơn"></i></a>';
                return $action;
            })
            ->rawColumns(['actions','dia_diem'])
            ->make(true);
    }

    public function deal(Request $req){
        $save_nhan = DB::table('nh_khachhang')
                    ->where('nh_id',$req->id_nh)
                    ->first();
        $save_nh = DB::table('nh_khachhang')
                    ->where('nh_id',$req->id_nh)
                    ->update([
                        // 'nh_trangthai' => 'duyệt',
                        'check' => 1,
                        'id_nhanviengh' => $req->id_gh,
                    ]);

        $save_tt = DB::table('xe_congty')
                    ->leftjoin('nhanvien_giaohang','nhanvien_giaohang.gh_id_xecongty','=','xe_congty.xct_id')
                    ->where('xe_congty.xct_id',$req->id_xct)
                    ->first();
        if($save_tt->gh_trangthai == 'đang vận chuyển'){
            DB::table('xe_congty')
                ->leftjoin('nhanvien_giaohang','nhanvien_giaohang.gh_id_xecongty','=','xe_congty.xct_id')
                ->where('xe_congty.xct_id',$req->id_xct)
                ->update([
                    // 'gh_trangthai' => 'đang vận chuyển',
                    'gh_diachigh' => $save_tt->gh_diachigh.'( đơn hàng chờ '.$save_nhan->nh_noilayhang.'->'.$save_nhan->nh_diachi.')',
                ]);
        }else{
            DB::table('xe_congty')
                ->leftjoin('nhanvien_giaohang','nhanvien_giaohang.gh_id_xecongty','=','xe_congty.xct_id')
                ->where('xe_congty.xct_id',$req->id_xct)
                ->update([
                    'gh_trangthai' => 'đang vận chuyển',
                    'gh_diachigh' => $save_nhan->nh_noilayhang.'->'.$save_nhan->nh_diachi,
                ]);
        }

        return 1;
    }

    public function update_donhang(){

        return view('admin.update_donhang');
    }

    public function request_yeucau(Request $req){
        if(Auth::user()->permissions == 3){
            $data = DB::table('nh_khachhang')
                    ->where('check',1)
                    ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                    ->where('nh_trangthai','duyệt')
                    ->orderBy('nh_created_at','desc');
            return DataTables::of($data)
                ->addColumn('actions',function($dt){
                        $action = '<button class="complet" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-cart-shopping" data-bs-placement="top" title="Nhận hàng thành công"></i></button>'.'</br>'.'<button class="hoandon" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-arrow-right-arrow-left" data-bs-placement="top" title="Hoàn đơn"></i></button>'.'</br>'.'<button class="delete_inf" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-trash-can"></i></button>';
                            return $action;
                        })  
                ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })
                ->addColumn('sodienthoai', function($dt){
                    $sodienthoai = DB::table('users')
                                ->select('email')
                                ->where('id',$dt->nh_id_user)
                                ->first();

                    if($sodienthoai){
                        return $sodienthoai->email;
                    }else{
                        return '';
                    }
                })
                ->addColumn('tenkhachhang', function($dt){
                    $udd = DB::table('users')
                                ->select('id_nhanvien')
                                ->where('id',$dt->nh_id_user)
                                ->first();
                        if($udd){
                          $tenkhachhang = DB::table('khach_hang')
                            ->select('kh_ten')
                            ->where('kh_id',$udd->id_nhanvien)
                            ->first();

                            if($tenkhachhang){
                                return $tenkhachhang->kh_ten;
                            }else{
                                return '';
                            }  
                        }else{
                            return '';
                        }
                })
                ->addColumn('name_kh', function($dt){
                    $name = DB::table('khach_hang')
                                ->select('kh_ten')
                                ->where('kh_id',$dt->nh_id_khachhang)
                                ->first();
                    if($name){
                        return $name->kh_ten;
                    }else{
                        return '';
                    }
                })
               
                    ->rawColumns(['actions','anhminhhoa'])
                    ->make(true);
        }else if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
                 $data = DB::table('nh_khachhang')
                    ->where('check',1)
                    ->where('nh_trangthai','duyệt')
                    ->orderBy('nh_created_at','desc');
                return DataTables::of($data)
                    ->addColumn('actions',function($dt){
                        $action = '<button class="complet" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-cart-shopping" data-bs-placement="top" title="Nhận hàng thành công">></i></button>'.'</br>'.'<button class="hoandon" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-arrow-right-arrow-left" data-bs-placement="top" title="Hoàn đơn"></i></button>'.'</br>'.'<button class="delete_inf" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-trash-can" data-bs-placement="top" title="Hủy đơn"></i></button>';
                            return $action;
                        })  
                    ->addColumn('sodienthoai', function($dt){
                        $sodienthoai = DB::table('users')
                                    ->select('email')
                                    ->where('id',$dt->nh_id_user)
                                    ->first();

                        if($sodienthoai){
                            return $sodienthoai->email;
                        }else{
                            return '';
                        }
                    })

                     ->addColumn('anhminhhoa',function($dt){
                            if($dt->nh_anhminhhoa != ''){
                                return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                            }else{
                                return "";
                            }
                        })
                    ->addColumn('tenkhachhang', function($dt){
                        $udd = DB::table('users')
                                    ->select('id_nhanvien')
                                    ->where('id',$dt->nh_id_user)
                                    ->first();
                            if($udd){
                              $tenkhachhang = DB::table('khach_hang')
                                ->select('kh_ten')
                                ->where('kh_id',$udd->id_nhanvien)
                                ->first();

                                if($tenkhachhang){
                                    return $tenkhachhang->kh_ten;
                                }else{
                                    return '';
                                }  
                            }else{
                                return '';
                            }
                    })
                    ->addColumn('name_kh', function($dt){
                        $name = DB::table('khach_hang')
                                    ->select('kh_ten')
                                    ->where('kh_id',$dt->nh_id_khachhang)
                                    ->first();
                        if($name){
                            return $name->kh_ten;
                        }else{
                            return '';
                        }
                    })
                
                    ->rawColumns(['actions','anhminhhoa'])
                    ->make(true);
        }
    }

    public function don_hoanthanh(){
        return view('admin.don_hoanthanh');
    }

    public function show_don(){
        if(Auth::user()->permissions == 1){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','thành công')
                    ->where('nh_id_user',Auth::user()->id)
                    ->orderBy('nh_updated_at','desc');

            return DataTables::of($data)
                    ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })
                    ->rawColumns(['anhminhhoa'])
                    ->make(true);
        }else if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','thành công')
                    ->orderBy('nh_updated_at','desc');

            return DataTables::of($data)
                        ->addColumn('sdt',function($dt){
                            $name = DB::table("users")
                                        ->where('id',$dt->nh_id_user)
                                        ->first();

                            if($name){
                                return $name->email;
                            }else{
                                return "Không thấy dữ liệu";
                            }
                        })
                        ->addColumn('anhminhhoa',function($dt){
                            if($dt->nh_anhminhhoa != ''){
                                return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                            }else{
                                return "";
                            }
                        })
                        ->addColumn('name_kh',function($dt){
                            $name_kh = DB::table("users")
                                            ->where('id',$dt->nh_id_user)
                                            ->first();
                            if($name_kh){
                              $tenkhachhang = DB::table('khach_hang')
                                ->select('kh_ten')
                                ->where('kh_id',$name_kh->id_nhanvien)
                                ->first();

                                if($tenkhachhang){
                                    return $tenkhachhang->kh_ten;
                                }else{
                                    return '';
                                }  
                            }else{
                                return '';
                            }
                        })

                        ->addColumn('nhavgh',function($dt){
                            // $name_kh = DB::table("users")
                            //                 ->where('id_nhanvien',$dt->id_nhanviengh)
                            //                 ->first();
                            
                              $tenkhachhang = DB::table('nhanvien_giaohang')
                                ->select('gh_tennvgh')
                                ->where('gh_id',$dt->id_nhanviengh)
                                ->first();

                                if($tenkhachhang){
                                    return $tenkhachhang->gh_tennvgh;
                                }else{
                                    return '';
                                }  
                            
                        })
                        ->rawColumns(['anhminhhoa'])
                        ->make(true);
        }else if(Auth::user()->permissions == 3){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','thành công')
                    ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                    ->orderBy('nh_updated_at','desc');

            return DataTables::of($data)
                        ->addColumn('sdt',function($dt){
                            $name = DB::table("users")
                                        ->where('id',$dt->nh_id_user)
                                        ->first();

                            if($name){
                                return $name->email;
                            }else{
                                return "Không thấy dữ liệu";
                            }
                        })
                        ->addColumn('name_kh',function($dt){
                            $name_kh = DB::table("users")
                                            ->where('id',$dt->nh_id_user)
                                            ->first();
                            if($name_kh){
                              $tenkhachhang = DB::table('khach_hang')
                                ->select('kh_ten')
                                ->where('kh_id',$name_kh->id_nhanvien)
                                ->first();

                                if($tenkhachhang){
                                    return $tenkhachhang->kh_ten;
                                }else{
                                    return '';
                                }  
                            }else{
                                return '';
                            }
                        })
                        ->addColumn('anhminhhoa',function($dt){
                            if($dt->nh_anhminhhoa != ''){
                                return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                            }else{
                                return "";
                            }
                        })
                        ->rawColumns(['anhminhhoa'])
                        ->make(true);
        }
        
    }

    public function update_status(Request $req){
        $save = DB::table('nh_khachhang')
                        ->where('nh_id',$req->id_nh)
                        ->update([
                            'nh_trangthai' => 'thành công',
                            'nh_updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
        return 1;
    }

    public function update_hoandon(Request $req){
        $save = DB::table('nh_khachhang')
                        ->where('nh_id',$req->id_nh)
                        ->update([
                            'nh_trangthai' => $req->maleValue,
                            'nh_phanhoi' => $req->value_hoandon,
                            'nh_updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
        return 1;
    }

    public function update_huydon(Request $req){
        $save = DB::table('nh_khachhang')
                        ->where('nh_id',$req->id_nh)
                        ->update([
                            'nh_trangthai' => 'khác',
                            'nh_phanhoi' => $req->value_huydon,
                            'nh_updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        ]);
        return 1;
    }

    public function hoandon(){
        if(Auth::user()->permissions == 1){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','<>','chờ')
                    ->where('nh_trangthai','<>','duyệt')
                    ->where('nh_trangthai','<>','thành công')
                    ->where('nh_trangthai','<>','xóa')
                    ->where('nh_id_user',Auth::user()->id)
                    ->orderBy('nh_updated_at','desc');
            return DataTables::of($data)
                    ->addColumn('nguyenhan',function($dt){
                        if($dt->nh_phanhoi != ''){
                            return $dt->nh_trangthai . '('. $dt->nh_phanhoi . ')';
                        }else{
                            return $dt->nh_trangthai ;
                        }
                        
                    })
                    ->addColumn('sdt',function($dt){
                        $name = DB::table("users")
                                    ->where('id',$dt->nh_id_user)
                                    ->first();

                        if($name){
                            return $name->email;
                        }else{
                            return "Không thấy dữ liệu";
                        }
                    })
                    ->addColumn('name_kh',function($dt){
                        $name_kh = DB::table("users")
                                        ->where('id',$dt->nh_id_user)
                                        ->first();
                        if($name_kh){
                          $tenkhachhang = DB::table('khach_hang')
                            ->select('kh_ten')
                            ->where('kh_id',$name_kh->id_nhanvien)
                            ->first();

                            if($tenkhachhang){
                                return $tenkhachhang->kh_ten;
                            }else{
                                return '';
                            }  
                        }else{
                            return '';
                        }
                    })
                    ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })
                    ->rawColumns(['anhminhhoa'])
                    ->make(true);
        }else if(Auth::user()->permissions == 3){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','<>','chờ')
                    ->where('nh_trangthai','<>','duyệt')
                    ->where('nh_trangthai','<>','thành công')
                    ->where('nh_trangthai','<>','xóa')
                    ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                    ->orderBy('nh_updated_at','desc');
            return DataTables::of($data)
                    ->addColumn('nguyenhan',function($dt){
                        if($dt->nh_phanhoi != ''){
                            return $dt->nh_trangthai . '('. $dt->nh_phanhoi . ')';
                        }else{
                            return $dt->nh_trangthai ;
                        }
                        
                    })
                    ->addColumn('sdt',function($dt){
                        $name = DB::table("users")
                                    ->where('id',$dt->nh_id_user)
                                    ->first();

                        if($name){
                            return $name->email;
                        }else{
                            return "Không thấy dữ liệu";
                        }
                    })
                    ->addColumn('name_kh',function($dt){
                        $name_kh = DB::table("users")
                                        ->where('id',$dt->nh_id_user)
                                        ->first();
                        if($name_kh){
                          $tenkhachhang = DB::table('khach_hang')
                            ->select('kh_ten')
                            ->where('kh_id',$name_kh->id_nhanvien)
                            ->first();

                            if($tenkhachhang){
                                return $tenkhachhang->kh_ten;
                            }else{
                                return '';
                            }  
                        }else{
                            return '';
                        }
                    })
                    ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })
                    ->rawColumns(['anhminhhoa'])
                    ->make(true);
        }else{
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','<>','chờ')
                    ->where('nh_trangthai','<>','duyệt')
                    ->where('nh_trangthai','<>','thành công')
                    ->where('nh_trangthai','<>','xóa')
                    ->orderBy('nh_updated_at','desc');
            return DataTables::of($data)
                    ->addColumn('nguyenhan',function($dt){
                        if($dt->nh_phanhoi != ''){
                            return $dt->nh_trangthai . '('. $dt->nh_phanhoi . ')';
                        }else{
                            return $dt->nh_trangthai ;
                        }
                        
                    })
                    ->addColumn('sdt',function($dt){
                        $name = DB::table("users")
                                    ->where('id',$dt->nh_id_user)
                                    ->first();

                        if($name){
                            return $name->email;
                        }else{
                            return "Không thấy dữ liệu";
                        }
                    })
                    ->addColumn('name_kh',function($dt){
                        $name_kh = DB::table("users")
                                        ->where('id',$dt->nh_id_user)
                                        ->first();
                        if($name_kh){
                          $tenkhachhang = DB::table('khach_hang')
                            ->select('kh_ten')
                            ->where('kh_id',$name_kh->id_nhanvien)
                            ->first();

                            if($tenkhachhang){
                                return $tenkhachhang->kh_ten;
                            }else{
                                return '';
                            }  
                        }else{
                            return '';
                        }
                    })
                     ->addColumn('nhavgh',function($dt){
                            // $name_kh = DB::table("users")
                            //                 ->where('id_nhanvien',$dt->id_nhanviengh)
                            //                 ->first();
                            
                              $tenkhachhang = DB::table('nhanvien_giaohang')
                                ->select('gh_tennvgh')
                                ->where('gh_id',$dt->id_nhanviengh)
                                ->first();

                                if($tenkhachhang){
                                    return $tenkhachhang->gh_tennvgh;
                                }else{
                                    return '';
                                }  
                            
                    })
                     ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })
                    ->rawColumns(['anhminhhoa'])
                    ->make(true);
        }
        
    }

    public function huydon(){
        if(Auth::user()->permissions == 1){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','=','xóa')
                    ->where('nh_id_user',Auth::user()->id)
                    ->orderBy('nh_updated_at','desc');
            return DataTables::of($data)
                ->addColumn('sdt',function($dt){
                    $name = DB::table("users")
                                ->where('id',$dt->nh_id_user)
                                ->first();

                    if($name){
                        return $name->email;
                    }else{
                        return "Không thấy dữ liệu";
                    }
                })
                ->addColumn('name_kh',function($dt){
                    $name_kh = DB::table("users")
                                    ->where('id',$dt->nh_id_user)
                                    ->first();
                    if($name_kh){
                      $tenkhachhang = DB::table('khach_hang')
                        ->select('kh_ten')
                        ->where('kh_id',$name_kh->id_nhanvien)
                        ->first();

                        if($tenkhachhang){
                            return $tenkhachhang->kh_ten;
                        }else{
                            return '';
                        }  
                    }else{
                        return '';
                    }
                })
                ->addColumn('anhminhhoa',function($dt){
                    if($dt->nh_anhminhhoa != ''){
                        return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                    }else{
                        return "";
                    }
                })
                ->rawColumns(['anhminhhoa'])
                ->make(true);
        }else if(Auth::user()->permissions == 3){
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','=','xóa')
                    ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                    ->orderBy('nh_updated_at','desc');
            return DataTables::of($data)
                ->addColumn('sdt',function($dt){
                    $name = DB::table("users")
                                ->where('id',$dt->nh_id_user)
                                ->first();

                    if($name){
                        return $name->email;
                    }else{
                        return "Không thấy dữ liệu";
                    }
                })
                ->addColumn('name_kh',function($dt){
                    $name_kh = DB::table("users")
                                    ->where('id',$dt->nh_id_user)
                                    ->first();
                    if($name_kh){
                      $tenkhachhang = DB::table('khach_hang')
                        ->select('kh_ten')
                        ->where('kh_id',$name_kh->id_nhanvien)
                        ->first();

                        if($tenkhachhang){
                            return $tenkhachhang->kh_ten;
                        }else{
                            return '';
                        }  
                    }else{
                        return '';
                    }
                })
                ->addColumn('anhminhhoa',function($dt){
                    if($dt->nh_anhminhhoa != ''){
                        return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                    }else{
                        return "";
                    }
                })
                ->rawColumns(['anhminhhoa'])       
                ->make(true);
        }else{
            $data = DB::table('nh_khachhang')
                    ->where('nh_trangthai','=','xóa')
                    ->orderBy('nh_updated_at','desc');
            return DataTables::of($data)
                ->addColumn('sdt',function($dt){
                    $name = DB::table("users")
                                ->where('id',$dt->nh_id_user)
                                ->first();

                    if($name){
                        return $name->email;
                    }else{
                        return "Không thấy dữ liệu";
                    }
                })
                ->addColumn('name_kh',function($dt){
                    $name_kh = DB::table("users")
                                    ->where('id',$dt->nh_id_user)
                                    ->first();
                    if($name_kh){
                      $tenkhachhang = DB::table('khach_hang')
                        ->select('kh_ten')
                        ->where('kh_id',$name_kh->id_nhanvien)
                        ->first();

                        if($tenkhachhang){
                            return $tenkhachhang->kh_ten;
                        }else{
                            return '';
                        }  
                    }else{
                        return '';
                    }
                })
                ->addColumn('anhminhhoa',function($dt){
                    if($dt->nh_anhminhhoa != ''){
                        return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                    }else{
                        return "";
                    }
                })
                ->rawColumns(['anhminhhoa'])
                ->make(true);
        }
        
    }

    public function list_car(){

        return view('admin.list_car');
    }

    public function show_car(){
        $data = DB::table('xe_congty')
                    ->where('xct_deleted_at',null)
                    ->orderBy('created_at','desc');

        return DataTables::of($data)
            ->addColumn('tenlaixe',function($dt){
                $name = DB::table('nhanvien_giaohang')
                            ->select('gh_tennvgh')
                            ->where('gh_id',$dt->id_nvgh)
                            ->first();
                if ($name) {
                    return $name->gh_tennvgh;
                }else{
                    return '';
                }
            })

            ->addColumn('anhminhhoa',function($dt){
                if($dt->xct_anh != ''){
                    return '<a href="'.$dt->xct_anh.'" target="_blank"><img src="' . $dt->xct_anh . '" width="70%" alt="lỗi"/></a>';
                }else{
                    return "";
                }
            })
            ->addColumn('sdt',function($dt){
                $phone = DB::table('nhanvien_giaohang')
                            ->select('gh_id')
                            ->where('gh_id',$dt->id_nvgh)
                            ->first();

                if($phone){
                    $sdt = DB::table('users')
                            ->select('email')
                            ->where('id_nhanvien',$phone->gh_id)
                            ->first();
                    return $sdt->email;
                }else{
                    return '';
                }
            })

            ->addColumn('gender',function($dt){
                $gender = DB::table('nhanvien_giaohang')
                            ->select('gh_gioitinh')
                            ->where('gh_id',$dt->id_nvgh)
                            ->first();
                if($gender){
                    return $gender->gh_gioitinh;
                }else{
                    return '';
                }
            })
              ->addColumn('actions',function($dt){
                    $action = '<a class="complet" href="'.route('add_car',$dt->xct_id).'" dt-id="'.$dt->xct_id.'"data-bs-placement="top" title="Chỉnh sửa"><i class="fa-solid fa-pen-to-square"></i></a>'.'</br>'.'<button class="delete" dt-id="'.$dt->xct_id.'"><i class="fa-solid fa-trash-can" data-bs-placement="top" title="Loại bỏ xe"></i></button>';
                    return $action;
                }) 
                    ->rawColumns(['actions','anhminhhoa'])
                    ->make(true);
    }

    public function data_nvgh(Request $req){
        $data = DB::table('nhanvien_giaohang')
                    ->where('nhanvien_giaohang.gh_id',$req->id_nvgh)
                    ->leftjoin('users','users.id_nhanvien','nhanvien_giaohang.gh_id')
                    ->where('deleted_at',null)
                    // ->where('gh_bophan','=','giao hàng')
                    ->first();
        return $data;
    }

    public function delete_car(Request $req){

            $data = DB::table('xe_congty')
                    ->where('xct_id',$req->id_xct)
                    ->update([
                        'xct_deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'xct_note_dlcar' => $req->note,
                    ]);
            // $update_tx = DB::table('nhanvien_giaohang')
            //                 ->where('gh_id_xecongty',$req->id_xct)
            //                 ->update([
            //                     'gh_id_xecongty' => null,
            //                 ]);
    }

    public function list_car_del(){

        return view('admin.list_car_del');
    }

    public function list_car_delete(){

        $data = DB::table('xe_congty')
                    ->whereNotNull('xct_deleted_at');
        return DataTables::of($data)
                    ->addColumn('actions',function($dt){
                            $action = '<button class="khoi_phuc" dt-id="'.$dt->xct_id.'"><i class="fa-solid fa-trash-can-arrow-up"></i></button>';
                            return $action;
                    })
                    ->addColumn('anhminhhoa',function($dt){
                        if($dt->xct_anh != ''){
                            return '<a href="'.$dt->xct_anh.'" target="_blank"><img src="' . $dt->xct_anh . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })
                    ->addColumn('ghichu',function($dt){
                        if($dt->xct_note_dlcar == null){
                            return "Hết niên hạn sử dụng";
                        }else{
                            return $dt->xct_note_dlcar;
                        }
                    })
                    ->rawColumns(['actions','anhminhhoa'])
                    ->make(true);

    }

    public function khoi_phuc(Request $req){

        $save = DB::table('xe_congty')
                    ->where('xct_id',$req->xct)
                    ->update([
                        'xct_deleted_at' => null,
                    ]);
        return 1;
    }

    public function dang_nhap(Request $req){

        return view('admin.dang_nhap');
    }

    public function tai_khoan(Request $req){
        $credentials = $req->only('username', 'passwork');

        if (Auth::attempt($credentials)) {
            $user = Auth::user()->username;
            echo($user);
            // Authentication passed...
            // return redirect()->intended('dashboard');
        }
        // $user = DB::table('user');
        // echo ($req->ten_dangnhap);
        // echo ($req->matkhau);
    }

    public function dangky_ac(){

        return view('admin.dangky_ac');
    }

    public function bieudo(){

        return view('admin.bieudo');
    }

    public function thongtindonhang(){

        if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            $startDate = Carbon::now()->subDays(7);
            $startDate2 = Carbon::now()->subDays(30);
            $currentDate = Carbon::now();

            
            $array = array();
            $donhang_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->count();
            $soluong_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->sum('nh_soluong');
            $tiendo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien = 0;
            foreach($tiendo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien += $t;
            }            
            $tien = number_format($tien);

            $donhang_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->count();
            $soluong_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->sum('nh_soluong');
            $tien_dgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_dg = 0;
            foreach($tien_dgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_dg += $t;
            }
            $tien_dg = number_format($tien_dg);

            $phivanchuyen_ghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_gh = 0;
            foreach($phivanchuyen_ghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_gh += $t;
            }
            $phivanchuyen_gh = number_format($phivanchuyen_gh);
            $phivanchuyen_hddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_hd = 0;
            foreach($phivanchuyen_hddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hd += $t;
            }
            $phivanchuyen_hd = number_format($phivanchuyen_hd);
            // 7 ngày 

            $donhangsevent_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();                
            $soluong_s_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->sum('nh_soluong');

            // $startDate = Carbon::now()->subDays(7);
            // $currentDate = Carbon::now();
            $tien_seventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_sevent = 0;
            foreach($tien_seventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_sevent += $t;
            }            
            $tien_sevent = number_format($tien_sevent);
            // $tien_sevent = DB::table('nh_khachhang')
            //             ->where('nh_trangthai', 'thành công')
            //             ->where(function ($query) use ($startDate, $currentDate) {
            //                 $query->where('nh_updated_at', '>=', $startDate->startOfDay())
            //                     ->where('nh_updated_at', '<=', $currentDate->endOfDay());
            //             })
            //             ->sum('nh_tienthuho');



            $donhang_sdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
            $soluong_sdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->sum('nh_soluong');
            $tien_sdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_sdg = 0;
            foreach($tien_sdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_sdg += $t;
            }            
            $tien_sdg = number_format($tien_sdg);
            $phivanchuyen_sghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_sgh = 0;
            foreach($phivanchuyen_sghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_sgh += $t;
            }            
            $phivanchuyen_sgh = number_format($phivanchuyen_sgh);
            $phivanchuyen_hsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_hsd = 0;
            foreach($phivanchuyen_hsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hsd += $t;
            }            
            $phivanchuyen_hsd = number_format($phivanchuyen_hsd);
            // 30 ngày
            // $startDate = $currentDate->subDays(30);     
            $startDate = Carbon::now()->subDays(30);
            $currentDate = Carbon::now();           
            $donhangsevent_ttc = DB::table('nh_khachhang')
                            ->where('nh_trangthai', 'thành công')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay()->toDateTimeString(), $currentDate->endOfDay()->toDateTimeString()])
                            ->count();

            $soluong_s_ttc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->sum('nh_soluong');

            $tien_tseventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_tsevent = 0;
            foreach($tien_tseventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsevent += $t;
            }            
            $tien_tsevent = number_format($tien_tsevent);
            $donhang_tsdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
            $soluong_tsdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->sum('nh_soluong');
            $tien_tsdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_tsdg = 0;
            foreach($tien_tsdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsdg += $t;
            }            
            $tien_tsdg = number_format($tien_tsdg);            
            $phivanchuyen_tsghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_tsgh = 0;
            foreach($phivanchuyen_tsghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_tsgh += $t;
            }            
            $phivanchuyen_tsgh = number_format($phivanchuyen_tsgh);
            $phivanchuyen_thsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_thsd = 0;
            foreach($phivanchuyen_thsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_thsd += $t;
            }            
            $phivanchuyen_thsd = number_format($phivanchuyen_thsd);

            // Tất cả

            $donhangsevent_ttcs = DB::table('nh_khachhang')
                            ->where('nh_trangthai', 'thành công')
                            ->count();

            $soluong_s_ttcs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->sum('nh_soluong');

            $tien_tseventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_tsevents = 0;
            foreach($tien_tseventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsevents += $t;
            }            
            $tien_tsevents = number_format($tien_tsevents);
            $donhang_tsdgs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->count();
            $soluong_tsdgs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->sum('nh_soluong');
            $tien_tsdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_tsdgs = 0;
            foreach($tien_tsdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsdgs += $t;
            }            
            $tien_tsdgs = number_format($tien_tsdgs);            
            $phivanchuyen_tsghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_tsghs = 0;
            foreach($phivanchuyen_tsghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_tsghs += $t;
            }            
            $phivanchuyen_tsghs = number_format($phivanchuyen_tsghs);
            $phivanchuyen_thsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_thsds = 0;
            foreach($phivanchuyen_thsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_thsds += $t;
            }            
            $phivanchuyen_thsds = number_format($phivanchuyen_thsds);

            $array[0]['tc_dh'] =  $donhang_tc;
            $array[1]['tc_sp'] =  $soluong_tc;
            $array[2]['tc_tien'] =  $tien;
            $array[3]['dg_dh'] =  $donhang_dg;
            $array[4]['dg_sl'] =  $soluong_dg;
            $array[5]['dg_tien'] =  $tien_dg;
            $array[6]['phigh'] =  $phivanchuyen_gh;
            $array[7]['phihd'] =  $phivanchuyen_hd;
            $array[8]['tc_s_dh'] =  $donhangsevent_tc;
            $array[9]['tc_s_sl'] =  $soluong_s_tc;
            $array[10]['tien_s'] =  $tien_sevent;
            $array[11]['donhang_sdg'] =  $donhang_sdg;
            $array[12]['soluong_sdg'] =  $soluong_sdg;
            $array[13]['tien_sdg'] =  $tien_sdg;
            $array[14]['phivanchuyen_sgh'] =  $phivanchuyen_sgh;
            $array[15]['phivanchuyen_hsd'] =  $phivanchuyen_hsd;
            $array[16]['donhangsevent_ttc'] =  $donhangsevent_ttc;
            $array[17]['soluong_s_ttc'] =  $soluong_s_ttc;
            $array[18]['tien_tsevent'] =  $tien_tsevent;
            $array[19]['donhang_tsdg'] =  $donhang_tsdg;
            $array[20]['soluong_tsdg'] =  $soluong_tsdg;
            $array[21]['tien_tsdg'] =  $tien_tsdg;
            $array[22]['phivanchuyen_tsgh'] =  $phivanchuyen_tsgh;
            $array[23]['phivanchuyen_thsd'] =  $phivanchuyen_thsd;

            $array[24]['donhangsevent_ttcs'] =  $donhangsevent_ttcs;
            $array[25]['soluong_s_ttcs'] =  $soluong_s_ttcs;
            $array[26]['tien_tsevents'] =  $tien_tsevents;
            $array[27]['donhang_tsdgs'] =  $donhang_tsdgs;
            $array[28]['soluong_tsdgs'] =  $soluong_tsdgs;
            $array[29]['tien_tsdgs'] =  $tien_tsdgs;
            $array[30]['phivanchuyen_tsghs'] =  $phivanchuyen_tsghs;
            $array[31]['phivanchuyen_thsds'] =  $phivanchuyen_thsds;
            // $array[24]['tien_s'] =  $tien_sevent;
            // $array[25]['tien_s'] =  $tien_sevent;
            // $array[26]['tien_s'] =  $tien_sevent;
            return $array;
        }else if(Auth::user()->permissions == 1){
            $startDate = Carbon::now()->subDays(7);
            $startDate2 = Carbon::now()->subDays(30);
            $currentDate = Carbon::now();

            
            $array = array();
            $donhang_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
            $soluong_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');
            $tiendo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien = 0;
            foreach($tiendo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien += $t;
            }            
            $tien = number_format($tien);

            $donhang_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
            $soluong_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');
            $tien_dgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_dg = 0;
            foreach($tien_dgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_dg += $t;
            }
            $tien_dg = number_format($tien_dg);

            $phivanchuyen_ghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_gh = 0;
            foreach($phivanchuyen_ghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_gh += $t;
            }
            $phivanchuyen_gh = number_format($phivanchuyen_gh);
            $phivanchuyen_hddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_hd = 0;
            foreach($phivanchuyen_hddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hd += $t;
            }
            $phivanchuyen_hd = number_format($phivanchuyen_hd);
            // 7 ngày 

            $donhangsevent_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();                
            $soluong_s_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');

            // $startDate = Carbon::now()->subDays(7);
            // $currentDate = Carbon::now();
            $tien_seventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_sevent = 0;
            foreach($tien_seventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_sevent += $t;
            }            
            $tien_sevent = number_format($tien_sevent);
            // $tien_sevent = DB::table('nh_khachhang')
            //             ->where('nh_trangthai', 'thành công')
            //             ->where(function ($query) use ($startDate, $currentDate) {
            //                 $query->where('nh_updated_at', '>=', $startDate->startOfDay())
            //                     ->where('nh_updated_at', '<=', $currentDate->endOfDay());
            //             })
            //             ->sum('nh_tienthuho');



            $donhang_sdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
            $soluong_sdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');
            $tien_sdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_sdg = 0;
            foreach($tien_sdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_sdg += $t;
            }            
            $tien_sdg = number_format($tien_sdg);
            $phivanchuyen_sghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->where('nh_id_user',Auth::user()->id)
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_sgh = 0;
            foreach($phivanchuyen_sghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_sgh += $t;
            }            
            $phivanchuyen_sgh = number_format($phivanchuyen_sgh);
            $phivanchuyen_hsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_hsd = 0;
            foreach($phivanchuyen_hsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hsd += $t;
            }            
            $phivanchuyen_hsd = number_format($phivanchuyen_hsd);
            // 30 ngày
            // $startDate = $currentDate->subDays(30);     
            $startDate = Carbon::now()->subDays(30);
            $currentDate = Carbon::now();           
            $donhangsevent_ttc = DB::table('nh_khachhang')
                            ->where('nh_trangthai', 'thành công')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay()->toDateTimeString(), $currentDate->endOfDay()->toDateTimeString()])
                            ->count();

            $soluong_s_ttc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');

            $tien_tseventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_tsevent = 0;
            foreach($tien_tseventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsevent += $t;
            }            
            $tien_tsevent = number_format($tien_tsevent);
            $donhang_tsdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
            $soluong_tsdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');
            $tien_tsdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_tsdg = 0;
            foreach($tien_tsdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsdg += $t;
            }            
            $tien_tsdg = number_format($tien_tsdg);            
            $phivanchuyen_tsghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('nh_id_user',Auth::user()->id)
                            ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_tsgh = 0;
            foreach($phivanchuyen_tsghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_tsgh += $t;
            }            
            $phivanchuyen_tsgh = number_format($phivanchuyen_tsgh);
            $phivanchuyen_thsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user',Auth::user()->id)
                            ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_thsd = 0;
            foreach($phivanchuyen_thsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_thsd += $t;
            }            
            $phivanchuyen_thsd = number_format($phivanchuyen_thsd);

            // Tất cả

            $donhangsevent_ttcs = DB::table('nh_khachhang')
                            ->where('nh_trangthai', 'thành công')
                            ->where('nh_id_user',Auth::user()->id)
                            ->count();

            $soluong_s_ttcs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');

            $tien_tseventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_tsevents = 0;
            foreach($tien_tseventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsevents += $t;
            }            
            $tien_tsevents = number_format($tien_tsevents);
            $donhang_tsdgs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
            $soluong_tsdgs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user',Auth::user()->id)
                        ->sum('nh_soluong');
            $tien_tsdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_tsdgs = 0;
            foreach($tien_tsdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsdgs += $t;
            }            
            $tien_tsdgs = number_format($tien_tsdgs);            
            $phivanchuyen_tsghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('nh_id_user',Auth::user()->id)
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_tsghs = 0;
            foreach($phivanchuyen_tsghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_tsghs += $t;
            }            
            $phivanchuyen_tsghs = number_format($phivanchuyen_tsghs);
            $phivanchuyen_thsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user',Auth::user()->id)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_thsds = 0;
            foreach($phivanchuyen_thsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_thsds += $t;
            }            
            $phivanchuyen_thsds = number_format($phivanchuyen_thsds);

            $array[0]['tc_dh'] =  $donhang_tc;
            $array[1]['tc_sp'] =  $soluong_tc;
            $array[2]['tc_tien'] =  $tien;
            $array[3]['dg_dh'] =  $donhang_dg;
            $array[4]['dg_sl'] =  $soluong_dg;
            $array[5]['dg_tien'] =  $tien_dg;
            $array[6]['phigh'] =  $phivanchuyen_gh;
            $array[7]['phihd'] =  $phivanchuyen_hd;
            $array[8]['tc_s_dh'] =  $donhangsevent_tc;
            $array[9]['tc_s_sl'] =  $soluong_s_tc;
            $array[10]['tien_s'] =  $tien_sevent;
            $array[11]['donhang_sdg'] =  $donhang_sdg;
            $array[12]['soluong_sdg'] =  $soluong_sdg;
            $array[13]['tien_sdg'] =  $tien_sdg;
            $array[14]['phivanchuyen_sgh'] =  $phivanchuyen_sgh;
            $array[15]['phivanchuyen_hsd'] =  $phivanchuyen_hsd;
            $array[16]['donhangsevent_ttc'] =  $donhangsevent_ttc;
            $array[17]['soluong_s_ttc'] =  $soluong_s_ttc;
            $array[18]['tien_tsevent'] =  $tien_tsevent;
            $array[19]['donhang_tsdg'] =  $donhang_tsdg;
            $array[20]['soluong_tsdg'] =  $soluong_tsdg;
            $array[21]['tien_tsdg'] =  $tien_tsdg;
            $array[22]['phivanchuyen_tsgh'] =  $phivanchuyen_tsgh;
            $array[23]['phivanchuyen_thsd'] =  $phivanchuyen_thsd;

            $array[24]['donhangsevent_ttcs'] =  $donhangsevent_ttcs;
            $array[25]['soluong_s_ttcs'] =  $soluong_s_ttcs;
            $array[26]['tien_tsevents'] =  $tien_tsevents;
            $array[27]['donhang_tsdgs'] =  $donhang_tsdgs;
            $array[28]['soluong_tsdgs'] =  $soluong_tsdgs;
            $array[29]['tien_tsdgs'] =  $tien_tsdgs;
            $array[30]['phivanchuyen_tsghs'] =  $phivanchuyen_tsghs;
            $array[31]['phivanchuyen_thsds'] =  $phivanchuyen_thsds;
            // $array[24]['tien_s'] =  $tien_sevent;
            // $array[25]['tien_s'] =  $tien_sevent;
            // $array[26]['tien_s'] =  $tien_sevent;
            return $array;
        }else{

            $startDate = Carbon::now()->subDays(7);
            $startDate2 = Carbon::now()->subDays(30);
            $currentDate = Carbon::now();

            
            $array = array();
            $donhang_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();
            $soluong_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');
            $tiendo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien = 0;
            foreach($tiendo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien += $t;
            }            
            $tien = number_format($tien);

            $donhang_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();
            $soluong_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');
            $tien_dgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_dg = 0;
            foreach($tien_dgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_dg += $t;
            }
            $tien_dg = number_format($tien_dg);

            $phivanchuyen_ghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_gh = 0;
            foreach($phivanchuyen_ghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_gh += $t;
            }
            $phivanchuyen_gh = number_format($phivanchuyen_gh);
            $phivanchuyen_hddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_hd = 0;
            foreach($phivanchuyen_hddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hd += $t;
            }
            $phivanchuyen_hd = number_format($phivanchuyen_hd);
            // 7 ngày 

            $donhangsevent_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();                
            $soluong_s_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');

            // $startDate = Carbon::now()->subDays(7);
            // $currentDate = Carbon::now();
            $tien_seventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_sevent = 0;
            foreach($tien_seventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_sevent += $t;
            }            
            $tien_sevent = number_format($tien_sevent);
            // $tien_sevent = DB::table('nh_khachhang')
            //             ->where('nh_trangthai', 'thành công')
            //             ->where(function ($query) use ($startDate, $currentDate) {
            //                 $query->where('nh_updated_at', '>=', $startDate->startOfDay())
            //                     ->where('nh_updated_at', '<=', $currentDate->endOfDay());
            //             })
            //             ->sum('nh_tienthuho');



            $donhang_sdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();
            $soluong_sdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');
            $tien_sdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->whereBetween('nh_created_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_sdg = 0;
            foreach($tien_sdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_sdg += $t;
            }            
            $tien_sdg = number_format($tien_sdg);
            $phivanchuyen_sghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_sgh = 0;
            foreach($phivanchuyen_sghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_sgh += $t;
            }            
            $phivanchuyen_sgh = number_format($phivanchuyen_sgh);
            $phivanchuyen_hsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_hsd = 0;
            foreach($phivanchuyen_hsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hsd += $t;
            }            
            $phivanchuyen_hsd = number_format($phivanchuyen_hsd);
            // 30 ngày
            // $startDate = $currentDate->subDays(30);     
            $startDate = Carbon::now()->subDays(30);
            $currentDate = Carbon::now();           
            $donhangsevent_ttc = DB::table('nh_khachhang')
                            ->where('nh_trangthai', 'thành công')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay()->toDateTimeString(), $currentDate->endOfDay()->toDateTimeString()])
                            ->count();

            $soluong_s_ttc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');

            $tien_tseventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_tsevent = 0;
            foreach($tien_tseventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsevent += $t;
            }            
            $tien_tsevent = number_format($tien_tsevent);
            $donhang_tsdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();
            $soluong_tsdg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');
            $tien_tsdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_created_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_tsdg = 0;
            foreach($tien_tsdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsdg += $t;
            }            
            $tien_tsdg = number_format($tien_tsdg);            
            $phivanchuyen_tsghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_tsgh = 0;
            foreach($phivanchuyen_tsghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_tsgh += $t;
            }            
            $phivanchuyen_tsgh = number_format($phivanchuyen_tsgh);
            $phivanchuyen_thsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->where('nh_updated_at', '>=', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_thsd = 0;
            foreach($phivanchuyen_thsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_thsd += $t;
            }            
            $phivanchuyen_thsd = number_format($phivanchuyen_thsd);

            // Tất cả

            $donhangsevent_ttcs = DB::table('nh_khachhang')
                            ->where('nh_trangthai', 'thành công')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->count();

            $soluong_s_ttcs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');

            $tien_tseventdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_tsevents = 0;
            foreach($tien_tseventdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsevents += $t;
            }            
            $tien_tsevents = number_format($tien_tsevents);
            $donhang_tsdgs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();
            $soluong_tsdgs = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');
            $tien_tsdgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');

            $tien_tsdgs = 0;
            foreach($tien_tsdgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_tsdgs += $t;
            }            
            $tien_tsdgs = number_format($tien_tsdgs);            
            $phivanchuyen_tsghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->get();
                            // ->sum('nh_phiship');
            $phivanchuyen_tsghs = 0;
            foreach($phivanchuyen_tsghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_tsghs += $t;
            }            
            $phivanchuyen_tsghs = number_format($phivanchuyen_tsghs);
            $phivanchuyen_thsddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_thsds = 0;
            foreach($phivanchuyen_thsddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_thsds += $t;
            }            
            $phivanchuyen_thsds = number_format($phivanchuyen_thsds);

            $array[0]['tc_dh'] =  $donhang_tc;
            $array[1]['tc_sp'] =  $soluong_tc;
            $array[2]['tc_tien'] =  $tien;
            $array[3]['dg_dh'] =  $donhang_dg;
            $array[4]['dg_sl'] =  $soluong_dg;
            $array[5]['dg_tien'] =  $tien_dg;
            $array[6]['phigh'] =  $phivanchuyen_gh;
            $array[7]['phihd'] =  $phivanchuyen_hd;
            $array[8]['tc_s_dh'] =  $donhangsevent_tc;
            $array[9]['tc_s_sl'] =  $soluong_s_tc;
            $array[10]['tien_s'] =  $tien_sevent;
            $array[11]['donhang_sdg'] =  $donhang_sdg;
            $array[12]['soluong_sdg'] =  $soluong_sdg;
            $array[13]['tien_sdg'] =  $tien_sdg;
            $array[14]['phivanchuyen_sgh'] =  $phivanchuyen_sgh;
            $array[15]['phivanchuyen_hsd'] =  $phivanchuyen_hsd;
            $array[16]['donhangsevent_ttc'] =  $donhangsevent_ttc;
            $array[17]['soluong_s_ttc'] =  $soluong_s_ttc;
            $array[18]['tien_tsevent'] =  $tien_tsevent;
            $array[19]['donhang_tsdg'] =  $donhang_tsdg;
            $array[20]['soluong_tsdg'] =  $soluong_tsdg;
            $array[21]['tien_tsdg'] =  $tien_tsdg;
            $array[22]['phivanchuyen_tsgh'] =  $phivanchuyen_tsgh;
            $array[23]['phivanchuyen_thsd'] =  $phivanchuyen_thsd;

            $array[24]['donhangsevent_ttcs'] =  $donhangsevent_ttcs;
            $array[25]['soluong_s_ttcs'] =  $soluong_s_ttcs;
            $array[26]['tien_tsevents'] =  $tien_tsevents;
            $array[27]['donhang_tsdgs'] =  $donhang_tsdgs;
            $array[28]['soluong_tsdgs'] =  $soluong_tsdgs;
            $array[29]['tien_tsdgs'] =  $tien_tsdgs;
            $array[30]['phivanchuyen_tsghs'] =  $phivanchuyen_tsghs;
            $array[31]['phivanchuyen_thsds'] =  $phivanchuyen_thsds;
            // $array[24]['tien_s'] =  $tien_sevent;
            // $array[25]['tien_s'] =  $tien_sevent;
            // $array[26]['tien_s'] =  $tien_sevent;
            return $array;
        }
        
    }

    public function data_chart(Request $req){
        $arr = array();
        $startDate = Carbon::now()->subDays(7);
        $startDate2 = Carbon::now()->subDays(30);
        $currentDate = Carbon::now();
        $start_time = Carbon::parse($req->tungay)->startOfDay();
        $end_time = Carbon::parse($req->denngay)->endOfDay();
        // $sevenDaysAgo = Carbon::now()->subDays(7);

        // Tính ngày đầu tiên của khoảng thời gian 30 ngày trước
        if(Auth::user()->permissions == 1){
            if($req->info == 'myChart'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_id_user',Auth::user()->id)
                        ->whereDate('nh_updated_at',$currentDate)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
            }else if($req->info == 'myCharts'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_id_user',Auth::user()->id)
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
            }else if($req->info == 'myChartb'){
            
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_id_user',Auth::user()->id)
                        ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_id_user',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
            }else if($req->info == 'mytuychon'){
                
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','không liên hệ được')
                        ->where('nh_id_user',Auth::user()->id)
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                        ->where('nh_trangthai','từ chối nhận')
                        ->where('nh_id_user',Auth::user()->id)
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $khac = DB::table('nh_khachhang')
                        ->where('nh_trangthai','khác')
                        ->where('nh_id_user',Auth::user()->id)
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();

            
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
            }else{
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','không liên hệ được')
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                        ->where('nh_trangthai','từ chối nhận')
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();
                $khac = DB::table('nh_khachhang')
                        ->where('nh_trangthai','khác')
                        ->where('nh_id_user',Auth::user()->id)
                        ->count();

            
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }
        }else if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            if($req->info == 'myChart'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')

                        ->whereDate('nh_updated_at',$currentDate)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
    
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
    
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
    
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
            }else if($req->info == 'myCharts'){

                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')

                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
    
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
    
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
    
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }else if($req->info == 'myChartb'){
               
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')

                        ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','không liên hệ được')

                        ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                        ->where('nh_trangthai','từ chối nhận')

                        ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khac = DB::table('nh_khachhang')
                        ->where('nh_trangthai','khác')

                        ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                        ->count();

            
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }else if($req->info == 'mytuychon'){
                
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','không liên hệ được')

                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                        ->where('nh_trangthai','từ chối nhận')

                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $khac = DB::table('nh_khachhang')
                        ->where('nh_trangthai','khác')

                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();

            
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }else{
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','không liên hệ được')
                        ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                        ->where('nh_trangthai','từ chối nhận')
                        ->count();
                $khac = DB::table('nh_khachhang')
                        ->where('nh_trangthai','khác')
                        ->count();

            
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);
                return $arr;
             }
        }else if(Auth::user()->permissions == 3){

            if($req->info == 'myChart'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->whereDate('nh_updated_at',$currentDate)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
            }else if($req->info == 'myCharts'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }else if($req->info == 'myChartb'){
            
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate2->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }else if($req->info == 'mytuychon'){
                
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','không liên hệ được')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                        ->where('nh_trangthai','từ chối nhận')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $khac = DB::table('nh_khachhang')
                        ->where('nh_trangthai','khác')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();

            
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }else{
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->count();
                array_push($arr, $shophuy);
                array_push($arr, $khonglienhedc);
                array_push($arr, $tuchoinhan);
                array_push($arr, $khac);

                 return $arr;
             }
        }
        
    }

    public function data_chart_if(Request $req){
        $arr = array();
        $currentDate = Carbon::now();
        $start_time = Carbon::parse($req->tungay)->startOfDay();
        $end_time = Carbon::parse($req->denngay)->endOfDay();
        $startDate = Carbon::now()->subDays(7);
        $startDate2 = Carbon::now()->subDays(30);
        // Tính ngày đầu tiên của khoảng thời gian 30 ngày trước
        if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            if($req->info == 'myChart'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->whereDate('nh_updated_at',$currentDate)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $sum =  DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                return $arr;
            }else if($req->info == 'myCharts'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 return $arr;
             }else if($req->info == 'myChartb'){
                $startDate = $currentDate->subDays(30);
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_updated_at', '>=', $startDate)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 return $arr;
             }else if($req->info == 'mytuychon'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->count();

                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 
             }else{
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->count();

                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                return $arr;
                 
             }
        }else if(Auth::user()->permissions == 1){
            if($req->info == 'myChart'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->whereDate('nh_updated_at',$currentDate)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $sum =  DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->where('nh_trangthai','<>','thành công')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                return $arr;
            }else if($req->info == 'myCharts'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 return $arr;
            }else if($req->info == 'myChartb'){
                $startDate = $currentDate->subDays(30);
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_updated_at', '>=', $startDate)
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 return $arr;
            }else if($req->info == 'mytuychon'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();

                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 
            }else{
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();

                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                return $arr;
                 
            }
        }else{

            if($req->info == 'myChart'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                        ->whereDate('nh_updated_at',$currentDate)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $sum =  DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->where('nh_trangthai','<>','thành công')
                            ->whereDate('nh_updated_at',$currentDate)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                return $arr;
            }else if($req->info == 'myCharts'){
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                        ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$startDate->startOfDay(), $currentDate->endOfDay()])
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 return $arr;
            }else if($req->info == 'myChartb'){
                $startDate = $currentDate->subDays(30);
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('nh_updated_at', '>=', $startDate)
                        ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_updated_at', '>=', $startDate)
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                 return $arr;
            }else if($req->info == 'mytuychon'){

                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                        ->count();

                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();

                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                return $arr;  
                 
             }else{
                $shophuy = DB::table('nh_khachhang')
                        ->where('nh_trangthai','xóa')
                        ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                        ->count();
                $khonglienhedc = DB::table('nh_khachhang')
                            ->where('nh_trangthai','không liên hệ được')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();

                $tuchoinhan = DB::table('nh_khachhang')
                            ->where('nh_trangthai','từ chối nhận')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $khac = DB::table('nh_khachhang')
                            ->where('nh_trangthai','khác')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $sum = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh','=',Auth::user()->id_nhanvien)
                            ->count();
                $arr[0]['shophuy'] = $shophuy;
                $arr[1]['khonglienhedc'] = $khonglienhedc;
                $arr[2]['tuchoinhan'] = $tuchoinhan;
                $arr[3]['khac'] = $khac;
                $arr[4]['sum'] = $sum;

                return $arr;
                 
             }
         }
        
    }


    public function list_client(){

        return view('admin.list_client');
    }

    public function list_client_gh(){

        return view('admin.list_client_gh');
    }

    public function list_client_kh(){
        return view('admin.list_client_kh');
    }

    public function list_client_admin(){
        return view('admin.list_client_admin');
    }


    public function list_nv_kp(){
        $data = DB::table('nhan_vien')
                    ->whereNotNull('deleted_at')
                    ->orderBy('deleted_at','desc');
        return DataTables::of($data)
                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->id)
                                ->where('permissions',2)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })

                ->addColumn('anhcmt',function($dt){
                    $nhanvien = '<a href="'.$dt->anh_cmt.'" target="_blank"><img src="'.$dt->anh_cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    
                })
                ->addColumn('note',function($dt){
                    $user = DB::table('users')
                        ->select('id')
                        ->where('id_nhanvien',$dt->id)
                        ->where('permissions',2)
                        ->first();
                   
                    
                    if($user){
                         $khoa = DB::table('khoa_ac')
                                ->where('id_user',$user->id)
                                ->first();
                            if($khoa){
                                return $khoa->ghi_chu;
                            }else{
                                return "Dừng hoạt động";
                            }
                            
                         
                    }else{
                        return "Dừng hoạt động";
                    }
                   
                    
                })
                ->addColumn('actions',function($dt){
                    $user = DB::table('users')
                                ->select('id')
                                ->where('id_nhanvien',$dt->id)
                                ->where('permissions',2)
                                ->first();
                    if($user){
                        $actions = '<button class="khoi_phuc" d-id="'.$user->id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-trash-can-arrow-up"></i></button>';
                        return $actions;
                    }else{
                        return "Không có dữ liệu";
                    }
                    
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function list_nv_kp_gh(){
        $data = DB::table('nhanvien_giaohang')
                    ->whereNotNull('deleted_at')
                    ->orderBy('deleted_at','desc');
        return DataTables::of($data)
                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->gh_id)
                                ->where('permissions',3)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })

                ->addColumn('anhcmt',function($dt){
                    $nhanvien = '<a href="'.$dt->gh_cmt.'" target="_blank"><img src="'.$dt->gh_cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    
                })
                ->addColumn('note',function($dt){
                    $user = DB::table('users')
                        ->select('id')
                        ->where('id_nhanvien',$dt->gh_id)
                        ->where('permissions',3)
                        ->first();
                   
                    
                    if($user){
                         $khoa = DB::table('khoa_ac')
                                ->where('id_user',$user->id)
                                ->first();
                            if($khoa){
                                return $khoa->ghi_chu;
                            }else{
                                return "Dừng hoạt động";
                            }
                            
                         
                    }else{
                        return "Dừng hoạt động";
                    }
                   
                    
                })
                ->addColumn('actions',function($dt){
                    $user = DB::table('users')
                                ->select('id')
                                ->where('id_nhanvien',$dt->gh_id)
                                ->where('permissions',3)
                                ->first();
                    if($user){
                        $actions = '<button class="khoi_phuc" d-id="'.$user->id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-trash-can-arrow-up"></i></button>';
                        return $actions;
                    }else{
                        return "Không có dữ liệu";
                    }
                    
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function list_nv_kp_kh(){
        $data = DB::table('khach_hang')
                    ->whereNotNull('deleted_at')
                    ->orderBy('deleted_at','desc');
        return DataTables::of($data)
                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->kh_id)
                                ->where('permissions',1)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })

                ->addColumn('anhcmt',function($dt){
                    $nhanvien = '<a href="'.$dt->cmt.'" target="_blank"><img src="'.$dt->cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    
                })
                ->addColumn('note',function($dt){
                    $user = DB::table('users')
                        ->select('id')
                        ->where('id_nhanvien',$dt->kh_id)
                        ->where('permissions',1)
                        ->first();
                   
                    
                    if($user){
                         $khoa = DB::table('khoa_ac')
                                ->where('id_user',$user->id)
                                ->first();
                            if($khoa){
                                return $khoa->ghi_chu;
                            }else{
                                return "Dừng hoạt động";
                            }
                            
                         
                    }else{
                        return "Dừng hoạt động";
                    }
                   
                    
                })
                ->addColumn('actions',function($dt){
                    $user = DB::table('users')
                                ->select('id')
                                ->where('id_nhanvien',$dt->kh_id)
                                ->where('permissions',1)
                                ->first();
                    if($user){
                        $actions = '<button class="khoi_phuc" d-id="'.$user->id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-trash-can-arrow-up"></i></button>';
                        return $actions;
                    }else{
                        return "Không có dữ liệu";
                    }
                    
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function list_nv_kp_admin(){
        $data = DB::table('admin')
                    ->whereNotNull('deleted_at')
                    ->orderBy('deleted_at','desc');
        return DataTables::of($data)
                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->id)
                                ->where('permissions',4)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })

                ->addColumn('anhcmt',function($dt){
                    $nhanvien = '<a href="'.$dt->cmt.'" target="_blank"><img src="'.$dt->cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    
                })
                ->addColumn('note',function($dt){
                    $user = DB::table('users')
                        ->select('id')
                        ->where('id_nhanvien',$dt->id)
                        ->where('permissions',4)
                        ->first();
                   
                    
                    if($user){
                         $khoa = DB::table('khoa_ac')
                                ->where('id_user',$user->id)
                                ->first();
                            if($khoa){
                                return $khoa->ghi_chu;
                            }else{
                                return "Dừng hoạt động";
                            }
                            
                         
                    }else{
                        return "Dừng hoạt động";
                    }
                   
                    
                })
                ->addColumn('actions',function($dt){
                    $user = DB::table('users')
                                ->select('id')
                                ->where('id_nhanvien',$dt->id)
                                ->where('permissions',4)
                                ->first();
                    if($user){
                        $actions = '<button class="khoi_phuc" d-id="'.$user->id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-trash-can-arrow-up"></i></button>';
                        return $actions;
                    }else{
                        return "Không có dữ liệu";
                    }
                    
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function khoi_phuc_nhanvien(Request $req){
        $user = DB::table('users')
                    ->where('id',$req->xct)
                    ->first();
        $data = DB::table('nhan_vien')
                    ->where('id',$user->id_nhanvien)
                    ->update([
                            'deleted_at' => null,
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);
        $molai = DB::table('khoa_ac')
                    ->where('id_user',$req->xct)
                    ->update([
                        "khoa_acxz" => 1,
                    ]);

        return 1;
    }

    public function khoi_phuc_nhanvien_gh(Request $req){
        $user = DB::table('users')
                    ->where('id',$req->xct)
                    ->first();
        $data = DB::table('nhanvien_giaohang')
                    ->where('gh_id',$user->id_nhanvien)
                    ->update([
                            'deleted_at' => null,
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);
        $molai = DB::table('khoa_ac')
                    ->where('id_user',$req->xct)
                    ->update([
                        "khoa_acxz" => 1,
                    ]);

        return 1;
    }

    public function khoi_phuc_nhanvien_kh(Request $req){
        $user = DB::table('users')
                    ->where('id',$req->xct)
                    ->first();
        $data = DB::table('khach_hang')
                    ->where('kh_id',$user->id_nhanvien)
                    ->update([
                            'deleted_at' => null,
                            'kh_created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);
        $molai = DB::table('khoa_ac')
                    ->where('id_user',$req->xct)
                    ->update([
                        "khoa_acxz" => 1,
                    ]);

        return 1;
    }

    public function khoi_phuc_nhanvien_admin(Request $req){
        $user = DB::table('users')
                    ->where('id',$req->xct)
                    ->first();
        $data = DB::table('admin')
                    ->where('id',$user->id_nhanvien)
                    ->update([
                            'deleted_at' => null,
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);
        $molai = DB::table('khoa_ac')
                    ->where('id_user',$req->xct)
                    ->update([
                        "khoa_acxz" => 1,
                    ]);

        return 1;
    }



    public function khachhang_s(){

        return view('admin.khachhang');
    }

    public function tt_khachhang(){
        $data = DB::table('khach_hang')
                    ->whereNotNull('deleted_at');
        return DataTables::of($data)
            ->addColumn('user',function($dt){

                    $user = DB::table('users')
                                ->select('username')
                                ->where('id',$dt->kh_id_user)
                                ->first();
                if($user){
                    return $user->username;
                }else{
                    return "Không thấy dữ liệu";
                }
                               
            })

            ->addColumn('actions',function($dt){
                $actions = '<button class="upadate_ac" d-id="'.$dt->kh_id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-pen-to-square"></i></button>'.'</br>'.'<button class="upadate_pass" d-id="'.$dt->kh_id.'"data-bs-placement="top" title="Đổi mật khẩu"><i class="fa-solid fa-lock"></i></button>'.'</br>'.'<button class="delete_ac" d-id="'.$dt->kh_id.'"><i class="fa-solid fa-trash-can" ></i></button>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function nhanhang(){

        return view("admin.nhanhang");
    }

    // Đăng nhập

    public function check_ac(Request $req){
        
        $credentials = $req->only('email', 'password');
        $sdt = $req->email;
        $user = DB::table('users')
                    ->where('email',$sdt)
                    ->first();
        if($user){
            $khoa = DB::table('khoa_ac')
                    ->where('id_user',$user->id)
                    ->first();
        }
        

        
        if (Auth::attempt($credentials)) {
        
            if($khoa->khoa_acxz == 0){
                return redirect()->back()->with(["error" => "Tài khoản của bạn đã bị khóa"]);
            }else{
                return redirect()->route('bieudo');
            }
            
        }
        // $infor = "Số điện thoại hoặc mật khẩ"
        return redirect()->back()->with(["error" => "Mật khẩu không đúng"]);
    }

    public function logout(){
            Auth::logout();
        return redirect()->route('dang_nhap');
    }
    public function test(Request $req){

        echo(Hash::make('123'));
    }

    // Đăng ký

    public function dangky_tc(Request $req){
        $check_user = DB::table('users')
                        ->select('email')
                        ->get();
        $hasUser = $check_user->contains('email', $req->sdt);
        if($hasUser){
            return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
        }
        if($req->matkhau == $req->matkhau_nl){
            $khachhang = DB::table('khach_hang')
                        ->insertGetid([
                            "kh_ten"      => $req->name_ac,
                            "kh_Address"  => $req->address,
                        ]);
             $user = DB::table('users')
                        ->insertGetid([
                                "email"      => $req->sdt,
                                "password"      => Hash::make($req->matkhau),  
                                "id_nhanvien"      => $khachhang,  
                                "permissions"      => 1,  

                        ]);
             $check_ac = DB::table('khoa_ac')
                            ->insert([
                                "id_user" => $user,
                                "khoa_acxz" => 1,
                            ]);
             return redirect()->route('logout')->with(["sucess" => "Đăng kí thành công"]);
        }else{
            return redirect()->back()->with(["error" => "Mật khẩu không khớp"]);
        }
    }

    public function capnhatdonhang_hk(Request $req){
        if(Auth::user()->permissions == 3){
            $data = DB::table('nh_khachhang')
                    ->where('check',1)
                    ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                    ->where('nh_trangthai','chờ')
                    ->orderBy('nh_created_at','desc');
            return DataTables::of($data)
                ->addColumn('actions',function($dt){
                    $action = '<button class="complet" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-truck-moving" data-bs-placement="top" title="Giao hàng"></i></button>'.'</br>'.'<button class="delete_inf" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-trash-can"></i></button>';
                        return $action;
                    return $action;
                }) 
                ->addColumn('sodienthoai', function($dt){
                    $sodienthoai = DB::table('users')
                                ->select('email')
                                ->where('id',$dt->nh_id_user)
                                ->first();

                    if($sodienthoai){
                        return $sodienthoai->email;
                    }else{
                        return '';
                    }
                })
                ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })

                ->addColumn('tenkhachhang', function($dt){
                    $udd = DB::table('users')
                                ->select('id_nhanvien')
                                ->where('id',$dt->nh_id_user)
                                ->first();
                        if($udd){
                          $tenkhachhang = DB::table('khach_hang')
                            ->select('kh_ten')
                            ->where('kh_id',$udd->id_nhanvien)
                            ->first();

                            if($tenkhachhang){
                                return $tenkhachhang->kh_ten;
                            }else{
                                return '';
                            }  
                        }else{
                            return '';
                        }
                })
                ->addColumn('name_kh', function($dt){
                    $name = DB::table('khach_hang')
                                ->select('kh_ten')
                                ->where('kh_id',$dt->nh_id_khachhang)
                                ->first();
                    if($name){
                        return $name->kh_ten;
                    }else{
                        return '';
                    }
                })
               
                    ->rawColumns(['actions','anhminhhoa'])
                    ->make(true);
        }else if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
                 $data = DB::table('nh_khachhang')
                    ->where('check',1)
                    ->where('nh_trangthai','chờ')
                    ->orderBy('nh_created_at','desc');
                return DataTables::of($data)
                    ->addColumn('actions',function($dt){
                        $action = '<button class="complet" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-truck-moving" data-bs-placement="top" title="Nhận hàng"></i></button>'.'</br>'.'<button class="delete_inf" dt-id="'.$dt->nh_id.'"><i class="fa-solid fa-trash-can"></i></button>'.'</br>'.'<a class="upadate_ac" href="'.route('edit_donhang',$dt->nh_id).'" d-id="'.$dt->nh_id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-pen-to-square"></i></a>';
                        return $action;
                    })
                    ->addColumn('sodienthoai', function($dt){
                        $sodienthoai = DB::table('users')
                                    ->select('email')
                                    ->where('id',$dt->nh_id_user)
                                    ->first();

                        if($sodienthoai){
                            return $sodienthoai->email;
                        }else{
                            return '';
                        }
                    })
                    ->addColumn('anhminhhoa',function($dt){
                        if($dt->nh_anhminhhoa != ''){
                            return '<a href="'.$dt->nh_anhminhhoa.'" target="_blank"><img src="' . $dt->nh_anhminhhoa . '" width="70%" alt="lỗi"/></a>';
                        }else{
                            return "";
                        }
                    })

                    ->addColumn('tenkhachhang', function($dt){
                        $udd = DB::table('users')
                                ->select('id_nhanvien')
                                ->where('id',$dt->nh_id_user)
                                ->first();
                        if($udd){
                          $tenkhachhang = DB::table('khach_hang')
                            ->select('kh_ten')
                            ->where('kh_id',$udd->id_nhanvien)
                            ->first();

                            if($tenkhachhang){
                                return $tenkhachhang->kh_ten;
                            }else{
                                return '';
                            }  
                        }else{
                            return '';
                        }
                                
                        
                    })
                    ->addColumn('name_kh', function($dt){
                        $name = DB::table('khach_hang')
                                    ->select('kh_ten')
                                    ->where('kh_id',$dt->nh_id_khachhang)
                                    ->first();
                        if($name){
                            return $name->kh_ten;
                        }else{
                            return '';
                        }
                    })
                
                    ->rawColumns(['actions','anhminhhoa'])
                    ->make(true);
        }
        
    }

    public function nhanhangup(Request $req){
        $save = DB::table('nh_khachhang')
                    ->where('nh_id',$req->id_nh)
                    ->update([
                        'nh_created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'nh_trangthai' => 'duyệt',
                    ]);
        return 1;
    }

    public function huydon_kh(Request $req){

        $update = DB::table('nh_khachhang')
                            ->where('nh_id',$req->id_nh)
                            ->update([
                                    'nh_phanhoi' => $req->phanhoi,
                                    'nh_trangthai' => 'xóa',
                                    'nh_deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            ]);
        return 1;
    }

    public function nvgh(){

        return view('admin.nvgh');
    }

    public function tt_nvgh(){
        $data = DB::table('nhanvien_giaohang')
                    ->where('deleted_at',null)
                    ->orderBy('created_at','desc');
        return DataTables::of($data)

                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->gh_id)
                                ->where('permissions',3)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })
                ->addColumn('anhcmt',function($dt){
                    $nhanvien = '<a href="'.$dt->gh_cmt.'" target="_blank"><img src="'.$dt->gh_cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    
                })
                ->addColumn('actions',function($dt){
                    $actions = '<a class="upadate_ac" href="'.route('edit_tk_gh',$dt->gh_id).'" d-id="'.$dt->gh_id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-pen-to-square"></i></a>'.'</br>'.'<button class="upadate_pass" d-id="'.$dt->gh_id.'"data-bs-placement="top" title="Đổi mật khẩu"><i class="fa-solid fa-lock"></i></button>'.'</br>'.'<button class="delete_ac" d-id="'.$dt->gh_id.'"><i class="fa-solid fa-trash-can" data-bs-placement="top" title="Khóa tài khoản"></i></button>';
                    return $actions;
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function khachhang(){

        return view('admin.ac_khachhang');
    }





    public function ac_admin(){

        return view('admin.ac_admin');
    }


    public function tt_admin(){
        $data = DB::table('admin')
                    ->where('deleted_at',null)
                    ->orderBy('created_at','desc');
        return DataTables::of($data)

                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->id)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })
               ->addColumn('anhcmt',function($dt){
                    $nhanvien = '<a href="'.$dt->cmt.'" target="_blank"><img src="'.$dt->cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    
                })
                ->addColumn('actions',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->id)
                                ->first();
                    if($sdt->email != '0368374871'){
                        $actions = '<a class="upadate_ac" href="'.route('edit_tk_admin',$dt->id).'" d-id="'.$dt->id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-pen-to-square"></i></a>'.'</br>'.'<button class="upadate_pass" d-id="'.$dt->id.'"data-bs-placement="top" title="Đổi mật khẩu"><i class="fa-solid fa-lock"></i></button>'.'</br>'.'<button class="delete_ac" d-id="'.$dt->id.'"><i class="fa-solid fa-trash-can"></i></button>';
                        return $actions;
                    }else{
                        return "";
                    }
                    
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function name_ac(Request $req){

        $save = DB::table('nha');

        return $req->id;
    }

    public function themtaikhoan(Request $req){

         return view('admin.add_taikhoan');
    }

    public function themtaikhoanmoi(Request $req){
                    
        $check_user = DB::table('users')
            ->select('email')
            ->get();
        $hasUser = $check_user->contains('email', $req->sdt);
        if($hasUser){
            return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
        }

        if($req->passwork == $req->passwork_nl){

            if(isset($_FILES['anhminhhoa'])){

                //Thư mục bạn lưu file upload
                $target_dir = "uploads/";
                //Đường dẫn lưu file trên server
                $randomString = bin2hex(random_bytes(5)); 
                $target_file   = $target_dir .$randomString. basename($_FILES["anhminhhoa"]["name"]);                
                $allowUpload   = true;

                //Lấy phần mở rộng của file (txt, jpg, png,...)
                $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
                //Những loại file được phép upload
                $allowtypes    = array('txt', 'dat', 'data');
                //Kích thước file lớn nhất được upload (bytes)
                $maxfilesize   = 10000000;//10MB

                //1. Kiểm tra file có bị lỗi không?
                if ($_FILES["anhminhhoa"]['error'] != 0) {
                    echo "<br>The uploaded file is error or no file selected.";
                    die;
                }

                //2. Kiểm tra loại file upload có được phép không?
                // if (!in_array($fileType, $allowtypes )) {
                //     echo "<br>Only allow for uploading .txt, .dat or .data files.";
                //     $allowUpload = false;
                // }
                
                //3. Kiểm tra kích thước file upload có vượt quá giới hạn cho phép
                if ($_FILES["anhminhhoa"]["size"] > $maxfilesize) {
                    echo "<br>Size of the uploaded file must be smaller than $maxfilesize bytes.";
                    $allowUpload = false;
                }

                //4. Kiểm tra file đã tồn tại trên server chưa?
                // if (file_exists($target_file)) {
                //     echo "<br>The file name already exists on the server.";
                //     $allowUpload = false;
                // }

                if ($allowUpload) {
                    $name_nv = $req->name_nv;
                    //Lưu file vào thư mục được chỉ định trên server

                    if (move_uploaded_file($_FILES["anhminhhoa"]["tmp_name"], $target_file)) {
                        
                        // echo "<br>File ". basename( $_FILES["anhminhhoa"]["name"])." uploaded successfully.";
                        // echo "The file saved at " . $target_file;
                        $path = URL::to('/') . '/' . $target_file;

                        if($req->loainv == 'ADMIN'){
                            $data = [
                                    'ten_admin' => $name_nv,
                                    'ma_admin' => $req->ma_nv,
                                    'ngay_sinh' => $req->birth,
                                    'gioi_tinh' => $req->gender,
                                    'dia_chi' => $req->address,
                                    'cmt' => $path,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];

                            $save = DB::table('admin')
                                    ->insertGetid($data);
                            
                            $save_user = DB::table('users')
                                ->insertGetid([
                                    'email' => $req->sdt,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save,
                                    'permissions' => 4,
                                ]); 
                            $khoa_ac = DB::table('khoa_ac')
                                        ->insert([
                                            "khoa_acxz" => 1,
                                            "id_user" => $save_user,
                                        ]);  
                            return redirect()->route('ac_admin')->with('key', 'Bạn đã tạo tài khoản thành công');
                        }else if($req->loainv == 'NVTDKH'){

                            $data = [
                                    'ten_nhanvien' => $name_nv,
                                    'ma_nhanvien' => $req->ma_nv,
                                    'ngay_sinh' => $req->birth,
                                    'gioi_tinh' => $req->gender,
                                    'dia_chi' => $req->address,
                                    'anh_cmt' => $path,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];


                            $save = DB::table('nhan_vien')
                                    ->insertGetid($data);
                            
                            $save_user = DB::table('users')
                                ->insertGetid([
                                    'email' => $req->sdt,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save,
                                    'permissions' => 2,
                                ]); 
                            $khoa_ac = DB::table('khoa_ac')
                                        ->insert([
                                            "khoa_acxz" => 1,
                                            "id_user" => $save_user,
                                        ]);
                            return redirect()->route('create_account')->with('key', 'Bạn đã tạo tài khoản thành công');
                        }else if($req->loainv == 'NVGH'){
                            $data = [
                                    'gh_tennvgh' => $name_nv,
                                    'gh_manvgh' => $req->ma_nv,
                                    'gh_ngaysinh' => $req->birth,
                                    'gh_gioitinh' => $req->gender,
                                    'gh_diachigh' => $req->address,
                                    'gh_cmt' => $path,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];


                            $save = DB::table('nhanvien_giaohang')
                                    ->insertGetid($data);
                         
                            $save_user = DB::table('users')
                                ->insertGetid([
                                    'email' => $req->sdt,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save,
                                    'permissions' => 3,
                                ]); 
                            $khoa_ac = DB::table('khoa_ac')
                                        ->insert([
                                            "khoa_acxz" => 1,
                                            "id_user" => $save_user,
                                        ]);
                            return redirect()->route('nvgh')->with('key', 'Bạn đã tạo tài khoản thành công');
                        }else{

                            $data = [
                                    'ma_kh' => $req->ma_nv,
                                    'ngay_sinh' => $req->birth,
                                    'kh_gender' => $req->gender,
                                    'cmt' => $path,
                                    'kh_ten' => $name_nv,
                                    'kh_Address' => $req->address,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'kh_created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];


                            $save = DB::table('khach_hang')
                                    ->insertGetid($data);
                            
                            $save_user = DB::table('users')
                                ->insertGetid([
                                    'email' => $req->sdt,
                                    'password' => Hash::make($req->passwork),
                                    'id_nhanvien' => $save,
                                    'permissions' => 1,
                                ]); 
                            $khoa_ac = DB::table('khoa_ac')
                                        ->insert([
                                            "khoa_acxz" => 1,
                                            "id_user" => $save_user,
                                        ]);
                            return redirect()->route('khachhang')->with('key', 'Bạn đã tạo tài khoản thành công');
                        }
                    }        

                } 
            }
        }else{
            return redirect()->back()->with(["error" => "Mật khẩu không khớp"]);
        }
    }
             
    public function edit_tk(Request $req){
        $ac = DB::table('nhan_vien')
                ->select('nhan_vien.*','users.id as id_user','users.email','users.password')
                ->leftjoin('users','users.id_nhanvien','=','nhan_vien.id')
                ->where('users.permissions',2)
                ->where('nhan_vien.id',$req->id)
                ->first();

        // var_dump($ac);die;
        return view('admin.edit_tk')
                    ->with([
                        "user"  => $ac,
                    ]);
    }


    public function edit_tk_gh(Request $req){
        $ac = DB::table('nhanvien_giaohang')
                ->select('nhanvien_giaohang.*','users.id as id_user','users.email','users.password')
                ->leftjoin('users','users.id_nhanvien','=','nhanvien_giaohang.gh_id')
                ->where('users.permissions',3)
                ->where('nhanvien_giaohang.gh_id',$req->id)
                ->first();

        // var_dump($ac);die;
        return view('admin.edit_tk_gh')
                    ->with([
                        "user"  => $ac,
                    ]);
    }

    public function edit_tk_kh(Request $req){
        $ac = DB::table('khach_hang')
                ->select('khach_hang.*','users.id as id_user','users.email','users.password')
                ->leftjoin('users','users.id_nhanvien','=','khach_hang.kh_id')
                ->where('users.permissions',1)
                ->where('khach_hang.kh_id',$req->id)
                ->first();

        // var_dump($ac);die;
        return view('admin.edit_tk_kh')
                    ->with([
                        "user"  => $ac,
                    ]);
    }

    public function edit_tk_admin(Request $req){
        $ac = DB::table('admin')
                ->select('admin.*','users.id as id_user','users.email','users.password')
                ->leftjoin('users','users.id_nhanvien','=','admin.id')
                ->where('users.permissions',4)
                ->where('admin.id',$req->id)
                ->first();

        // var_dump($ac);die;
        return view('admin.edit_tk_admin')
                    ->with([
                        "user"  => $ac,
                    ]);
    }

    public function updatetaikhoanmoi(Request $req){

        $check_user = DB::table('users')
            ->select('email')
            ->get();
        $hasUser = $check_user->contains('email', $req->sdt);
        // if($hasUser){
        //     return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
        // }

            

            if(isset($_FILES['anhminhhoa'])){

                //Thư mục bạn lưu file upload
                $target_dir = "uploads/";
                //Đường dẫn lưu file trên server
                $target_file   = $target_dir . basename($_FILES["anhminhhoa"]["name"]);                
                $allowUpload   = true;

                //Lấy phần mở rộng của file (txt, jpg, png,...)
                $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
                //Những loại file được phép upload
                $allowtypes    = array('txt', 'dat', 'data');
                //Kích thước file lớn nhất được upload (bytes)
                $maxfilesize   = 10000000;//10MB

                //1. Kiểm tra file có bị lỗi không?
                if ($_FILES["anhminhhoa"]['error'] != 0) {

                    

                        $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                        $data = [
                                'ten_nhanvien' => $req->name_nv,
                                'ma_nhanvien' => $req->ma_nv,
                                'ngay_sinh' => $req->birth,
                                'gioi_tinh' => $req->gender,
                                'dia_chi' => $req->address,
                                // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            ];


                        $save = DB::table('nhan_vien')
                                ->where('id',$req->id_nv)
                                ->update($data);
                        
                        if($user_id->email != $req->sdt){
                           if($hasUser){
                                return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                            }
                            $save_user = DB::table('users')
                                ->where('id',$req->id_user)
                                ->update([
                                    'email' => $req->sdt,
                            ]);
                        }
                         
                        
                        return redirect()->route('create_account')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                    
                }

                //2. Kiểm tra loại file upload có được phép không?
                // if (!in_array($fileType, $allowtypes )) {
                //     echo "<br>Only allow for uploading .txt, .dat or .data files.";
                //     $allowUpload = false;
                // }
                
                //3. Kiểm tra kích thước file upload có vượt quá giới hạn cho phép
                if ($_FILES["anhminhhoa"]["size"] > $maxfilesize) {
                    echo "<br>Size of the uploaded file must be smaller than $maxfilesize bytes.";
                    $allowUpload = false;
                }

                //4. Kiểm tra file đã tồn tại trên server chưa?
                // if (file_exists($target_file)) {
                //     echo "<br>The file name already exists on the server.";
                //     $allowUpload = false;
                // }

                if ($allowUpload) {
                    $name_nv = $req->name_nv;
                    //Lưu file vào thư mục được chỉ định trên server

                    if (move_uploaded_file($_FILES["anhminhhoa"]["tmp_name"], $target_file)) {
                        
                        // echo "<br>File ". basename( $_FILES["anhminhhoa"]["name"])." uploaded successfully.";
                        // echo "The file saved at " . $target_file;
                        $path = URL::to('/') . '/' . $target_file;

                        
                            $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                            $data = [
                                    'ten_nhanvien' => $name_nv,
                                    'ma_nhanvien' => $req->ma_nv,
                                    'ngay_sinh' => $req->birth,
                                    'gioi_tinh' => $req->gender,
                                    'dia_chi' => $req->address,
                                    'anh_cmt' => $path,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];

                            $save = DB::table('nhan_vien')
                                ->where('id',$req->id_nv)
                                ->update($data);
                        
                            if($user_id->email != $req->sdt){
                                if($hasUser){
                                    return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                                }
                                $save_user = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->update([
                                        'email' => $req->sdt,
                                ]);
                            }
                            return redirect()->route('create_account')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                        
                }
            }
        }
    }

    public function updatetaikhoanmoi_gh(Request $req){

        $check_user = DB::table('users')
            ->select('email')
            ->get();
        $hasUser = $check_user->contains('email', $req->sdt);

            if(isset($_FILES['anhminhhoa'])){
                $randomString = bin2hex(random_bytes(5)); 
                //Thư mục bạn lưu file upload
                $target_dir = "uploads/";
                //Đường dẫn lưu file trên server
                $target_file   = $target_dir .$randomString. basename($_FILES["anhminhhoa"]["name"]);                
                $allowUpload   = true;

                //Lấy phần mở rộng của file (txt, jpg, png,...)
                $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
                //Những loại file được phép upload
                $allowtypes    = array('txt', 'dat', 'data');
                //Kích thước file lớn nhất được upload (bytes)
                $maxfilesize   = 10000000;//10MB

                //1. Kiểm tra file có bị lỗi không?
                if ($_FILES["anhminhhoa"]['error'] != 0) {

                 

                        $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                        // echo ($req->id_nv);die;
                        $data = [
                                'gh_tennvgh' => $req->name_nv,
                                'gh_manvgh' => $req->ma_nv,
                                'gh_ngaysinh' => $req->birth,
                                'gh_gioitinh' => $req->gender,
                                'gh_diachigh' => $req->address,
                                // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            ];


                        $save = DB::table('nhanvien_giaohang')
                                ->where('gh_id',$req->id_nv)
                                ->update($data);
                        
                        if($user_id->email != $req->sdt){
                            if($hasUser){
                                return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                            }
                            $save_user = DB::table('users')
                                ->where('id',$req->id_user)
                                ->update([
                                    'email' => $req->sdt,
                                   
                            ]);
                        }
                         
                        
                        return redirect()->route('nvgh')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                    
                }

                //2. Kiểm tra loại file upload có được phép không?
                // if (!in_array($fileType, $allowtypes )) {
                //     echo "<br>Only allow for uploading .txt, .dat or .data files.";
                //     $allowUpload = false;
                // }
                
                //3. Kiểm tra kích thước file upload có vượt quá giới hạn cho phép
                if ($_FILES["anhminhhoa"]["size"] > $maxfilesize) {
                    echo "<br>Size of the uploaded file must be smaller than $maxfilesize bytes.";
                    $allowUpload = false;
                }

                //4. Kiểm tra file đã tồn tại trên server chưa?
                // if (file_exists($target_file)) {
                //     echo "<br>The file name already exists on the server.";
                //     $allowUpload = false;
                // }

                if ($allowUpload) {
                    $name_nv = $req->name_nv;
                    //Lưu file vào thư mục được chỉ định trên server

                    if (move_uploaded_file($_FILES["anhminhhoa"]["tmp_name"], $target_file)) {
                        
                        // echo "<br>File ". basename( $_FILES["anhminhhoa"]["name"])." uploaded successfully.";
                        // echo "The file saved at " . $target_file;

                        $path = URL::to('/') . '/' . $target_file;

                       
                            $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                            $data = [
                                    'gh_tennvgh' => $req->name_nv,
                                    'gh_manvgh' => $req->ma_nv,
                                    'gh_ngaysinh' => $req->birth,
                                    'gh_gioitinh' => $req->gender,
                                    'gh_diachigh' => $req->address,
                                    'gh_cmt' => $path,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];


                        
                            $save = DB::table('nhanvien_giaohang')
                                ->where('gh_id',$req->id_nv)
                                ->update($data);
                        
                            if($user_id->email != $req->sdt){
                                if($hasUser){
                                    return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                                }
                                $save_user = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->update([
                                        'email' => $req->sdt,
                                ]);
                            }
                            
                            return redirect()->route('nvgh')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                              
                    }
                }   
            }
    }

    public function updatetaikhoanmoi_kh(Request $req){

        $check_user = DB::table('users')
            ->select('email')
            ->get();
        $hasUser = $check_user->contains('email', $req->sdt);

            if(isset($_FILES['anhminhhoa'])){
                $randomString = bin2hex(random_bytes(5)); 
                //Thư mục bạn lưu file upload
                $target_dir = "uploads/";
                //Đường dẫn lưu file trên server
                $target_file   = $target_dir .$randomString. basename($_FILES["anhminhhoa"]["name"]);                
                $allowUpload   = true;

                //Lấy phần mở rộng của file (txt, jpg, png,...)
                $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
                //Những loại file được phép upload
                $allowtypes    = array('txt', 'dat', 'data');
                //Kích thước file lớn nhất được upload (bytes)
                $maxfilesize   = 10000000;//10MB

                //1. Kiểm tra file có bị lỗi không?
                if ($_FILES["anhminhhoa"]['error'] != 0) {

                        $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                        // echo ($req->id_nv);die;
                        $data = [
                                'kh_ten' => $req->name_nv,
                                'ma_kh' => $req->ma_nv,
                                'ngay_sinh' => $req->birth,
                                'kh_gender' => $req->gender,
                                'kh_Address' => $req->address,
                                // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                'kh_created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            ];


                        $save = DB::table('khach_hang')
                                ->where('kh_id',$req->id_nv)
                                ->update($data);
                        
                        if($user_id->email != $req->sdt){
                            if($hasUser){
                                return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                            }
                            $save_user = DB::table('users')
                                ->where('id',$req->id_user)
                                ->update([
                                    'email' => $req->sdt,
                            ]);
                        }

                        return redirect()->route('khachhang')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                    }
                

                //2. Kiểm tra loại file upload có được phép không?
                // if (!in_array($fileType, $allowtypes )) {
                //     echo "<br>Only allow for uploading .txt, .dat or .data files.";
                //     $allowUpload = false;
                // }
                
                //3. Kiểm tra kích thước file upload có vượt quá giới hạn cho phép
                if ($_FILES["anhminhhoa"]["size"] > $maxfilesize) {
                    echo "<br>Size of the uploaded file must be smaller than $maxfilesize bytes.";
                    $allowUpload = false;
                }

                //4. Kiểm tra file đã tồn tại trên server chưa?
                // if (file_exists($target_file)) {
                //     echo "<br>The file name already exists on the server.";
                //     $allowUpload = false;
                // }

                if ($allowUpload) {
                    $name_nv = $req->name_nv;
                    //Lưu file vào thư mục được chỉ định trên server

                    if (move_uploaded_file($_FILES["anhminhhoa"]["tmp_name"], $target_file)) {
                        
                        // echo "<br>File ". basename( $_FILES["anhminhhoa"]["name"])." uploaded successfully.";
                        // echo "The file saved at " . $target_file;

                        $path = URL::to('/') . '/' . $target_file;

                       
                            $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                            $data = [
                                    'kh_ten' => $req->name_nv,
                                    'ma_kh' => $req->ma_nv,
                                    'ngay_sinh' => $req->birth,
                                    'kh_gender' => $req->gender,
                                    'kh_Address' => $req->address,
                                    'cmt' => $path,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'kh_created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];


                        
                            $save = DB::table('khach_hang')
                                ->where('kh_id',$req->id_nv)
                                ->update($data);
                        
                            if($user_id->email != $req->sdt){
                                if($hasUser){
                                    return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                                }
                                $save_user = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->update([
                                        'email' => $req->sdt,
                                ]);
                            }
                            
                            return redirect()->route('khachhang')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                            
                    }
                }   
            }
    }

    public function updatetaikhoanmoi_admin(Request $req){

        $check_user = DB::table('users')
            ->select('email')
            ->get();
        $hasUser = $check_user->contains('email', $req->sdt);

            if(isset($_FILES['anhminhhoa'])){
                $randomString = bin2hex(random_bytes(5)); 
                //Thư mục bạn lưu file upload
                $target_dir = "uploads/";
                //Đường dẫn lưu file trên server
                $target_file   = $target_dir .$randomString. basename($_FILES["anhminhhoa"]["name"]);                
                $allowUpload   = true;

                //Lấy phần mở rộng của file (txt, jpg, png,...)
                $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
                //Những loại file được phép upload
                $allowtypes    = array('txt', 'dat', 'data');
                //Kích thước file lớn nhất được upload (bytes)
                $maxfilesize   = 10000000;//10MB

                //1. Kiểm tra file có bị lỗi không?
                if ($_FILES["anhminhhoa"]['error'] != 0) {

                        $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                        // echo ($req->id_nv);die;
                        $data = [
                                'ten_admin' => $req->name_nv,
                                'ma_admin' => $req->ma_nv,
                                'ngay_sinh' => $req->birth,
                                'gioi_tinh' => $req->gender,
                                'dia_chi' => $req->address,
                                // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            ];


                        $save = DB::table('admin')
                                ->where('id',$req->id_nv)
                                ->update($data);
                        
                        if($user_id->email != $req->sdt){
                            if($hasUser){
                                return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                            }
                            $save_user = DB::table('users')
                                ->where('id',$req->id_user)
                                ->update([
                                    'email' => $req->sdt,
                            ]);
                        }

                        return redirect()->route('ac_admin')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                    }
                

                //2. Kiểm tra loại file upload có được phép không?
                // if (!in_array($fileType, $allowtypes )) {
                //     echo "<br>Only allow for uploading .txt, .dat or .data files.";
                //     $allowUpload = false;
                // }
                
                //3. Kiểm tra kích thước file upload có vượt quá giới hạn cho phép
                if ($_FILES["anhminhhoa"]["size"] > $maxfilesize) {
                    echo "<br>Size of the uploaded file must be smaller than $maxfilesize bytes.";
                    $allowUpload = false;
                }

                //4. Kiểm tra file đã tồn tại trên server chưa?
                // if (file_exists($target_file)) {
                //     echo "<br>The file name already exists on the server.";
                //     $allowUpload = false;
                // }

                if ($allowUpload) {
                    $name_nv = $req->name_nv;
                    //Lưu file vào thư mục được chỉ định trên server

                    if (move_uploaded_file($_FILES["anhminhhoa"]["tmp_name"], $target_file)) {
                        
                        // echo "<br>File ". basename( $_FILES["anhminhhoa"]["name"])." uploaded successfully.";
                        // echo "The file saved at " . $target_file;

                        $path = URL::to('/') . '/' . $target_file;

                       
                            $user_id = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->first();
                            $data = [
                                    'ten_admin' => $req->name_nv,
                                    'ma_admin' => $req->ma_nv,
                                    'ngay_sinh' => $req->birth,
                                    'gioi_tinh' => $req->gender,
                                    'dia_chi' => $req->address,
                                    'cmt' => $path,
                                    // 'deleted_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                                ];


                        
                            $save = DB::table('admin')
                                ->where('id',$req->id_nv)
                                ->update($data);
                        
                            if($user_id->email != $req->sdt){
                                if($hasUser){
                                    return redirect()->back()->with(["error" => "Số điện thoại này đã tồn tại"]);
                                }
                                $save_user = DB::table('users')
                                    ->where('id',$req->id_user)
                                    ->update([
                                        'email' => $req->sdt,
                                ]);
                            }
                            
                            return redirect()->route('ac_admin')->with('key', 'Bạn đã cập nhật tài khoản thành công');
                            
                    }
                }   
            }
    }
    public function tt_khachhang_ac(){
        $data = DB::table('khach_hang')
                    ->where('deleted_at',null)
                    ->orderBy('kh_created_at','desc');
        return DataTables::of($data)

                ->addColumn('sdt',function($dt){
                    $sdt = DB::table('users')
                                ->select('email')
                                ->where('id_nhanvien',$dt->kh_id)
                                ->where('permissions',1)
                                ->first();
                    if($sdt){
                        return $sdt->email;
                    }else{
                        return 'Không có dữ liệu';
                    }
                })
                ->addColumn('anhcmt',function($dt){
                    if($dt->cmt){
                        $nhanvien = '<a href="'.$dt->cmt.'" target="_blank"><img src="'.$dt->cmt.'" alt="lỗi" width="50%"></a>';
                        return $nhanvien;
                    }else{
                        return "Không có dữ liệu";
                    }
                    
                    
                })
                 ->addColumn('ngaysinh',function($dt){
                    if($dt->ngay_sinh){
                        return $dt->ngay_sinh;
                    }else{
                        return "Không có dữ liệu";
                    }
                    
                    
                })
                 ->addColumn('gioitinh',function($dt){
                    if($dt->kh_gender){
                        return $dt->kh_gender;
                    }else{
                        return "Không có dữ liệu";
                    }
   
                })
                 ->addColumn('ma_khachang',function($dt){
                    if($dt->ma_kh){
                        return $dt->ma_kh;
                    }else{
                        return "Không có dữ liệu";
                    }
                })
                ->addColumn('actions',function($dt){
                    $actions = '<a class="upadate_ac" href="'.route('edit_tk_kh',$dt->kh_id).'" d-id="'.$dt->kh_id.'"data-bs-placement="top" title="Cập nhật"><i class="fa-solid fa-pen-to-square"></i></a>'.'</br>'.'<button class="upadate_pass" d-id="'.$dt->kh_id.'"data-bs-placement="top" title="Đổi mật khẩu"><i class="fa-solid fa-lock"></i></button>'.'</br>'.'<button class="delete_ac" d-id="'.$dt->kh_id.'"><i class="fa-solid fa-trash-can" data-bs-placement="top" title="Khóa tài khoản"></i></button>';
                    return $actions;
                })
                ->rawColumns(['actions','anhcmt'])
                ->make(true);
    }

    public function tuychonthoigian(Request $req){
        $array = array();
        $start_time = Carbon::parse($req->tungay)->startOfDay();
        $end_time = Carbon::parse($req->denngay)->endOfDay();
        
        if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4){
            $donhang_tc = DB::table('nh_khachhang')
                    ->where('nh_trangthai','thành công')
                    ->whereBetween('nh_updated_at', [$start_time, $end_time])
                    ->count();
            $soluong_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->sum('nh_soluong');
            $tiendo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien = 0;
            foreach($tiendo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien += $t;
            }            
            $tien = number_format($tien);

            $donhang_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->count();
            $soluong_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->sum('nh_soluong');
            $tien_dgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_dg = 0;
            foreach($tien_dgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_dg += $t;
            }
            $tien_dg = number_format($tien_dg);

            $phivanchuyen_ghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_gh = 0;
            foreach($phivanchuyen_ghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_gh += $t;
            }
            $phivanchuyen_gh = number_format($phivanchuyen_gh);
            $phivanchuyen_hddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_hd = 0;
            foreach($phivanchuyen_hddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hd += $t;
            }
            $phivanchuyen_hd = number_format($phivanchuyen_hd);

            $array[0]['tc_dh'] =  $donhang_tc;
            $array[1]['tc_sp'] =  $soluong_tc;
            $array[2]['tc_tien'] = $tien;
            $array[3]['dg_dh'] =  $donhang_dg;
            $array[4]['dg_sl'] =  $soluong_dg;
            $array[5]['dg_tien'] =  $tien_dg;
            $array[6]['phigh'] =  $phivanchuyen_gh;
            $array[7]['phihd'] =  $phivanchuyen_hd;
            return $array;
        }else if(Auth::user()->permissions == 3){
            $donhang_tc = DB::table('nh_khachhang')
                    ->where('nh_trangthai','thành công')
                    ->whereBetween('nh_updated_at', [$start_time, $end_time])
                    ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                    ->count();
            $soluong_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');
            $tiendo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien = 0;
            foreach($tiendo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien += $t;
            }            
            $tien = number_format($tien);

            $donhang_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->count();
            $soluong_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->sum('nh_soluong');
            $tien_dgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_dg = 0;
            foreach($tien_dgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_dg += $t;
            }
            $tien_dg = number_format($tien_dg);

            $phivanchuyen_ghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_gh = 0;
            foreach($phivanchuyen_ghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_gh += $t;
            }
            $phivanchuyen_gh = number_format($phivanchuyen_gh);
            $phivanchuyen_hddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('id_nhanviengh',Auth::user()->id_nhanvien)
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_hd = 0;
            foreach($phivanchuyen_hddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hd += $t;
            }
            $phivanchuyen_hd = number_format($phivanchuyen_hd);

            $array[0]['tc_dh'] =  $donhang_tc;
            $array[1]['tc_sp'] =  $soluong_tc;
            $array[2]['tc_tien'] = $tien;
            $array[3]['dg_dh'] =  $donhang_dg;
            $array[4]['dg_sl'] =  $soluong_dg;
            $array[5]['dg_tien'] =  $tien_dg;
            $array[6]['phigh'] =  $phivanchuyen_gh;
            $array[7]['phihd'] =  $phivanchuyen_hd;
            return $array;
        }else{
            $donhang_tc = DB::table('nh_khachhang')
                    ->where('nh_trangthai','thành công')
                    ->whereBetween('nh_updated_at', [$start_time, $end_time])
                    ->where('nh_id_user','=',Auth::user()->id)
                    ->count();
            $soluong_tc = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->sum('nh_soluong');
            $tiendo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','thành công')
                        ->whereBetween('nh_updated_at', [$start_time, $end_time])
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien = 0;
            foreach($tiendo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien += $t;
            }            
            $tien = number_format($tien);

            $donhang_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->count();
            $soluong_dg = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->sum('nh_soluong');
            $tien_dgdo = DB::table('nh_khachhang')
                        ->where('nh_trangthai','duyệt')
                        ->where('nh_id_user','=',Auth::user()->id)
                        ->get();
                        // ->sum('nh_tienthuho');
            $tien_dg = 0;
            foreach($tien_dgdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_tienthuho) ; $i++){
                    if(substr($val->nh_tienthuho,$i, 1) != "."){
                        $t .= substr($val->nh_tienthuho,$i, 1);
                    }
                }
                $tien_dg += $t;
            }
            $tien_dg = number_format($tien_dg);

            $phivanchuyen_ghdo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','xóa')
                            ->where('nh_trangthai','<>','khác')
                            ->where('nh_trangthai','<>','từ chối nhận')
                            ->where('nh_trangthai','<>','không liên hệ được')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_gh = 0;
            foreach($phivanchuyen_ghdo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_gh += $t;
            }
            $phivanchuyen_gh = number_format($phivanchuyen_gh);
            $phivanchuyen_hddo = DB::table('nh_khachhang')
                            ->where('nh_trangthai','<>','chờ')
                            ->where('nh_trangthai','<>','duyệt')
                            ->where('nh_trangthai','<>','thành công')
                            ->where('nh_id_user','=',Auth::user()->id)
                            ->whereBetween('nh_updated_at', [$start_time, $end_time])
                            ->get();
                            // ->sum('nh_phiship');

            $phivanchuyen_hd = 0;
            foreach($phivanchuyen_hddo as $val){
                $t = "";
                for($i = 0; $i < strlen($val->nh_phiship) ; $i++){
                    if(substr($val->nh_phiship,$i, 1) != "."){
                        $t .= substr($val->nh_phiship,$i, 1);
                    }
                }
                $phivanchuyen_hd += $t;
            }
            $phivanchuyen_hd = number_format($phivanchuyen_hd);

            $array[0]['tc_dh'] =  $donhang_tc;
            $array[1]['tc_sp'] =  $soluong_tc;
            $array[2]['tc_tien'] = $tien;
            $array[3]['dg_dh'] =  $donhang_dg;
            $array[4]['dg_sl'] =  $soluong_dg;
            $array[5]['dg_tien'] =  $tien_dg;
            $array[6]['phigh'] =  $phivanchuyen_gh;
            $array[7]['phihd'] =  $phivanchuyen_hd;
            return $array;
        }
        
    }

    
    public function edit_donhang(Request $req){

        $user = DB::table('nh_khachhang')
                    ->where('nh_id',$req->id)
                    ->first();
        $khach_hang = DB::table('khach_hang')
                        ->where('kh_id',$user->nh_id_khachhang)
                        ->first();
        $sodienthoai = DB::table('users')
                        ->where('id',$user->nh_id_user)
                        ->first();
        return view('admin.edit_donhang')
                    ->with([
                        "user" => $user,
                        "khach_hang" => $khach_hang,
                        "sodienthoai" => $sodienthoai,
                    ]);
    }

    public function updatedonhangcn(Request $req){
        $khachhang = DB::table('nh_khachhang')
                        ->where('nh_id',$req->id_nhkh)
                        ->update([
                            'nh_tenkhachhang' => $req->address,
                            'nh_SĐT' => $req->sdt,
                            'nh_noilayhang' => $req->noilayhang,
                            'nh_diachi' => $req->dcth,
                            'nh_tenhanghoa' => $req->tenhh,
                            'nh_khoiluong' => $req->khoiluong,
                            'nh_soluong' => $req->soluong,
                            'nh_phiship' => $req->phiship,
                        ]);
        return redirect()->route('nhanhang')->with('key', 'Bạn đã cập nhật tài khoản thành công');
    }

    public function excel_donhoanthanh(){
        return Excel::download(new UsersExport, 'donhoanthanh.xlsx');
    }
    public function excel_donhoan(){
        return Excel::download(new DonhoanExport, 'donhoan.xlsx');
    }
    public function excel_donhuy(){
        return Excel::download(new DonhuyExport, 'donhuy.xlsx');
    }
    public function excel_xect(){
        return Excel::download(new XectExport, 'xecongty.xlsx');
    }
}


    
