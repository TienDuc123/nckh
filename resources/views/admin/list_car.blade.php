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
			.add_car{
					margin-right: 2rem;
			    border: none;
			    outline: none;
			    padding: 0 24px;
			    background: #1ab394;
			    box-shadow: 1px 1px lightgrey;
			    border-radius: 7px;
			}
			.search{
				margin-right: 110px;
			    background: #1890ff;
			    border: none;
			    border-radius: 7px;
			    box-shadow: 1px 1px grey;
			    color: white;
			}
			.delete{
				border: none;
				outline: none;
				background: none;
				color: red;
				font-size: 17px;
			}
			.complet{
				font-size: 17px;
			}
			.close {
			border: none;
			background: transparent;
		}
			.dataTables_wrapper .dataTables_filter input{
			margin-bottom: 1rem;
		}
		.excel svg{
			font-size: 34px;
    		color: #12c212;
		}
	</style>	
	<div class="list_request">
			@include("admin.menu")
	</div>
	<div class="header">
		@include("admin.sibar_heder")
	</div>
	<div class="css_listcar">	
			<h5>Danh sách xe công ty</h5>
			<div class="select">
				<button class="add_car btn btn-success">
					<i class="fa-solid fa-plus" style="color: white; margin-right: 5px;"></i>		
					<a href="{{route('add_car')}}" style="color: white;">Thêm xe</a>
				</button>
				<!-- <button class="d-flex align-items-center search btn btn-primary">
					<pre class="m-0"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</pre>
				</button> -->
			</div>
	</div>
	
	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
			<div style="display: flex;justify-content: flex-end;" class="excel">
				<a href="excel_xect" title="Xuất excel">
					<i class="fa-solid fa-file-excel btn"></i>
				</a>
			</div>
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>Tên xe</th>
	                <th>Loại xe</th>
	                <th>Biển số xe</th>
	                <th>Tên lái xe</th>
	                <th>SĐT</th>
	                <th>Giới tính</th>
	                <th>Số đăng kiểm</th>
	                <th>Hạn đăng kiểm</th>
	                <th>Định mức</th>
	                <th>Thông số kỹ thuật</th>
	                <th>Ngày sử dụng</th>
	                <th>Ảnh</th>
	                <th>Hành động</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>

	</div>

<!-- modal -->
<div class="modal fade" id="cancel_car" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Xóa xe</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="content_text" style="width: 100%; border: none; outline: none;" placeholder="Ghi chú">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary update_car">Xóa</button>
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
	                url:" {{ route('show_car') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'xct_ten_xe'},
	                {data:'xct_loai_xe'},
	                {data:'xct_biensoxe'},
	                {data:'tenlaixe'},
	                {data:'sdt'},
	                {data:'gender'},
	                {data:'xct_sodangkiem'},
	                {data:'xct_handangkiem'},
	                {data:'xct_dinhmuc'},
	                {data:'xct_thongsokythuat'},
	                {data:'created_at'},
	                {data:'anhminhhoa'},
	                {data:'actions'},

	            ]


	        });

    	});

    	$('.icon').on('click', function(){
    		$('.list_request').slideToggle();
    	})

    	$('.css-table').on('click','.delete',function(){
    		 $('.content_text').val('');
    		let id_xct = $(this).attr('dt-id');
    		$('#cancel_car').modal('show');
    		$('.update_car').on('click',function(){
    			let note = $('.content_text').val()
    			$.ajax({
						url: "{!! route('delete_car') !!}",
			            type: 'POST',
			            data:{
			            	id_xct : id_xct,
			            	note : note,
			            	_token: '{{ csrf_token() }}',
			            },
			            error: function(err) {

			            },
			            success: function(data) {
			            	$('#cancel_car').modal('hide');	
			            	table.ajax.reload();
			            	// $('#exampleModal').modal('hide');
			            }
					});
    		})
    		
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