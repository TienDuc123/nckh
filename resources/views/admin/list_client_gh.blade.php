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

		.khoi_phuc{
			background: none;
		    border: none;
		    outline: none;
		    color: blue;
		    /* width: 10rem; */
		    font-size: 16px;
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
		<h5>Danh sách tài khoản dừng hoạt động</h5>
		
	</div>

	<div class="container">
		<div class="m-3">
			<div class="btn btn-info">
				<a href="{{route('list_client')}}" style="color: white;">NVTDKH</a>
			</div>
			<div class="btn btn-danger">
				<a href="{{route('list_client_gh')}}" style="color: white;">NVGH</a>
			</div>
			<div class="btn btn-info">
				<a href="{{route('list_client_kh')}}" style="color: white;">Khách hàng</a>
			</div>
			@if(Auth::user()->permissions == 4 && Auth::user()->email == '0368374871')
				<div class="btn btn-info">
					<a href="{{route('list_client_admin')}}" style="color: white;">ADMIN</a>
				</div>
			@endif
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
	                <th>Ghi chú</th>
	                <th>HÀNH ĐỘNG</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	</div>

	<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>

	<script>
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
	                url:" {{ route('list_nv_kp_gh') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'gh_manvgh'},
	                {data:'gh_tennvgh'},
	                {data:'sdt'},
	                {data:'gh_ngaysinh'},
	                {data:'gh_gioitinh'},
	                {data:'gh_diachigh'},
	                {data:'anhcmt'},
	                {data:'note'},
	                {data:'actions'},

	            ]


	        });

    	});

    	$('.icon').on('click', function(){
			$('.list_request').slideToggle();
		})

		$('.css-table').on('click','.khoi_phuc',function(){
    		let xct = $(this).attr('d-id');
    		$.ajax({
		            url: "{!! route('khoi_phuc_nhanvien_gh') !!}",
		            type: 'POST',
		            data:{
		            	xct : xct,
		            	_token: '{{ csrf_token() }}',
		            },
		            error: function(err) {

		            },
		            success: function(data) {

		            	table.ajax.reload();
		            }

		        });
    	});
	</script>
</body>
</html>