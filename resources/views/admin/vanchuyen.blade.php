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
		.edit_inf{
			border: none;
			outline: none;
			background: none;
			color: red;
			font-size: 17px;
		}
		.edit_inf:hover{
			color: blue;
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
		<h5>Danh sách xe vận chuyển</h5>
		
	</div>
	
	<div class="tab-content" id="pills-tabContent">
	  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	  		<table class="table css-table" id="myTable" style="border-collapse: collapse;"  border="1">
	            <thead>
	            <tr>
	                <th>Tên xe</th>
	                <th>Loại xe</th>
	                <th>Biển số xe</th>
	                <th>Mã nhân viên</th>
	                <th>Tên lái xe</th>
	                <th>SĐT</th>
	                <!-- <th>Giới tính</th> -->
	                <th>Trạng thái</th>
	                <th>Địa điểm</th>
	                <th>Hành động</th>
	            </tr>
	            </thead>
	            <tbody>
	            
	            </tbody>
	        </table>
	  </div>
	</div>


	<div class="modal fade show_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d29793.988211049866!2d105.8369637!3d21.022739599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1676046331286!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
	            // "searching":false,
	            // "dom": "rtip",
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
	                url:" {{ route('giaohang') }}",
	                type: 'GET',
	            },

	            columns: [ 
	                {data:'xct_ten_xe'},
	                {data:'xct_loai_xe'},
	                {data:'xct_biensoxe'},
	                {data:'manhanvien'},
	                {data:'tenlaixe'},
	                {data:'sdt'},
	                // {data:'gender'},
	                {data:'trang_thai'},
	                {data:'dia_diem'},
	                {data:'actions'},

	            ]


	        });

    	});

		$('.css-table').on('click','.edit_inf',function(){
			let id_nh = {{$id_nh}};
			let id_xct = $(this).attr('dt-id');
			let id_gh = $(this).attr('dt-gh');
			$.ajax({
				url: "{!! route('deal') !!}",
	            type: 'POST',
	            data:{
	            	id_nh : id_nh,
	            	id_xct : id_xct,
	            	id_gh : id_gh,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {	
	            	table.ajax.reload();
	            	// $('#exampleModal').modal('hide');
	            }
			});
		})
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

    	function show_map(){
    		$('.show_modal').modal('show');
    		// $('.map').css('display','block');
    	}
    	// function hide_map(){
    	// 	$('.map').css('display','none');
    	// }
	</script>
</body>
</html>