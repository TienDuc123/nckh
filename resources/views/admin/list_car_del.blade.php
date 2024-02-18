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

	<h5 style="margin: 2rem; margin-left: 7rem; margin-bottom: 0; text-align: center;">Danh sách xe dừng hoạt động</h5>
	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>Tên xe</th>
	                <th>Loại xe</th>
	                <th>Biển số xe</th>
	                <th>Số đăng kiểm</th>
	                <th>Hạn đăng kiểm</th>
	                <th>Định mức</th>
	                <th>Ảnh</th>
	                <th>Ngày dừng hoạt động</th>
	                <th>Ghi chú</th>
	                <th>Hành động</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>
	</div>


	<div class="table-home">
		
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
	            // "dom": 'rtip',
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
	                url:" {{ route('list_car_delete') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'xct_ten_xe'},
	                {data:'xct_loai_xe'},
	                {data:'xct_biensoxe'},
	                {data:'xct_sodangkiem'},
	                {data:'xct_handangkiem'},
	                {data:'xct_dinhmuc'},
	                {data:'anhminhhoa'},
	                {data:'xct_deleted_at'},
	                {data:'ghichu'},
	                {data:'actions'},

	            ]


	        });

    	});

    	$('.icon').on('click', function(){
    		$('.list_request').slideToggle();
    	});

    	$('.css-table').on('click','.khoi_phuc',function(){
    		let xct = $(this).attr('dt-id');
    		$.ajax({
		            url: "{!! route('khoi_phuc') !!}",
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