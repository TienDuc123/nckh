@extends('admin/default')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Đăng ký</title>
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
</head>
<body>
	<style>
		.css_subr{
			padding: 25px 0;
			text-align: center;
		}
		.close {
			border: none;
			background: transparent;
		}
		.upadate_ac,.upadate_pass,.delete_ac{
			border: none;
			outline: none;
			background: none;
			font-size: 17px;
		}
		.upadate_ac{
			color: blue;
		}
		.upadate_pass{
			color: orange;
		}
		.delete_ac{
			color: red;
		}
		.css_dangky{
			width: 90%;
			margin: auto;
			padding: 15px;
			display: flex;
    		justify-content: space-between;
		}
		.dang_ky_taikhoan{
		    background: springgreen;
		    border: none;
		    color: white;
		    border-radius: 5px;
		    box-shadow: 1px 1px 1px lightgrey;
		    margin-right: 75px;
		}

		.genders{
			border: none;
		    outline: none;
		    box-shadow: 2px 1px 7px 3px #bac3c57a;
		    border-radius: 7px;
		    height: 36px;
		}
		.btn-success{
			background: skyblue !important;
		}
		.css_subr input{
			color: white;
		}
		.dataTables_wrapper .dataTables_filter input{
			margin-bottom: 1rem;
		}

		.note{
			width: 100%;
		    border: none;
		    outline: none;
		}
	</style>	
	<div class="list_request">
		@include("admin.menu")
	</div>	
	<div class="header">
		@include("admin.sibar_heder")
	</div>

	@if (session('key'))
	    <div class="alert alert-success" role="alert">
	        {{ session('key') }}
	        <a href="#" class="close" style="float: right;" data-bs-dismiss="alert" aria-label="close">&times;</a>
	    </div>
	@endif
	
	<div class="css_dangky">
		<h5>Danh sách tài khoản</h5>
		<div class="dang_ky_taikhoan d-flex align-items-center justify-content-center p-2">
			<a href="{{route('themtaikhoan')}}" style="display: block; height: 100%; color: white;">
				<i class="fa-solid fa-plus"></i>
				<span>Thêm mới</span>
			</a>
		</div>
	</div>

	<div class="container">
		<div class="m-3">
			<div class="btn btn-danger">
				<a href="{{route('create_account')}}" style="color: white;">NVTDKH</a>
			</div>
			<div class="btn btn-info">
				<a href="{{route('nvgh')}}" style="color: white;">NVGH</a>
			</div>
			<div class="btn btn-info">
				<a href="{{route('khachhang')}}" style="color: white;">Khách hàng</a>
			</div>
			<div class="btn btn-info">
				<a href="{{route('ac_admin')}}" style="color: white;">ADMIN</a>
			</div>
		</div>

	</div>
	<!-- <div class="">
		<input type="text" class="">
		<button class="d-flex align-items-center search btn btn-primary">
			<pre class="m-0"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</pre>
		</button>
	</div> -->
	<div class="tab-content" id="pills-tabContent">
		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>MÃ NHÂN VIÊN</th>
	                <th>TÊN NHÂN VIÊN</th>
	                <th>SĐT</th>
	                <th>NGÀY SINH</th>
	                <th>GIỚI TÍNH</th>
	                <th>ĐỊA CHỈ</th>
	                <th>Chứng minh thư</th>
	                <th>HÀNH ĐỘNG</th>
	                
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	</div>

	<!-- Large modal -->
	<!-- <form id="uploadForm" enctype="multipart/form-data" action="">
		<div class="modal fade bd-example-modal-lg show_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content p-4">
		    	<div class="text-center mb-3"><img src="{{ asset('css/img/logo.png') }}" alt="" style="width: 78px;"></div>
		      <h3 class="text-center" style="color: #ff0018;">Thêm nhân viên</h3>

		      <div class="name_nv css_subr">
		      	<label for="name_nv" class="btn-sm-2">Tên nhân viên</label>
		      	<input type="text" id="name_nv" class="btn btn-success w-75">
		      </div>
		      <div class="ma_nv css_subr">
		      	<label for="ma_nv" class="btn-sm-2">Mã nhân viên</label>
		      	<input type="text" id="ma_nv" class="btn btn-success w-75">
		      </div>
		      <div class="date_of_birth css_subr">
		      	<label for="birth" style="padding: 0px 22px 1px 7px;">Ngày sinh</label>
		      	<input type="date" id="birth" class="btn btn-success w-75">
		      </div>
		      <div class="gender css_subr">
		      	<label for="gender" style="padding: 0px 27px 1px 14px;">Giới tính</label>
		      	<select name="" id="gender" class="w-75 btn btn-success genders" style="border: solid .5px black;" >
		      		<option>Chọn giới tính</option>
		      		<option value="Nam">Nam</option>
		      		<option value="Nữ">Nữ</option>
		      		<option value="khác">Khác</option>
		      	</select>
		      </div>
		      <div class="address css_subr">
		      	<label for="address" style="padding: 0px 28px 1px 23px;">Địa chỉ</label>
		      	<input type="text" id="address" class="btn btn-success w-75">
		      </div>
		      <div class="cmt css_subr">
		      	<label for="cmt" style="padding: 0px 28px 1px 23px;">CMT</label>
		      	<input type="file" id="cmt" class="btn btn-success w-75">
		      </div>
		    
		      <div class="loainv css_subr">
		      	<label for="loainv" style="margin: 1px 3px 1px -15px;">Loại nhân viên</label>
		  
		      	<select name="" id="loainv" class="w-75 btn btn-success">
		      		<option value="NVTDKH">NVTDKH</option>
		      		<option value="NVGH">NVGH</option>
		      		<option value="ADMIN">ADMIN</option>
		      		<option value="KH">KH</option>
		      	</select>
		      </div>
		      <div class="account css_subr">
		      	<label for="account" style="padding: 1px 25px 1px 2px;">SĐT</label>
		      	<input type="text" id="account" class="btn btn-success  w-75">
		      </div>
		      <div class="passwork css_subr">
		      	<label for="passwork" style="padding: 1px 25px 1px 2px;">Mật khẩu</label>
		      	<input type="password" id="passwork" class="btn btn-success w-75">
		      </div>
		      <button class="create_ac btn btn-warning w-25 m-auto">Thêm</button>
		    </div>
		  </div>
		</div>
	</form> -->


	<!-- Modal -->
	<div class="modal fade" id="modal_pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Cấp mật khẩu</h5>
	        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div class="passwork css_subr">
	      		<label for="password">Mật khẩu mới</label>
	      		<br>
	      		<input type="password" id="password" class="btn btn-info w-75">
	      	</div>

	      	<div class="passwork css_subr">
	      		<label for="password2">Nhập lại mật khẩu</label>
	      		<input type="password" id="password2" class="btn btn-info w-75">
	      	</div>

	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary update_mk">Lưu</button>
	      </div>
	    </div>
	  </div>
	</div>
	

	<!-- Modal  dellete-->
	<div class="modal fade" id="modal_del" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Ghi chú</h5>
	        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <input type="text" class="note">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
	        <button type="button" class="btn btn-primary delete_s" >Xóa</button>
	      </div>
	    </div>
	  </div>
	</div>
	<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>


	<script>
		var select = 0;
		var id_nhanviens;
		var id_loainhanvien = 0;
		$( function () {
	        table = $('#myTable').DataTable({
	        	lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
	            responsive: true,
	            processing: true,
	            // serverSide: true,
	            // "searching": false,
	            // "dom":"rtip",
	    		"language": {
			      "search": "Tìm kiếm:",
			      "lengthMenu": "Hiển thị _MENU_ bản ghi",
			      "zeroRecords": "Không tìm thấy kết quả nào",
			      "info": "Đang hiển thị bản ghi _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
			      "infoEmpty": "Không có bản ghi nào để hiển thị",
			      "infoFiltered": "(được lọc từ tổng số _MAX_ bản ghi)",
			      "paginate": {
			        "first": "Đầu",
			        "last": "Cuối",
			        "next": "Tiếp",
			        "previous": "Trước"
			      }
			    },
	            ajax: {
	                url:" {{ route('dangky') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'ma_nhanvien'},
	                {data:'ten_nhanvien'},
	                {data:'sdt'},
	                {data:'ngay_sinh'},
	                {data:'gioi_tinh'},
	                {data:'dia_chi'},
	                {data:'anhcmt'},
					{data:'actions'},
	                

	            ]


	        });

    	});

    $('.dang_ky_taikhoan').on('click',function(){
    	select = 1;
    	let name_nv = $('#name_nv').val('');
    	let ngay_s = $('#birth').val('');
    	let gender = $('#gender').val('');
    	let address = $('#address').val('');
    	let loai_nv = $('#loainv').val('');
    	let account = $('#account').val('');
    	let passwork = $('#passwork').val('');
    	let ma_nv = $('#ma_nv').val('');
    	let cmt = $('#cmt').val('');
    	$('.create_ac').text('Thêm mới');
    	$('.show_modal').modal('show');
    })

    // function create_acc(){
    // 	let name_nv = $('#name_nv').val();
    // 	let ma_nv = $('#ma_nv').val();
    // 	let ngay_s = $('#birth').val();
    // 	let gender = $('#gender').val();
    // 	let address = $('#address').val();
    // 	let loai_nv = $('#loainv').val();
    // 	let account = $('#account').val();
    // 	let passwork = $('#passwork').val();
    // 	let cmt = $('#cmt').val();
    // 	$.ajax({
    //         url: "{!! route('tao_tk') !!}",
    //         type: 'POST',
    //         data:{
    //         	name_nv : name_nv,
    //         	ma_nv : ma_nv,
    //         	ngay_s : ngay_s,
    //         	gender : gender,
    //         	address : address,
    //         	loai_nv : loai_nv,
    //         	account : account,
    //         	passwork : passwork,
    //         	cmt : cmt,
    //         	select : select,
    //         	id_nhanviens : id_nhanviens,
    //         	id_loainhanvien : id_loainhanvien,
    //         	_token: '{{ csrf_token() }}',
    //         },
    //         error: function(err) {

    //         },
    //         success: function(data) {
    //         	console.log(data)
    //         	table.ajax.reload();
    //         	$('.show_modal').modal('hide');
    //         }

    //     });
    // }

    $('.css-table').on('click','.upadate_ac',function(){
    	let id_nhanvien = $(this).attr('d-id');
    	
    	$.ajax({
            url: "{!! route('update_ac') !!}",
            type: 'POST',
            data:{
            	id_nhanvien : id_nhanvien,
            	_token: '{{ csrf_token() }}',
            },
            error: function(err) {

            },
            success: function(data) {
            	select = 2;
            	id_nhanviens = data.id;
            	id_loainhanvien = data.id_loainhanvien;
            	let name_nv = $('#name_nv').val(data.ten_nhanvien);
            	let ma_nv = $('#ma_nv').val(data.ten_nhanvien);
				let ngay_s = $('#birth').val(data.ngay_sinh);
				let gender = $('#gender').val(data.gioi_tinh);
				let address = $('#address').val(data.dia_chi);
				let loai_nv = $('#loainv').val(data.tenloainhanvien);
				let account = $('#account').val(data.username);
				let passwork = $('#passwork').val(data.passwork);
				let cmt = $('#cmt').val(data.sdt);
				$('.create_ac').text('Cập nhật');
            	$('.show_modal').modal('show');
            }

        });
    });

    $('.css-table').on('click','.delete_ac',function(){
    	
    	var id_nhanvien = $(this).attr('d-id');
    	

    	$('#modal_del').modal('show');
    	$('.delete_s').on('click',function(){
    		let note = $('.note').val();
    		
 			console.log(note)
    		$.ajax({
	            url: "{!! route('delete_ac') !!}",
	            type: 'POST',
	            data:{
	            	id_nhanvien : id_nhanvien,
	            	note : note,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {
	            	location.reload();
	            	table.ajax.reload();
	            }

	        });
    	})
    	
    });


    $('.css-table').on('click','.upadate_pass',function(){
    	$('#modal_pass').modal('show');
    	let id_nhanvien = $(this).attr('d-id');
    	$('#password').val('');
    	$('#password2').val('');
    	$('.update_mk').on('click', function(){
    	
    		let pass = $('#password').val();
    		let pass2 = $('#password2').val();
    		$.ajax({
	            url: "{!! route('chang_pass') !!}",
	            type: 'POST',
	            data:{
	            	id_nhanvien : id_nhanvien,
	            	pass : pass,
	            	pass2 : pass2,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {
	            	if(data == 1){
	            		table.ajax.reload();
		            	alert("Cập nhật mật khẩu thành công");
		            	$('#modal_pass').modal('hide');
	            	}else{
	            		alert("Mật khẩu không trùng nhau");
	            	}
	            	
	            }

       	 	});
    	})
    });
	$('.icon').on('click', function(){
		$('.list_request').slideToggle();
	})
	</script>
</body>
</html>