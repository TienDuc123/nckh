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
		.delete_inf{
			color: red;
			border: none;
			background: none;
			outline: none;
			font-size: 17px;
		}
		.edit_inf svg{
			font-size: 17px;
		}
		.dataTables_wrapper .dataTables_filter input{
			margin-bottom: 1rem;
		}
		.close {
			border: none;
			background: transparent;
		}
	</style>
	<div class="list_request">
		@include("admin.menu")
	</div>
	<div class="header">
		@include("admin.sibar_heder")
	</div>

	<h5 style="margin: 2rem; margin-left: 7rem; margin-bottom: 0;">Yêu cầu khách hàng</h5>

	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
            		<th>Khách hàng</th>
            		<th>SĐT khách hàng</th>
	                <th>Mã sản phẩm</th>
	                <th>Tên người nhận</th>
	                <th>Số điện thoại</th>
	                <th>Nơi lấy hàng</th>
	                <th>Địa chỉ trả hàng</th>
	                <th>Tên hàng hóa</th>
	                <th>Khối lượng</th>
	                <th>Số lượng</th>
	                <th>Phí ship</th>
	                <th>Ảnh minh họa</th>
	                <th>Ghi chú</th>
	                <th>Yêu cầu</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>

	</div>

<!-- modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hủy đơn</h5>
        <button type="button" class="close border-0 btn btn-link" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" placeholder="Nhập thông tin phản hồi" style="border: none; outline: none; width: 100%;" class="phanhoi">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary feedback">Gửi</button>
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
	            // "order": [[0, "asc"], [1, "desc"]],
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
	                url:" {{ route('yeucau_khachhang') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'ten_khach_h'},
	                {data:'sdt_kh'},
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
	                {data:'yeucau'},

	            ]


	        });

    	});

    	$('.icon').on('click', function(){
    		$('.list_request').slideToggle();
    	})


    	$('.css-table').on('click','.delete_inf',function(){
    		let id_kh = $(this).attr('dt-id');
    		$('#exampleModal').modal('show');

    		$('.feedback').on('click',function(){
    			let phanhoi = $('.phanhoi').val();
    			$.ajax({
	            url: "{!! route('feedback') !!}",
	            type: 'POST',
	            data:{
	            	id_kh : id_kh,
	            	phanhoi : phanhoi,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {	
	            	table.ajax.reload();
	            	$('#exampleModal').modal('hide');
	            }

	        });
    		})
    	})
    	$('.dinone').on('click', function(){
    		$('.list_request').slideUp();
    	})

    	// $(document).click(function(e){
    	// 	let $target = $(e.target);

    	// 	if(!$target.closest('.wrapper').length){
    	// 		$('.list_request').stop().slideUp();
    	// 	}
    	// })
	</script>
</body>
</html>