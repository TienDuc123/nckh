<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thêm mới khách hàng</title>
</head>
<body>
	<h2>Thêm mới khách hàng</h2>
	<h4>Thông tin chung</h4>
	<form action="{{route('them_kh')}}" method="POST">
		@csrf
		<input type="text" hidden name="acction" value="{{isset($khach_hang->kh_id)?'update_kh':'create_kh'}}">
		<input type="text" hidden name="id_kh" value="{{isset($khach_hang->kh_id)?$khach_hang->kh_id:''}}">
		<div class="container">
			
			<div class="row">
				
				<div class="col">
					<label for="type">Loại</label>
					<br>
					<input type="text" id="type" name="loai" value="{{isset($khach_hang->kh_loai)?$khach_hang->kh_loai:''}}">
				</div>

				<div class="col">
					<label for="guest">Tên khách hàng</label>
					<br>
					<input type="text" id="guest" name="ten_kh" value="{{isset($khach_hang->kh_ten)?$khach_hang->kh_ten:''}}">
				</div>

				<div class="col">
					<label for="name_vt">Tên viết tắt</label>
					<br>
					<input type="text" id="name_vt" name="ten_vt" value="{{isset($khach_hang->kh_ten_viettat)?$khach_hang->kh_ten_viettat:''}}">
				</div>
		
				<div>
					<label for="address">Địa chỉ</label>
					<br>
					<input type="text" id="address" name="dia_chi" value="{{isset($khach_hang->kh_Address)?$khach_hang->kh_Address:''}}">
				</div>

				<div>
					<label for="zalo">Tên Zalo</label>
					<br>
					<input type="text" id="zalo" name="zalo" value="{{isset($khach_hang->kh_zalo)?$khach_hang->kh_zalo:''}}">
				</div>

				<div>
					<label for="loai_tn">Loại tin nhắn</label>
					<br>
					<input type="text" id="loai_tn" name="loai_tn" value="{{isset($khach_hang->kh_loaitinnhan)?$khach_hang->kh_loaitinnhan:''}}">
				</div>

				<div>
					<label for="phone_number">SĐT</label>
					<br>
					<input type="tel" id="phone_number" name="sdt" value="{{isset($khach_hang->kh_sdt)?$khach_hang->kh_sdt:''}}">
				</div>

				<div>
					<label for="mail">Địa chỉ Email</label>
					<br>
					<input type="email" id="mail" name="mail" value="{{isset($khach_hang->kh_mail)?$khach_hang->kh_mail:''}}">
				</div>

				<div>
					<label for="number_business">Số đăng ký kinh doanh</label>
					<br>
					<input type="number" id="number_business" name="sdkkd" value="{{isset($khach_hang->kh_sodangky_kd)?$khach_hang->kh_sodangky_kd:''}}">
				</div>

				<div>
					<label for="surplus">Số dư định kỳ</label>
					<br>
					<input type="number" id="surplus" name="sodudk" value="{{isset($khach_hang->kh_sodu_dinhky)?$khach_hang->kh_sodu_dinhky:''}}">
				</div>

				<div>
					<label for="pay">Phương thức thanh toán</label>
					<br>
					<input type="text" name="pttt" value="{{isset($khach_hang->kh_pt_thanhtoan)?$khach_hang->kh_pt_thanhtoan:''}}">
				</div>

				<div>
					<label for="level">Hạn mức thanh toán</label>
					<br>
					<input type="number" name="hmtt" value="{{isset($khach_hang->kh_hanmuc_ttso)?$khach_hang->kh_hanmuc_ttso:''}}">
				</div>

				<div>
					<label for="status">Trạng thái</label>
					<br>
					<input type="text" id="status" name="trang_thai" value="{{isset($khach_hang->kh_trang_thai)?$khach_hang->kh_trang_thai:''}}">
				</div>
			<div>
		</div> 	

		<input type="submit" value="Thêm">
	</form>
</body>
</html>