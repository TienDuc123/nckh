<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NCKH</title>
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
	<link rel="stylesheet" href="{{ asset('css/icon/fontawesome-free-6.2.0-web/css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('css/famework/bootstrap-5.2.2-dist/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
	<style>
		.dataTables_wrapper .dataTables_filter input{
			margin-bottom: 1rem;
		}
	</style>
</head>
<body>
	<div class="list_request">
		@include("admin.menu")
	</div>
	<div class="header">
		@include("admin.sibar_heder")
	</div>


	<h5 style="margin-left: 96px; margin-top: 3rem;">Danh sách đơn hàng</h5>

	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>Mã sản phẩm</th>
	                <th>Tên khách hàng</th>
	                <th>Số điện thoại</th>
	                <th>Địa chỉ</th>
	                <th>Tên hàng hóa</th>
	                <th>Khối lượng</th>
	                <th>Số lượng</th>
	                <th>Phí ship</th>
	                <th>Ghi chú</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>
	  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">Ngọc Linh</div>
	  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">Hải Linh</div>
	  <div class="tab-pane fade" id="pills-date" role="tabpanel" aria-labelledby="pills-date-tab">Khánh Linh</div>
	  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Phương Linh</div>
	</div>


	<div class="table-home">
		
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
	                url:" {{ route('list_donhang') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'nh_masanpham'},
	                {data:'nh_tenkhachhang'},
	                {data:'nh_SĐT'},
	                {data:'nh_diachi'},
	                {data:'nh_tenhanghoa'},
	                {data:'nh_khoiluong'},
	                {data:'nh_soluong'},
	                {data:'nh_phiship'},
	                {data:'nh_ghichu'},

	            ]

	        });

    	});

	</script>
</body>
</html>