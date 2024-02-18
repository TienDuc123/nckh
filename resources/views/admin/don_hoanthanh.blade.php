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
		.don_hoan{
			width: 100% !important;
		}

		.donhuy{
			width: 100% !important;
		}
		.dataTables_wrapper .dataTables_filter input{
			margin-bottom: 1rem;
		}
		.excel svg{
			font-size: 34px;
    		color: #12c212;
		}
		#pills-tabContent{
			padding: 0 28px !important;
		}
	</style>
	
	<div class="list_request">
		@include("admin.menu")
	</div>
	<div class="header">
		@include("admin.sibar_heder")
	</div>

	<ul class="nav nav-pills mb-3 list-css" id="pills-tab" role="tablist">
		  <li class="nav-item" role="presentation">
		    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
		    	Đơn hoàn thành
		    </button>
		    
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
		    	Đơn hoàn
		    </button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
		    	Đơn hủy
		    </button>
		  </li>
	</ul>

	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<div style="display: flex;justify-content: flex-end;" class="excel">
	  			<a href="excel_donhoanthanh" title="Xuất excel">
	  				<i class="fa-solid fa-file-excel btn"></i>
	  			</a>
	  		</div>
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	            	@if(Auth::user()->permissions != 1)
		            	<th>Tên khách hàng</th>
		            	<th>Số điện thoại</th>
		            @endif
		            @if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4)
		            	<th>Nhân viên vận chuyển</th>
		            @endif
	                <th>Mã sản phẩm</th>
	                <th>Tên người nhận</th>
	                <th>Số điện người nhận</th>
	                <th>Địa chỉ</th>
	                <th>Tên hàng hóa</th>
	                <th>Khối lượng</th>
	                <th>Số lượng</th>
	                <th>Phí vận chuyển</th>
	                 <th>Ngày vận chuyển</th>
	                 <th>Ngày hoàn thành</th>
	                 <th>Ảnh minh họa</th>
	                <th>Ghi chú</th>
	               
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>
	<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
		<div style="display: flex;justify-content: flex-end;" class="excel">
			<a href="excel_donhoan" title="Xuất excel">
				<i class="fa-solid fa-file-excel btn"></i>
			</a>
		</div>
	  		<table class="table don_hoan" id="table_donhoan" style="border-collapse: collapse;font-size: 14px;"  border="1">
	            <thead>
	            <tr>
	            	@if(Auth::user()->permissions != 1)
		            	<th>Tên khách hàng</th>
		            	<th>Số điện thoại</th>
		            @endif
		            @if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4)
		            	<th>Nhân viên vận chuyển</th>
		            @endif
	                <th>Mã sản phẩm</th>
	                <th>Tên người nhận</th>
	                <th>Số điện người nhận</th>
	                <th>Địa chỉ</th>
	                <th>Tên hàng hóa</th>
	                <th>Khối lượng</th>
	                <th>Số lượng</th>
	                <th>Phí vận chuyển</th>
	                <th>Ghi chú</th>
	                <th>Ngày vận chuyển</th>
	                <th>Ngày hoàn đơn</th>
	                <th>Ảnh minh họa</th>
	                <th>Phản hồi</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	</div>
	<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
		<div style="display: flex;justify-content: flex-end;" class="excel">
			<a href="excel_donhuy" title="Xuất excel">
				<i class="fa-solid fa-file-excel btn"></i>
			</a>
		</div>
	  		<table class="table donhuy" id="table_donhuy" style="border-collapse: collapse;font-size: 14px;"  border="1">
	            <thead>
	            <tr>
	            	@if(Auth::user()->permissions != 1)
		            	<th>Tên khách hàng</th>
		            	<th>Số điện thoại</th>
		            @endif
	                <th>Mã sản phẩm</th>
	                <th>Tên người nhận</th>
	                <th>Số điện người nhận</th>
	                <th>Địa chỉ</th>
	                <th>Tên hàng hóa</th>
	                <th>Khối lượng</th>
	                <th>Số lượng</th>
	                <th>Phí vận chuyển</th>
	                <th>Ghi chú</th>
	                <th>Ngày vận chuyển</th>
	                <th>Ngày hủy đơn</th>
	                <th>Ảnh minh họa</th>
	                <th>Phản hồi</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	</div>
	<div class="tab-pane fade" id="pills-date" role="tabpanel" aria-labelledby="pills-date-tab">Khánh Linh</div>
	<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Phương Linh</div>
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
	                url:" {{ route('show_don') }}",
	                type: 'GET',
	            },

	            columns: [ 
	            	@if(Auth::user()->permissions != 1)
		                {data:'name_kh'},
		                {data:'sdt'},
		            @endif
		            @if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4)
		                {data:'nhavgh'},
		            @endif
	                {data:'nh_masanpham'},
	                {data:'nh_tenkhachhang'},
	                {data:'nh_SĐT'},
	                {data:'nh_diachi'},
	                {data:'nh_tenhanghoa'},
	                {data:'nh_khoiluong'},
	                {data:'nh_soluong'},
	                {data:'nh_phiship'},
	                {data:'nh_created_at'},
	                {data:'nh_updated_at'},
	                {data:'anhminhhoa'},
	                {data:'nh_ghichu'},

	            ]


	        });

    	});

    	$( function () {
	        table = $('#table_donhoan').DataTable({
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
	                url:" {{ route('hoandon') }}",
	                type: 'GET',
	            },

	            columns: [ 
	            	@if(Auth::user()->permissions != 1)
		                {data:'name_kh'},
		                {data:'sdt'},
		            @endif
		            @if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4)
		                {data:'nhavgh'},
		            @endif
	                {data:'nh_masanpham'},
	                {data:'nh_tenkhachhang'},
	                {data:'nh_SĐT'},
	                {data:'nh_diachi'},
	                {data:'nh_tenhanghoa'},
	                {data:'nh_khoiluong'},
	                {data:'nh_soluong'},
	                {data:'nh_phiship'},
	                {data:'nh_ghichu'},
	                {data:'nh_created_at'},
	                {data:'nh_updated_at'},
	                {data:'anhminhhoa'},
	                {data:'nguyenhan'},

	            ]


	        });

    	});

    	$( function () {
	        table = $('#table_donhuy').DataTable({
	        	lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
	            responsive: true,
	            processing: true,
	            // serverSide: true,
	            // "order": [[ 0, "asc" ]],
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
	                url:" {{ route('huydon') }}",
	                type: 'GET',
	            },

	            columns: [ 
	            	@if(Auth::user()->permissions != 1)
		                {data:'name_kh'},
		                {data:'sdt'},
		            @endif
	                {data:'nh_masanpham'},
	                {data:'nh_tenkhachhang'},
	                {data:'nh_SĐT'},
	                {data:'nh_diachi'},
	                {data:'nh_tenhanghoa'},
	                {data:'nh_khoiluong'},
	                {data:'nh_soluong'},
	                {data:'nh_phiship'},
	                {data:'nh_ghichu'},
	                {data:'nh_created_at'},
	                {data:'nh_updated_at'},
	                {data:'anhminhhoa'},
	                {data:'nh_phanhoi'},
	            ]


	        });
	     });

    	$('.icon').on('click', function(){
    		$('.list_request').slideToggle();
    	})

    	// $('.dinone').on('click', function(){
    	// 	$('.list_request').slideUp();
    	// })

    	// $(document).click(function(e){
    	// 	let $target = $(e.target);

    	// 	if(!$target.closest('.wrapper').length){
    	// 		$('.list_request').stop().slideUp();
    	// 	}
    	// })
	</script>
</body>
</html>