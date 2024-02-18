<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Danh sách khách hàng</title>
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
	<link rel="stylesheet" href="{{ asset('css/icon/fontawesome-free-6.2.0-web/css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('css/famework/bootstrap-5.2.2-dist/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
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
	<div class="search_css">
		<h5>Danh sách Khách hàng</h5>
		<button class="d-flex align-items-center">
			<pre class="m-0"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</pre>
		</button>
	</div>
	
	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	            	<th>Mã định danh</th>
	                <th>Tên khách hàng</th>
	                <th>Số điện thoại</th>
	                <th>Loại khách hàng</th>
	                <th>Tài khoản</th>
	                <th>Hành động</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>
	</div>

	<script src="{{ asset('css/icon/fontawesome-free-6.2.0-web/js/all.js')}}"></script>
	<script src="{{ asset('css/famework/bootstrap-5.2.2-dist/js/bootstrap.min.js')}}"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>

	<script>
		$( function () {
	        table = $('#myTable').DataTable({
	        	lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
	            responsive: true,
	            processing: true,
	            serverSide: true,
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
	                url:" {{ route('tt_khachhang') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'kh_ma_dinhdanh'},
	                {data:'kh_ten'},
	                {data:'kh_sdt'},
	                {data:'kh_loai'},
	                {data:'user'},
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
		            url: "{!! route('khoi_phuc_nhanvien') !!}",
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