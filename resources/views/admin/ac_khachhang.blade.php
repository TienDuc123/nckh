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
		.close {
			border: none;
			background: transparent;
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

	
	<div class="css_dangky">
		<h5>Danh sách tài khoản</h5>
		<div class="dang_ky_taikhoan d-flex align-items-center justify-content-center p-2">
			<a href="{{route('themtaikhoan')}}" style="display: block; height: 100%;color: white;">
				<i class="fa-solid fa-plus"></i>
				<span>Thêm mới</span>
			</a>
		</div>
	</div>

	<div class="container">
		<div class="m-3">
			<div class="btn btn-info">
				<a href="{{route('create_account')}}" style="color: white;">NVTDKH</a>
			</div>
			<div class="btn btn-info">
				<a href="{{route('nvgh')}}" style="color: white;">NVGH</a>
			</div>
			<div class="btn btn-danger">
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
	                <th>MÃ KHÁCH HÀNG</th>
	                <th>TÊN KHÁCH HÀNG</th>
	                <th>SỐ ĐIỆN THOẠI</th>
	                <th>NGÀY SINH</th>
	                <th>GIỚI TÍNH</th>
	                <th>ĐỊA CHỈ</th>
	                <th>CHỨNG MINH THƯ</th>
	                <th>HÀNH ĐỘNG</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="modal_pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Cấp mật khẩu</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
	        <input type="text" class="note" placeholder="Thông tin ghi chú">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">đóng</button>
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
	                url:" {{ route('tt_khachhang_ac') }}",
	                type: 'GET',
	            },

	            columns: [ 
	            
	                {data:'ma_khachang'},
	                {data:'kh_ten'},
	                {data:'sdt'},
	                {data:'ngaysinh'},
	                {data:'gioitinh'},
	                {data:'kh_Address'},
	                {data:'anhcmt'},
	                {data:'actions'},

	            ]


	        });

    	});

  $('.css-table').on('click','.delete_ac',function(){
    	var id_nhanvien = $(this).attr('d-id');
    	

    	$('#modal_del').modal('show');
    	$('.delete_s').on('click',function(){
    		let note = $('.note').val();

 			// console.log(id_nhanvien)
    		$.ajax({
	            url: "{!! route('delete_ac_kh') !!}",
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
	            url: "{!! route('chang_pass_kh') !!}",
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