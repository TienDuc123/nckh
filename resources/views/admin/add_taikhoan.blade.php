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
	<h3 class="text-center" style="color: #ff0018;">Thêm tài khoản</h3>
	

	<!-- Large modal -->
	<div class="container">
		<form id="uploadForm" enctype="multipart/form-data" action="{{'themtaikhoanmoi'}}" method="POST">
			@csrf	
		    <div class="row">
		
		      <div class="col-6">
		      	<label for="name_nv" class="btn-sm-2">Tên nhân viên</label>
		      	<input type="text" id="name_nv" name="name_nv" class="form-control" required>
		      </div>
		      <div class="col-6">
		      	<label for="ma_nv" class="btn-sm-2">Mã nhân viên</label>
		      	<input type="text" id="ma_nv" name="ma_nv" class="form-control" required>
		      </div>
		      <div class="col-6">
		      	<label for="birth">Ngày sinh</label>
		      	<input type="date" id="birth" name="birth" class="form-control" required>
		      </div>
		      <div class="col-6">
		      	<label for="gender">Giới tính</label>
		      	<select name="gender" id="gender" class="form-control" required>
		      		<option hidden value="">Chọn giới tính</option>
		      		<option value="Nam">Nam</option>
		      		<option value="Nữ">Nữ</option>
		      	</select>
		      </div>
		      <div class="col-6">
		      	<label for="address">Địa chỉ</label>
		      	<input type="text" name="address" id="address" class="form-control" required>
		      </div>
		      <div class="col-6">
		      	<label for="cmt">CMT</label>
		      	<input type="file" name="anhminhhoa" id="cmt" class="form-control" required>
		      </div>
		      <div class="col-6">
		      	<label for="loainv">Loại nhân viên</label>
		      	<!-- <input type="text" id="loainv" class="btn btn-info w-75"> -->
		      	<select name="loainv" id="loainv" class="form-control" required>
		      		<option value="NVTDKH">NVTDKH</option>
		      		<option value="NVGH">NVGH</option>
		      		<option value="ADMIN">ADMIN</option>
		      		<option value="KH">KH</option>
		      	</select>
		      </div>
		      <div class="col-6">
		      	<label for="account">SĐT</label>
		      
		      	<input type="text" name="sdt" class="form-control" value="" required>
		      </div>

		      <div class="col-6">
		      	<label for="passwork" style="padding: 1px 25px 1px 2px;">Mật khẩu</label>
		      	<input type="password" id="passwork" class="form-control" name="passwork" required value="">
		      </div>

		      <div class="col-6">
		      	<label for="passwork_nl" style="padding: 1px 25px 1px 2px;">Nhập lại mật khẩu</label>
		      	<input type="password" id="passwork_nl" class="form-control" name="passwork_nl">
		      </div>

		</div>
		<button class="create_ac btn btn-success mt-3" style="width: 10rem;">Thêm</button>
		<a class="create_ac btn btn-danger mt-3" style="width: 10rem;" href="{{route('create_account')}}">Đóng</a>

		</form>
	</div>

	<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>

	<script>
		

	</script>
</body>
</html>