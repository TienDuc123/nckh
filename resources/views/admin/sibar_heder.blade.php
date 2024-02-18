@extends('admin/default')
<style>
	.show_logout{
		position: relative;
	}
	.infor_user{
		position: absolute;
		top: 32px;
		right: -25px;
		width: 170px;
		z-index: 2;
		background-color: #e9e9e9;
		border-radius: 10px;
		display: block;
		padding: 10px;
		border: 1px solid #a9a4a4;
	}
	.logout{
	
		height: 51px;
		display: flex;
		align-items: center;
		padding: 5px 1px 0px 0px;
		cursor: pointer;
	}
	.user-item{
		height: 52px;
		display: flex;
		align-items: center;
		border-bottom: 1px solid #a9a4a4;
		padding-bottom: 6px;
		cursor: pointer;
	}
	.infor_user{
		display: none;
	}
</style>
@php
	if(Auth::user()->permissions == 1){
		$ac = 'KH';
	}else if(Auth::user()->permissions == 2){
		$ac = 'NVTDKH';
	}else if(Auth::user()->permissions == 3){
		$ac = 'NVGH';
	}else{
		$ac = 'ADMIN';
	}
	$id_nv = Auth::user()->id_nhanvien;
@endphp

@php 
	if(Auth::user()->permissions == 1){
		$name_tk = DB::table('khach_hang')
				->where('kh_id',Auth::user()->id_nhanvien)
				->first();
		$name_tk = $name_tk->kh_ten;
	}else if(Auth::user()->permissions == 2){
		$name_tk = DB::table('nhan_vien')
				->where('id',Auth::user()->id_nhanvien)
				->first();
		$name_tk = $name_tk->ten_nhanvien;
	}else if(Auth::user()->permissions == 3){
		$name_tk = DB::table('nhanvien_giaohang')
				->where('gh_id',Auth::user()->id_nhanvien)
				->first();
		$name_tk = $name_tk->gh_tennvgh;

	}else{
		$name_tk = DB::table('admin')
				->where('id',Auth::user()->id_nhanvien)
				->first();
		$name_tk = $name_tk->ten_admin;

	}

@endphp
	
		
			<div class="icon display_ht">
				@if(Auth::user()->permissions == 2 || Auth::user()->permissions == 4)
				<i class="fa-solid fa-bars"></i>
				@endif
			</div>
		
			<div class="content-header">
				<a href="{{route('bieudo')}}">
					<strong>Trang chủ</strong>
				</a>
				@if(Auth::user()->permissions == 3 || Auth::user()->permissions == 4)
					<a href="{{route('nhanhang')}}">
						<strong>Nhận hàng</strong>
					</a>
					<!-- <a href="{{route('danhmuc')}}">
						<strong>danh mục</strong>
					</a> -->
					
					<a href="{{route('update_donhang')}}">
						<strong>Cập nhật đơn hàng</strong>
					</a>
				@endif
				@if(Auth::user()->permissions != 4)
					<a href="{{route('don_hoanthanh')}}">
						<strong>Thông tin đơn hàng</strong>
					</a>
				@endif
				@if(Auth::user()->permissions != 3)
					<a href="{{route('don_hangcho')}}">
						<strong>Đơn hàng chờ</strong>
					</a>
				@endif
				
			</div>
			<div class="img-header">
				<a href="{{route('bieudo')}}">
					<img src="{{ asset('css/img/logo.png') }}" alt="">
				</a>
			</div>
			<div style="width: 10%;">
			</div>
			<div style="border: solid 1px; text-align: center;" class="p-2 btn btn-danger">
				<a href="{{route('create_order')}}" style="width: 100%; height: 100%; display: block; color: white;">
					<i class="fa-solid fa-plus"></i>
					<strong style="margin-left: 4px;">Tạo Đơn Hàng</strong>
				</a>
			</div>
			<div class="notification">
				<!-- <span>
				    <i class="fa-solid fa-bell"></i>
				</span> -->
				<span class="span2">{{$ac}}</span>
				<div class="show_logout">
					<button class="logouts">
						<i class="fa-solid fa-right-from-bracket"></i>
					</button>
					<div class="infor_user">
						<div class="user-item">
							<span><i class="fa-solid fa-person" style="color: #10aac9;font-size: 24px;"></i></span>&nbsp;&nbsp;&nbsp;
							<span>{{$name_tk}}</span>
						</div>
						<div>

						</div>
						<div class="logout">
							<span><i class="fa-solid fa-power-off" style="color: #10aac9;font-size: 24px;"></i></span>&nbsp;&nbsp;	
							<span>Đăng xuất</span>
						</div>
					</div>
				</div>
			</div>

			<script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>
	<script>
		document.querySelector('.logout').addEventListener('click',function(e){
			location.replace("{{ route('logout') }}")
		});


		// $.ajax({
	    //     url: "{!! route('name_ac') !!}",
	    //     type: 'POST',
	    //     data:{
	    //     	id : {{$id_nv}},
	    //     	_token: '{{ csrf_token() }}',
	    //     },
	    //     error: function(err) {

	    //     },
	    //     success: function(data) {
	        	
	    //     }

	    // });

		$(function(){
			$('.logouts').on('click', function(){

	    		$('.infor_user').slideToggle();
	    	})
		})
	    
    	// document.querySelector('.logouts').addEventListener('click', function() {
		//   document.querySelector('.infor_user').classList.toggle('slide');
		// });

	</script>
