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
	<h3 class="text-center" style="color: #ff0018;">Cập nhật đơn hàng</h3>
	

	<!-- Large modal -->
	<div class="container">
		<form id="uploadForm_ud" enctype="multipart/form-data" action="{{route('updatedonhangcn')}}" method="POST">
			@csrf	
		    <div class="row">
				<input type="text" hidden name="id_nhkh" value="{{isset($user->nh_id)?$user->nh_id:''}}"> 
				<input type="text" hidden name="id_kh" value="{{isset($khach_hang->kh_id)?$khach_hang->kh_id:''}}"> 
				<input type="text" hidden name="id_user" value="{{isset($sodienthoai->id)?$sodienthoai->id:''}}"> 
		      
		      <div class="col-6">
		      	<label for="address">Tên người nhận</label>
		      	<input type="text" name="address" id="address" class="form-control" required value="{{isset($user->nh_tenkhachhang)?$user->nh_tenkhachhang:''}}">
		      </div>
		 
		      <div class="col-6">
		      	<label for="account">Số điện thoại người nhận</label>
		      
		      	<input type="text" name="sdt" class="form-control" required value="{{isset($user->nh_SĐT)?$user->nh_SĐT:''}}">
		      </div>

		       <div class="col-6">
		      	<label for="account">Nơi lấy hàng</label>
		      
		      	<input type="text" name="noilayhang" class="form-control" required value="{{isset($user->nh_noilayhang)?$user->nh_noilayhang:''}}">
		      </div>
		       <div class="col-6">
		      	<label for="account">Địa chỉ trả hàng</label>
		      
		      	<input type="text" name="dcth" class="form-control" required value="{{isset($user->nh_diachi)?$user->nh_diachi:''}}">
		      </div>
		       <div class="col-6">
		      	<label for="account">Tên hàng hóa</label>
		      
		      	<input type="text" name="tenhh" class="form-control" required value="{{isset($user->nh_tenhanghoa)?$user->nh_tenhanghoa:''}}">
		      </div>
		       <div class="col-6">
		      	<label for="account">Khối lượng</label>
		      
		      	<input type="number" id="khoi_luong" name="khoiluong" class="form-control" required value="{{isset($user->nh_khoiluong)?$user->nh_khoiluong:''}}" onchange="phi_ship()">
		      </div>
		       <div class="col-6">
		      	<label for="account">Số lượng</label>
		      
		      	<input type="number" id="so_luong" name="soluong" class="form-control" required value="{{isset($user->nh_soluong)?$user->nh_soluong:''}}" onchange="phi_ship()">
		      </div>
		      <div class="m-2">
					<strong>Phí vận chuyển : </strong>
					<span class="phiship">0</span> đồng
					<input type="text" hidden class="phiship" name="phiship" value="">
				</div>


		     

		</div>
		<button type="submit" class="create_ac btn btn-success mt-3" style="width: 10rem;">Cập nhật</button>

		</form>
	</div>


	<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>

	<script>
		function phi_ship(){
			var weight = $('#khoi_luong').val();
			var numberof = $('#so_luong').val();
			let phi = 5000*weight*numberof;
			phi = phi.toLocaleString();
			$('.phiship').text(phi);
			$('.phiship').val(phi);
		}

		phi_ship()
	</script>
</body>
</html>