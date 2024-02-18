@extends('admin/default')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NCKH</title>
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
</head>
<body>
	
	<style>	
		.khoi_phuc{
			border: none;
			outline: none;
			background: none;
			color: blue;
			font-size: 17px;
		}
		.dataTables_wrapper .dataTables_filter input{
			margin-bottom: 1rem;
		}
	</style>	
	<div class="list_request">
		@include("admin.menu")
	</div>
	<div class="header">
		@include("admin.sibar_heder")
	</div>
	@if(Session::get('error') != null)
        <div class="alert alert-danger" role="alert">
          <strong><i class="fa-solid fa-triangle-exclamation"></i></strong> {{Session::get('error')}}
          <a href="#" class="close" style="float: right;" data-bs-dismiss="alert" aria-label="close">&times;</a>
        </div>
    @endif
	<h3 class="text-center" style="color: #ff0018;">Cập nhật tài khoản</h3>
	

	<!-- Large modal -->
	<div class="container">
		<form id="uploadForm_ud" enctype="multipart/form-data" action="{{route('updatetaikhoanmoi')}}" method="POST">
			@csrf	
		    <div class="row">
				<input type="text" hidden name="id_nv" value="{{isset($user->id)?$user->id:''}}"> 
				<input type="text" hidden name="id_user" value="{{isset($user->id_user)?$user->id_user:''}}"> 
		      <div class="col-6">
		      	<label for="name_nv" class="btn-sm-2">Tên nhân viên</label>
		      	<input type="text" id="name_nv" name="name_nv" class="form-control" required value="{{isset($user->ten_nhanvien)?$user->ten_nhanvien:''}}">
		      </div>
		      <div class="col-6">
		      	<label for="ma_nv" class="btn-sm-2">Mã nhân viên</label>
		      	<input type="text" id="ma_nv" name="ma_nv" class="form-control" required value="{{isset($user->ma_nhanvien)?$user->ma_nhanvien:''	}}">
		      </div>
		      <div class="col-6">
		      	<label for="birth">Ngày sinh</label>
		      	<input type="date" id="birth" name="birth" class="form-control" required value="{{isset($user->ngay_sinh)?$user->ngay_sinh:''}}">
		      </div>
		      <div class="col-6">
		      	<label for="gender">Giới tính</label>
		      	<select name="gender" id="gender" class="form-control" required>
		      		<option hidden value="{{isset($user->gioi_tinh)?$user->gioi_tinh:''}}">{{isset($user->gioi_tinh)?$user->gioi_tinh:''}}</option>
		      		<option value="Nam">Nam</option>
		      		<option value="Nữ">Nữ</option>	
		      	</select>
		      </div>
		      <div class="col-6">
		      	<label for="address">Địa chỉ</label>
		      	<input type="text" name="address" id="address" class="form-control" required value="{{isset($user->dia_chi)?$user->dia_chi:''}}">
		      </div>
		      <div class="col-6">
		      	<label for="cmt">Chứng minh thư</label>
		      	<input type="file" name="anhminhhoa" id="cmt" class="form-control" >
		      </div>
		      <!-- <div class="col-6">
		      	<label for="loainv">Loại nhân viên</label>
		      
		      	<select name="loainv" id="loainv" class="form-control" required >
		      		
		      		<option value="2">NVTDKH</option>
		      		<option value="3">NVGH</option>
		      		<option value="4">ADMIN</option>
		      		<option value="1">KH</option>
		      	</select>
		      </div> -->
		      <div class="col-6">
		      	<label for="account">Số điện thoại</label>
		      
		      	<input type="text" name="sdt" class="form-control" required value="{{isset($user->email)?$user->email:''}}">
		      </div>

		     

		</div>
		<button type="submit" class="create_ac btn btn-success mt-3" style="width: 10rem;">Cập nhật</button>

		</form>
	</div>

 
	<div class="text-center">
		<a href="{{isset($user->anh_cmt)?$user->anh_cmt:''}}" target="_bank">
			<img src="{{isset($user->anh_cmt)?$user->anh_cmt:''}}" alt="lỗi" width="30%">
		</a>
	</div>

	<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>

	<script>
		

	</script>
</body>
</html>