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
</head>
<body>
	<div class="list_request">
		<div class="request range">
			<a href="">
				<i class="fa-solid fa-code-pull-request"></i>
				<span>yêu cầu</span>
			</a>
		</div>
		<div class="orders range">
			<a href="">
				<i class="fa-solid fa-car"></i>
				<span>đơn hàng</span>
			</a>
		</div>
		<div class="orders_request range">
			<a href="">
				<i class="fa-solid fa-car"></i>
				<span>đơn hàng nvtdkh</span>
			</a>
		</div>
		<div class="accountant range">
			<i class="fa-regular fa-clipboard-list-check"></i>
			<span>Kế toán</span>
			<i class="fa-light fa-angle-down"></i>
		</div>
	</div>
	<div class="header">
		<div class="icon">
			<i class="fa-solid fa-bars"></i>
		</div>
		<div class="content-header">
			<a href="">
				<strong>đơn hàng</strong>
			</a>
			<a href="{{route('danhmuc')}}">
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

	<h3>Xe công ty</h3>
	<button class="create_ac"><a href="{{route('add_car')}}">Thêm mới</a></button>
	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>BIỂN SỐ XE</th>
	                <th>LOẠI ĐẦU KÉO</th>
	                <th>SỐ RƠ MOOC</th>
	                <th>LOẠI SƠ MI</th>
	                <th>TÊN LÁI XE</th>
	                <th>SỐ ĐIỆN THOẠI</th>
	                <th>HẠN ĐĂNG KIỂM</th>
	                <th>HẠN BHTNDS</th>
	                <th>HÀNH ĐỘNG</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>
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
	            // serverSide: true,
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
	                url:" {{ route('add_xecongty') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'xct_biensoxe'},
	                {data:'xct_loaidaukeo'},
	                {data:'xct_soromooc'},
	                {data:'xct_loaisomi'},
	                {data:'xct_tenlaixe'},
	                {data:'xct_sdt'},
	                {data:'xct_NgayHetHanDK'},
	                {data:'xct_NgayHetHanBHTNDS'},
	                {data:'action'},

	            ]


	        });

    	});

    	$('.css-table').on('click','.delete_inf',function(){
    		let id_xecty = $(this).attr('dt-id');

    		$.ajax({
	            url: "{!! route('delete_xecty') !!}",
	            type: 'POST',
	            data:{
	            	id_xecty : id_xecty,
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