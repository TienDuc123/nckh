<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/icon/fontawesome-free-6.2.0-web/css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('css/famework/bootstrap-5.2.2-dist/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
	<style>
		.dataTables_wrapper .dataTables_filter input{
			margin-bottom: 1rem;
		}
	</style>
	<title>Danh mục</title>
</head>
<body>
	
	<div class="header">
		<div class="icon">
			<i class="fa-solid fa-bars"></i>
		</div>
		<div class="content-header">
			<a href="{{route('oder')}}">
				<strong>đơn hàng</strong>
			</a>
			<a href="">
				<strong>danh mục</strong>
			</a>
			<a href="{{route('don_hangcho')}}">
				<strong>Đơn hàng chờ</strong>
			</a>
			<a href="">
				<strong>xin đơn hàng</strong>
			</a>
		</div>
		<div class="img-header">
			<img src="{{ asset('css/img/logo.png') }}" alt="">
		</div>

		<div class="notification">
			<span>
			    <i class="fa-solid fa-bell"></i>
			</span>
			<span class="span2">admin</span>
			<button>
				<i class="fa-solid fa-right-from-bracket"></i>
			</button>
		</div>
	</div>

	<div>
		<h3>Danh sách khách hàng</h3>
		<button class="create_ac"><a href="{{route('create_customer')}}">Thêm mới</a></button>	
		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>MÃ ĐỊNH DANH</th>
	                <th>TÊN KHÁCH HÀNG</th>
	                <th>LOẠI</th>
	                <th>ĐẠI CHỈ</th>
	                <th>SỐ ĐIỆN THOẠI</th>
	                <th>EMAIL</th>
	                <th>TRẠNG THÁI</th>
	                <th>HÀNH ĐỘNG</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
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
	            // serverSide: true,
	            // "searching": false,
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
	                url:" {{ route('data_danhmuc') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'kh_ma_dinhdanh'},
	                {data:'kh_ten'},
	                {data:'kh_loai'},
	                {data:'kh_Address'},
	                {data:'kh_sdt'},
	                {data:'kh_mail'},
	                {data:'kh_trang_thai'},
	                {data:'action'},

	            ]


	        });

    	});

    	$('.css-table').on('click','.delete_inf',function(){
    		let id_kh = $(this).attr('dt-id');
    		$.ajax({
            url: "{!! route('delet_tt') !!}",
            type: 'POST',
            data:{
            	id_kh : id_kh,
            	_token: '{{ csrf_token() }}',
            },
            error: function(err) {

            },
            success: function(data) {
            	console.log(data)

            	table.ajax.reload();
            }

        	});
    	});

  

	</script>
</body>
</html>