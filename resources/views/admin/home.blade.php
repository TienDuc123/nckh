@extends('admin/default')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NCKH</title>
	<link rel='shortcut icon' href="{{ asset('css/img/Logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
</head>
<body>
	<div class="list_request">
		@include("admin.menu")
	</div>
	<div class="header">
		@include("admin.sibar_heder")
	</div>

	<ul class="nav nav-pills mb-3 list-css" id="pills-tab" role="tablist">
		  <li class="nav-item" role="presentation">
		    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
		    	Tất cả các đơn hàng
		    	<span>(</span>
		    	<span>0</span>
		    	<span>)</span>
		    	<div>
		    		<span>0</span>
		    		<span>/</span>
		    		<span>0</span>
		    	</div>
		    </button>
		    
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
		    	Theo tháng
		    	<span>(</span>
		    	<span>0</span>
		    	<span>)</span>
		    	<div>
		    		<span>0</span>
		    		<span>/</span>
		    		<span>0</span>
		    	</div>
		    </button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
		    	Theo tuần
		    	<span>(</span>
		    	<span>0</span>
		    	<span>)</span>
		    	<div>
		    		<span>0</span>
		    		<span>/</span>
		    		<span>0</span>
		    	</div>
		    </button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link" id="pills-date-tab" data-bs-toggle="pill" data-bs-target="#pills-date" type="button" role="tab" aria-controls="pills-date" aria-selected="false">
		    	Theo ngày
		    	<span>(</span>
		    	<span>0</span>
		    	<span>)</span>
		    	<div>
		    		<span>0</span>
		    		<span>/</span>
		    		<span>0</span>
		    	</div>
		    </button>
		  </li>
		  <li class="nav-item" role="presentation">
		    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
		    	Đơn Hủy
		    	<span>(</span>
		    	<span>0</span>
		    	<span>)</span>
		    </button>
		  </li>
		  <li class="date">
		  	<div>
		  		<input  readonly="" placeholder="Start date" size="12" autocomplete="off" value="">
		  	</div>
		  	<div>
		  		<i class="fa-solid fa-arrow-right"></i>
		  	</div>
		  	<div>
		  		<input readonly="" placeholder="End date" size="12" autocomplete="off" value="" >
		  	</div>
		  </li>
	</ul>
	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>ĐƠN HÀNG</th>
	                <th>kHÁCH HÀNG</th>
	                <th>NHÀ XE</th>
	                <th>XE CÔNG TY</th>
	                <th>SỐ LƯỢNG</th>
	                <th>SỐ CONT</th>
	                <th>THỜI GIAN</th>
	                <th>NƠI ĐÓNG</th>
	                <th>THỜI GIAN</th>
	                <th>NƠI TRẢ</th>
	                <th>NVTDKH</th>
	                <th>TG CẬP NHẬT</th>
	                <th>XÁC NHẬN</th>
	                <th>DUYỆT</th>
	                <th>HÀNH ĐỘNG</th>
<!-- 


	                <th>Đơn hàng</th>
	            	<th>Số lượng</th>
	            	<th>Khách hàng</th>
	                <th>Tên xe</th>
	                <th>Loại xe</th>
	                <th>Biển số xe</th>
	                <th>Tên lái xe</th>
	                <th>SĐT</th>
	                <th>Thời gian nhận hàng</th>
	                <th>Thời gian giao hàng</th>
	                <th>Giới tính</th>
	                <th>Trạng thái</th>
	                <th>Nơi trả</th>
	                <th>Hành động</th> -->
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

	<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>

	<script>
		$( function () {
	        table = $('#myTable').DataTable({
	        	lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
	            responsive: true,
	            processing: true,
	            serverSide: true,
	            "searching": false,
	            "dom": "rtip",
	            ajax: {
	                url:" {{ route('data') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'ten_donhang'},
	                {data:'khach_hang'},
	                {data:'ten_xe'},
	                {data:'xe_congty'},
	                {data:'so_don_hang'},
	                {data:'donhang'},
	                {data:'donhang'},
	                {data:'noidong'},
	                {data:'noitra'},
	                {data:'nvtdkh'},
	                {data:'donhang'},
	                {data:'donhang'},
	                {data:'donhang'},
	                {data:'donhang'},
	                {data:'action'},

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