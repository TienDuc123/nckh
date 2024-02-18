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
		.close {
			border: none;
			background: transparent;
		}
		.note{
			width: 100%;
			border: none;
			outline: none;
		}
		.delete{
			width: 100%;
			border: none;
			outline: none;
		}
		.complet,.hoandon,.delete_inf{
			border: none;
	    	outline: none;
	    	font-size: 16px;
	    	background: none;
		}
		.complet{
			color: blue;
		}	
		.hoandon{
			color: green;
		}
		.delete_inf{
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

	
	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>Khách hàng</th>
	            		<th>SĐT khách hàng</th>
	                <th>Mã sản phẩm</th>
	                <th>Tên người nhận</th>
	                <th>SĐT người nhận</th>
	                <th>Nơi lấy hàng</th>
	                <th>Địa chỉ trả hàng</th>
	                <th>Tên hàng hóa</th>
	                <th>Khối lượng</th>
	                <th>Số lượng</th>
	                <th>Phí vận chuyển</th>
	                <th>Ảnh minh họa</th>
	                <th>Ghi chú</th>
	                <th>Hành động</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>

	</div>


	<!-- Modal hoàn đơn -->
<div class="modal fade" id="hoandon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hoàn đơn</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		
      </div>
	  <div style="margin-left: 1rem;">
		<input type="radio" name="donhang" id="male" checked value="không liên hệ được">
		<i class="fa-solid fa-phone-slash" style="color: red" title="Không liên hệ được"></i>&ensp;
	  <input type="radio" name="donhang" value="từ chối nhận">
	  <i class="fa-solid fa-user-large-slash" style="color: mediumblue" title="Khách hàng từ chối nhận"></i>
	  
	</div>
      
	  <div class="modal-body">
        <input type="text" class="note" placeholder="Phản hồi">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary update_hoandon">Cập nhật</button>
      </div>
    </div>
  </div>
</div>

	<!-- Modal hoàn đơn -->
<div class="modal fade" id="huydon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ghi chú</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="delete" placeholder="Phản hồi">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary update_huydon">Cập nhật</button>
      </div>
    </div>
  </div>
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
	                url:" {{ route('request_yeucau') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'tenkhachhang'},
	                {data:'sodienthoai'},
	                {data:'nh_masanpham'},
	                {data:'nh_tenkhachhang'},
	                {data:'nh_SĐT'},
	                {data:'nh_noilayhang'},
	                {data:'nh_diachi'},
	                {data:'nh_tenhanghoa'},
	                {data:'nh_khoiluong'},
	                {data:'nh_soluong'},
	                {data:'nh_phiship'},
	                {data:'anhminhhoa'},
	                {data:'nh_ghichu'},
	                {data:'actions'},

	            ]


	        });

    	});

    	$('.icon').on('click', function(){
    		$('.list_request').slideToggle();
    	})

    	$('.css-table').on('click','.complet',function(){
    		let id_nh = $(this).attr('dt-id');

    		$.ajax({
		            url: "{!! route('update_status') !!}",
		            type: 'POST',
		            data:{
		            	id_nh : id_nh,
		            	_token: '{{ csrf_token() }}',
		            },
		            error: function(err) {

		            },
		            success: function(data) {
		            	table.ajax.reload();
		            }

		        });
    	});

    	$('.css-table').on('click','.hoandon',function(){
    		let id_nh = $(this).attr('dt-id');
    		
    		$('#hoandon').modal('show');
    		$('.update_hoandon').on('click',function(){
    			var isMaleChecked = $('input[name="donhang"][value="không liên hệ được"]').is(":checked");
					if (isMaleChecked) {
					  var maleValue = "không liên hệ được";
					}else{
						var maleValue = "từ chối nhận";
					}
    			let value_hoandon = $('.note').val();
    			$.ajax({
		            url: "{!! route('update_hoandon') !!}",
		            type: 'POST',
		            data:{
		            	id_nh : id_nh,
		            	value_hoandon : value_hoandon,
		            	maleValue : maleValue,
		            	_token: '{{ csrf_token() }}',
		            },
		            error: function(err) {

		            },
		            success: function(data) {
		            	$('#hoandon').modal('hide');
		            	table.ajax.reload();
		            }

		        });
    		});
    		
    	});

    	$('.css-table').on('click','.delete_inf',function(){
    		let id_nh = $(this).attr('dt-id');
    		$('#huydon').modal('show');
    		$('.update_huydon').on('click',function(){
    			let value_huydon = $('.delete').val();
    			$.ajax({
		            url: "{!! route('update_huydon') !!}",
		            type: 'POST',
		            data:{
		            	id_nh : id_nh,
		            	value_huydon : value_huydon,
		            	_token: '{{ csrf_token() }}',
		            },
		            error: function(err) {

		            },
		            success: function(data) {
		            	$('#huydon').modal('hide');
		            	table.ajax.reload();
		            }

		        });
    		});
    		
    	});

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