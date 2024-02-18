@extends('admin/default')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thêm xe công ty</title>
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
</head>
<body>
	

	<form action="{{route('create_car')}}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="text" hidden name="acction" value="{{isset($khach_hang->xct_id)?'update_kh':'create_kh'}}">
		<input type="text" hidden name="id_kh" value="{{isset($khach_hang->xct_id)?$khach_hang->xct_id:''}}">
		<input type="text" hidden name="nhanviengh" value="{{isset($khach_hang->gh_id)?$khach_hang->gh_id:''}}">
		<div class="container">
			@if(isset($khach_hang))
				<h3 class="mt-4 text-center">Cập nhật thông tin xe</h3>
			@else
				<h3 class="mt-4">Thêm xe mới</h3>
			@endif
			<div class="row">
				<h5 class="text-danger mt-4">Thông tin xe</h5>
				<div class="col-4">
					<label for="type">Tên xe</label>
					<br>
					<input type="text" class="form-control" id="type" name="tenxe" value="{{isset($khach_hang->xct_ten_xe)?$khach_hang->xct_ten_xe:''}}" required>
				</div>
				<div class="col-4">
					<label for="type">Biển số xe</label>
					<br>
					<input type="text" class="form-control" id="type" name="biensoxe" value="{{isset($khach_hang->xct_biensoxe)?$khach_hang->xct_biensoxe:''}}" required>
				</div>

				<div class="col-4">
					<label for="guest">Loại xe</label>
					<br>
					<input type="text" class="form-control" id="guest" name="loaixe" value="{{isset($khach_hang->xct_loai_xe)?$khach_hang->xct_loai_xe:''}}" required>
				</div>

				<div class="col-4">
					<label for="zalo">Số đăng kiểm</label>
					<br>
					<input type="text" class="form-control" id="zalo" name="sodangkiem" value="{{isset($khach_hang->xct_sodangkiem)?$khach_hang->xct_sodangkiem:''}}" required>
				</div>

				<div class="col-4">
					<label for="loai_tn">Hạn đăng kiểm</label>
					<br>
					<input type="date" class="form-control" id="loai_tn" name="handangkiem" value="{{isset($khach_hang->xct_handangkiem)?$khach_hang->xct_handangkiem:''}}" required>
				</div>

				<div class="col-4">
					<label for="phone_number">Định mức</label>
					<br>
					<input type="text" class="form-control" id="phone_number" name="dinhmuc" value="{{isset($khach_hang->xct_dinhmuc)?$khach_hang->xct_dinhmuc:''}}" required>
				</div>

				<div class="col-4">
					<label for="mail">Thông số kỹ thuật</label>
					<br>
					<input type="text" class="form-control" id="mail" name="thongsokythuat" value="{{isset($khach_hang->xct_thongsokythuat)?$khach_hang->xct_thongsokythuat:''}}" required>
				</div>

				<div class="col-4">
					<label for="anhdangkiem">Ảnh minh họa</label>
					<br>
					<input type="file" name="anhminhhoa" class="form-control" {{empty($khach_hang->xct_anh)?'required':''}}>
				</div>

				<h5 class="text-danger mt-4">Thông tin lái xe</h5>
				<div class="col-4">
					<label for="number_business">Tên lái xe</label>
					<br>
					<select name="" id="" class="upload form-control" required>
						@if(empty($khach_hang->xct_id))
							<option value="" hidden>Chọn nhân viên</option>
							@forEach($nhanvienlx as $value)
								<option value="{{$value->gh_id}}" class="update_inf">{{$value->gh_manvgh}}.{{$value->gh_tennvgh}}</option>
							@endforEach
						@endif
						@if(!empty($khach_hang->xct_id))
							<option value="{{$khach_hang->id_nvgh}}" hidden>{{$khach_hang->gh_manvgh}}.{{$khach_hang->gh_tennvgh}}</option>
							@forEach($nhanvienlx as $value)
								<option value="{{$value->gh_id}}" class="update_inf">{{$value->gh_manvgh}}.{{$value->gh_tennvgh}}</option>
							@endforEach
						@endif
					</select>
					
				</div>
				<input type="text" hidden name="id_gh" class="putsid">
				<div class="col-4">
					<label for="surplus">Số điện thoại</label>
					<br>
					<input type="tel" id="surplus" disabled name="sodienthoai" value="{{isset($khach_hang->gh_sdt)?$khach_hang->gh_sdt:''}}" class="form-control" >
				</div>

				<div class="col-4">
					<label for="pay">Giới tính</label>
					<br>
					<input type="text" class="form-control" id="cmt" name="cmt" disabled value="{{isset($khach_hang->gh_cmt)?$khach_hang->gh_cmt:''}}" >
				</div>

				<div class="col-4">
					<label for="status">Địa chỉ</label>
					<br>
					<input type="text" class="form-control" id="status" name="bhtnds" value="{{isset($khach_hang->gh_bhtnds)?$khach_hang->gh_bhtnds:''}}" disabled>
				</div>

				<div class="col-4">
					<label for="bhtn">Ngày sinh</label>
					<br>
					<input type="date" class="form-control" id="bhtn" name="hanbhtnds" value="{{isset($khach_hang->gh_hbhtnds)?$khach_hang->gh_hbhtnds:''}}" disabled>
				</div>
			<div>
		</div> 	
		<div class="mt-2">
			@if(isset($khach_hang))
				<button type="submit" class="btn btn-primary m-2" style="width: 6rem;">Cập nhật</button>
			@else
				<button type="submit" class="btn btn-primary m-2" style="width: 6rem;">Thêm</button>
			@endif
			
			<button type="button" class="btn btn-danger m-2" style="width: 6rem;">
				<a href="{{route('list_car')}}" style="color: white;" class="w-100 h-100">Đóng</a>
			</button>
		</div>
	</form>	

	@if(isset($khach_hang->xct_anh))

		<div class="text-center">
			<a href="{{$khach_hang->xct_anh}}" target="_blank">
			    <img src="{{$khach_hang->xct_anh}}" alt="lỗi" width="30%">
			</a>
		</div>
	@endif

	<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>
	<script>

		$('.upload').on('click',function(){
			let id_nvgh = $(this).val();
			$.ajax({
				url: "{!! route('data_nvgh') !!}",
	            type: 'POST',
	            data:{
	            	id_nvgh : id_nvgh,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {
	            	$('#surplus').val(data.email);
	            	$('#cmt').val(data.gh_gioitinh);
	            	$('#status').val(data.gh_diachigh);
	            	$('#bhtn').val(data.gh_ngaysinh);
	            	$('.putsid').val(data.gh_id);
	            	// $('#exampleModal').modal('hide');
	            }
			});
		});

		$(window).on('load', function() {
		    let id_nvgh = $('.upload').val();
		    // console.log(id_nvgh)
		    $.ajax({
		        url: "{!! route('data_nvgh') !!}",
		        type: 'POST',
		        data: {
		            id_nvgh: id_nvgh,
		            _token: '{{ csrf_token() }}',
		        },
		        error: function(err) {
		            // Xử lý lỗi nếu có
		        },
		        success: function(data) {
		            $('#surplus').val(data.email);
		            $('#cmt').val(data.gh_gioitinh);
		            $('#status').val(data.gh_diachigh);
		            $('#bhtn').val(data.gh_ngaysinh);
		            $('.putsid').val(data.gh_id);
		            // $('#exampleModal').modal('hide');
		        }
		    });
		});
	</script>
</body>
</html>