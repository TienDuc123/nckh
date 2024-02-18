@extends('admin/default')
<div class="request range">
	<a href="{{route('yeucau')}}">
		<i class="fa-solid fa-code-pull-request"></i>
		<span>yêu cầu</span>
	</a>
</div>

<div class="orders_request range">
	<a href="{{route('don_hoanthanh')}}">
		<i class="fa-solid fa-file-circle-check"></i>
		<span>Thông tin đơn hàng</span>
	</a>
</div>

<div class="orders_request range">
	<a href="{{route('list_car')}}">
		<i class="fa-solid fa-car"></i>
		<span>Thông tin xe</span>
	</a>
</div>

<div class="orders_request range">
	<a href="{{route('list_car_del')}}">
		<i class="fa-solid fa-truck"></i>
		<span>Xe dừng hoạt động</span>
	</a>
</div>
@if(Auth::user()->permissions == 4)
	<div class="orders_request range">
		<a href="{{route('create_account')}}">
			<i class="fa-solid fa-person"></i>
			<span>Thông tin tài khoản</span>
		</a>
	</div>

	<div class="orders_request range">
		<a href="{{route('list_client')}}">
			<i class="fa-solid fa-person"></i>
			<span>Tài khoản dừng hoạt động</span>
		</a>
	</div>
@endif

<div class="dinone"></div>
