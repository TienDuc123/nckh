@extends('admin/default')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tạo đơn hàng</title>
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
	<!-- <link rel="stylesheet" href="{{ asset('css/icon/fontawesome-free-6.2.0-web/css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('css/famework/bootstrap-5.2.2-dist/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/> -->
</head>
<body>
	

	<form action="{{route('create_donhang')}}" method="POST" enctype="multipart/form-data" id="submit">
		@csrf
		<input type="text" hidden name="acction" value="{{isset($khach_hang->xct_id)?'update_kh':'create_kh'}}">
		<input type="text" hidden name="id_kh" value="{{isset($khach_hang->xct_id)?$khach_hang->xct_id:''}}">
		<div class="container">
			<h3 class="mt-2 text-center">Tạo đơn hàng</h3>
			<h5 class="text-danger mt-4">Thông tin hàng hóa</h5>
			<div class="row">
				
				<div class="col-6">
					<label for="type">Tên khách hàng</label>
					<br>
					<input type="text" class="form-control" id="type" name="tenkhach" value="{{isset($khach_hang->xct_biensoxe)?$khach_hang->xct_biensoxe:''}}" required>
				</div>

				<div class="col-6">
					<label for="guest">Số điện thoại người nhận</label>
					<br>
					<input type="text" pattern="^0\d{9}$" class="form-control" id="guest" name="sdt" value="{{isset($khach_hang->xct_loaidaukeo)?$khach_hang->xct_loaidaukeo:''}}" required>
				</div>

				<div class="col-6">
					<label for="name_vt">Nơi lấy hàng</label>
					<br>
					<input type="text" class="form-control" id="name_vt" name="noilayhang" value="{{isset($khach_hang->xct_soromooc)?$khach_hang->xct_soromooc:''}}" required>
				</div>
		
				<div class="col-6">
					<label for="address">Nơi giao hàng</label>
					<br>
					<input type="text" class="form-control" id="address" name="diachi" value="{{isset($khach_hang->xct_loaisomi)?$khach_hang->xct_loaisomi:''}}" required>
				</div>

				<div class="col-6">
					<label for="zalo">Tên hàng hóa</label>
					<br>
					<input type="text" class="form-control" id="zalo" name="tenhanghoa" value="{{isset($khach_hang->xct_sodangkiem)?$khach_hang->xct_sodangkiem:''}}" required>
				</div>

				<div class="col-6">
					<label for="loai_tn">Khối lượng (Kg)</label>
					<br>
					<input type="number" class="form-control" id="khoi_luong" name="khoiluong" value="" onchange="phi_ship()" required min="0">
				</div>

				<div class="col-6">
					<label for="phone_number">Số lượng</label>
					<br>
					<input type="number" class="form-control" id="so_luong" name="soluong" value="" onchange="phi_ship()" required min="0">
				</div>

				<div class="col-6">
					<label for="phone_number">Tiền lấy hộ</label>
					<br>
					<input type="text" class="form-control" id="money_thuho" name="money_thuho" value="" required>
				</div>

				<div class="col-6">
					<label for="mail">Ghi chú</label>
					<br>
					<textarea id="mail" class="form-control" name="ghichu" value="" placeholder="Ghi chú" style="height: 39px;"></textarea>
				</div>

				<div class="col-6">
					<label for="mail">Ảnh minh họa</label>
					<br>
					<input type="file" id="mail" name="anhminhhoa" value="Chọn tệp" class="form-control" required>
				</div>

				<div class="m-2">
					<strong>Phí vận chuyển : </strong>
					<span class="phiship">0</span> đồng
					<input type="text" hidden class="phiship" name="phiship" value="">
				</div>


			<div>
		</div> 
		<div>	
			<button type="submit" class="btn btn-primary m-2" style="width: 6rem;" id="submit-btn">
				Tạo đơn
			</button>
			
			<button type="button" class="btn btn-danger m-2" style="width: 6rem;">
				<a href="{{route('bieudo')}}" style="color: white;">Đóng</a>
				
			</button>
		</div>
		
	</form>


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



		 const input = document.getElementById('money_thuho');

		// Thêm event listener cho input
		input.addEventListener('input', function(e) {
		  // Lấy giá trị của input
		  let value = e.target.value;

		  // Loại bỏ các ký tự không phải số
		  value = value.replace(/[^\d]/g, '');

		  // Chuyển đổi giá trị thành số
		  const number = parseInt(value, 10);

		  // Định dạng số thành chuỗi có dấu chấm
		  const formattedValue = number.toLocaleString();

		  // Cập nhật giá trị của input với số đã được định dạng
		  e.target.value = formattedValue;

		  if (!isNaN(number)) {
		    // Nếu là số, định dạng số thành chuỗi có dấu chấm
		    const formattedValue = number.toLocaleString();

		    // Cập nhật giá trị của input với số đã được định dạng
		    e.target.value = formattedValue;
		  } else {
		    // Nếu không phải số, không làm gì cả hoặc có thể xóa giá trị hiện tại của input
		    e.target.value = '';
		  }
		});


		// // Lấy thẻ input số điện thoại và nút submit
		// const phoneInput = document.getElementById('guest');
		// const submitBtn = document.getElementById('submit-btn');

		// // Thêm sự kiện click vào nút submit
		// $('#submit-btn').on('click',function(){
		//   const phonePattern = /^\d{10}$/; // Regex để kiểm tra số điện thoại (10 chữ số)
		//   const phoneNumber = phoneInput.value.trim(); // Lấy giá trị số điện thoại đã nhập và loại bỏ các khoảng trắng ở đầu và cuối chuỗi

	  	// 	if (phonePattern.test(phoneNumber)) { // Kiểm tra xem số điện thoại có hợp lệ hay không
		// 	    $('#submit').submit();
		// 	} else {
		// 	    console.log('s3')

		// 	   // 
		// 	}

		//   });
		  
 
	</script>

</body>
</html>