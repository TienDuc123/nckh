@extends('admin/default')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NCKH</title>
	<link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
	<link rel="stylesheet" href="{{ asset('css/css/index.css') }}">
	<link rel="stylesheet" href="{{ asset('css/famework/flatpickr/css/flatpickr.min.css') }}">
</head>
<body>


	<style>
		.inforps{
			position: relative;
			border: solid 1px lightgray;
		}

		.show1{
			position: absolute;
		    border-radius: 5px;
		    border: solid 1px lightgray;
		    padding: 6px 14px;
		    left: 9rem;
		    top: -24px;
		}

		#myChartb{

		}
		
		.css_boder{
			border: solid 1px lightgray;
			border-radius: 100%;
			width: 45px;
			height: 45px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 14px 0;

		}
		.application_css{
			line-height: 10px;
		}
		.bor_sayruou{
			border: solid 1px lightgrey;
			position: relative;
			padding-top: 72px;
		}
		.donhoan{
			position: absolute;
			border: solid 1px lightgray;
		    padding: 8px 24px;
		    top: -22px;
		    left: 60px;
		    background: #fff;
		    border-radius: 7px;
		}
		.lienhe{
		    
		    position: absolute;
		    left: 180%;
		    top: 28%;
		    
		}
		.lienhe2{
			width: 85px;
		    height: 85px;
		    border: solid 1px lightgray;
		    border-radius: 100%;
			display: flex;
		    align-items: center;
		    justify-content: center;
		    background: aquamarine;
		}
		.lienhe svg{
			font-size: 55px;
			color: red;
			
		}
		.lienhe:hover .lienhe2{
			background: lightgreen;
		}
		.thongtin{
			display: flex;
    		justify-content: space-between;
		}
		.bieudo{
			margin-left: 94px;
		}
		.block_select{
			box-shadow: 1px 3px 12px 6px #d3d3d4;
		    text-align: center;
/*		    padding: 0 52px;*/	
		    margin-top: 15px;
		    position: absolute;
		    z-index: 5;
		    width: 186px;
    		background: #fffdfa;
    		left: -48px;
    		border-radius: 5px;
		}
		.block_select div{
			border-top: solid 1px lightgray;
			padding: 11px 0;
		}
		.block_select div:hover{
			background: lightblue;
		}
		.block_select div p{
			margin: 0;
		}
		.show_select{
			background: none;
			border: none;
			outline: noe;
			color: #0d6efd;
		}
		.css_boder:hover{
			background: lightblue;
		}
	</style>
	<div class="list_request">
		@include("admin.menu")
	</div>
	<div class="header">
		@include("admin.sibar_heder")
	</div>

	


	<ul class="nav nav-pills mb-5 list-css d-flex justify-content-around mt-3" id="pills-tab" role="tablist">
		  <li><h3>Tổng quan</h3></li>
		  <li class="nav-item" role="presentation" onclick="hide()">
		    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" onclick="chart('myChart','pills-home')">
		    	Hôm nay
		    </button>
		    
		  </li>
		  <li class="nav-item" role="presentation" onclick="hide()">
		    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" onclick="chart('myCharts','pills-profile')">
		    	7 ngày trước
		    </button>
		  </li>
		  <li class="nav-item" role="presentation" onclick="hide()">
		    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" onclick="chart('myChartb','pills-contact')">
		    	30 ngày trước
		    </button>
		  </li>
		  <li class="nav-item change_time" role="presentation" style="position: relative;padding: 7px;">
		  	<button class="show_select">Tùy chọn</button>
		  	<div class="block_select" style="display: none;">
		  		<div>
		  			<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#datePickerModal">
					  Chọn ngày
					</button> -->
		  			<!-- <button class="nav-link" id="pills-date-tab" data-bs-toggle="pill" data-bs-target="#c" type="button" role="tab" aria-controls="pills-date" aria-selected="false"> 
		    			<p>Tùy chọn</p>
		    		</button> -->
		    		<button type="button" id="show_tg" class="" data-toggle="modal" data-target="#dateRangePickerModal" style="border: none; background: none; color: #0d6efd;">
								Tùy chọn

					</button>

				</div>

		  		<div>
		  			<button class="nav-link" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" onclick="chart('myChartall','contact')" style="margin: auto;">
				    	<p>Tất cả</p>
				    </button>
		  			
		  		</div>
		  	</div>
		    <!-- <button class="nav-link" id="pills-date-tab" data-bs-toggle="pill" data-bs-target="#pills-date" type="button" role="tab" aria-controls="pills-date" aria-selected="false"> 
		    	Tùy chọn
		    </button> -->
		    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="dateRangeModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="dateRangeModalLabel" style="color: black;">Chọn khoảng thời gian</h5>
			        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			      </div>
			      <div class="modal-body">
			        <form method="POST" action="{{route('dulieuluachon')}}">
			        	@csrf
			          <div class="mb-3">
			            <label for="startDate" class="form-label" style="color: black;">Từ ngày</label>
			            <input name="ngay_batdau_chuanbi" class="chot-date form-control flatpickr flatpickr-input searchs" id="ngay_cbi_start" type="text" placeholder="Từ ngày" required onchange="chechk_time()">  
			          </div>
			          <div class="mb-3">
			            <label for="endDate" class="form-label" style="color: black;">Đến ngày</label>
			            <input name="ngay_hoanthanh_chuanbi" class="chot-date form-control flatpickr flatpickr-input searchs" id="ngay_cbi_end" type="text" placeholder="Đến ngày" required onchange="chechk_time()">
			          </div>
			        
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
				        <button class="nav-link btn btn-secondary" id="pills-date-tab" data-bs-toggle="pill" data-bs-target="#pills-date" type="button" role="tab" aria-controls="pills-date" aria-selected="false" onclick="chart('mytuychon','pills-date')" style="background: lightgreen;">Xác nhận</button>
				        <!-- <button id="show-contact">Show Contact</button> -->
				      </div>
			      </form>
			    </div>
			  </div>
			</div>
		  </li>
		  
	</ul>

	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
			<div class="container mt-5">
				<div class="row">
					
					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Thành công</div>
						<div class="tc_dh">
							<p class="dh_tc">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_tc">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="money_tc">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Đang giao</div>
						<div class="dg_dh">
							<p class="dh_dg">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_dg">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="monney_dg">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Phí vận chuyển</div>
						<div class="pvc_dh">
							<p class="phi_giao">
								<span>0 đ</span>
								<span>Giao</span>
							</p>
							<p class="phi_hoan">
								<span>0 đ</span>
								<span>Hoàn</span>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="container bor_sayruou">
				<div class="donhoan">
					Đơn hoàn
				</div>
				<div class="thongtin">
					<h4>Thông tin website liên hệ</h4>
					<h4>Biểu đồ phần trăm</h4>
					<h4>Liên hệ trực tiếp</h4>
				</div>
				<div style="display: flex;justify-content: space-around; position: relative;" class="m-auto">
					<div class="application" style="position: absolute;top: 10px;right: 174%;">
						
						<div class="application_css">
							<div class="css_boder">
								<a href="https://www.youtube.com" style="color: red; font-size: 25px;">
									<i class="fa-brands fa-youtube"></i>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.facebook.com" style="color: #0278ad; font-size: 25px;">
									<i class="fa-brands fa-facebook"></i>
								</a>
							</div>
							<div class="css_boder zalo">
								<a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F" style="font-size: 25px;" class="d-flex align-items-center justify-content-center">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(3, 90, 218)"><path opacity="0.24" fill-rule="evenodd" clip-rule="evenodd" d="M19.26 2.61428H4.71429C3.19946 2.61428 1.97144 3.8423 1.97144 5.35714V18.96C1.97144 20.4748 3.19946 21.7029 4.7143 21.7029H19.26C20.7748 21.7029 22.0029 20.4748 22.0029 18.96V5.35714C22.0029 3.8423 20.7748 2.61428 19.26 2.61428ZM4.71429 2.35714C3.05744 2.35714 1.71429 3.70029 1.71429 5.35714V18.96C1.71429 20.6169 3.05744 21.96 4.7143 21.96H19.26C20.9169 21.96 22.26 20.6169 22.26 18.96V5.35714C22.26 3.70029 20.9169 2.35714 19.26 2.35714H4.71429Z" fill=""></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6.76817 21.9497C7.16225 21.9455 7.55616 21.9414 7.94962 21.9414C7.94903 21.9371 7.94835 21.933 7.94758 21.9289C7.94836 21.9329 7.94904 21.937 7.94964 21.9412C8.03935 21.9412 8.12906 21.9456 8.21877 21.9546H14.7005C15.2388 21.9546 15.777 21.9556 16.3153 21.9566C17.3919 21.9586 18.4684 21.9606 19.545 21.9546H19.5674C21.0656 21.9412 22.2677 20.7211 22.2588 19.2229V16.5988C22.2588 16.5825 22.2599 16.5623 22.2611 16.5411C22.2649 16.4752 22.2692 16.3999 22.2408 16.4015C22.1601 16.4059 22.0328 16.4348 21.9879 16.4796C21.6439 16.709 21.3103 16.9594 20.9767 17.2099C20.3095 17.7109 19.6423 18.2118 18.8917 18.5437C15.9708 19.5291 13.3874 19.5758 10.6455 18.9134C10.4954 18.8588 10.424 18.8649 10.2972 18.8758C10.2155 18.8828 10.1108 18.8918 9.94727 18.8877L9.88519 18.8898C9.61469 18.8982 9.22197 18.9104 8.75256 19.196C7.65358 19.5324 6.16307 19.5758 5.39283 19.4606C5.39609 19.468 5.39817 19.4744 5.39979 19.4803L5.39282 19.4653C5.38211 19.4532 5.3686 19.4426 5.35551 19.4323C5.31373 19.3993 5.27624 19.3698 5.34796 19.3083C5.36889 19.2948 5.38983 19.2809 5.41076 19.2669C5.45262 19.239 5.49449 19.2111 5.53636 19.1872C6.12397 18.8059 6.6757 18.3887 7.03904 17.7742C7.53298 17.1753 7.30402 16.9418 6.86756 16.4966L6.85111 16.4798C4.72468 14.31 4.09897 12.0076 4.32075 8.91062C4.58541 7.21057 5.3659 5.74826 6.49179 4.47434C7.1736 3.70281 7.9855 3.07931 8.87365 2.55898C8.88647 2.55082 8.90141 2.54418 8.91666 2.5374C8.9601 2.51808 9.00607 2.49763 9.01271 2.43787C9.01795 2.39067 8.94214 2.37507 8.91522 2.37507C8.40904 2.37507 7.90939 2.37055 7.41206 2.36604C6.42418 2.3571 5.44547 2.34823 4.44299 2.37508C3.00311 2.41546 1.69084 3.40713 1.71461 5.1741C1.72059 8.29626 1.7186 11.4164 1.71661 14.5359C1.71561 16.0954 1.71461 17.6548 1.71461 19.2141C1.71461 20.663 2.82256 21.8786 4.27141 21.9369C5.10253 21.9673 5.93572 21.9585 6.76817 21.9497ZM5.44976 19.5633L5.4601 19.5774C5.53529 19.6441 5.60991 19.7115 5.68409 19.7793C5.61213 19.7105 5.5378 19.6417 5.46012 19.5728L5.44976 19.5633ZM7.74593 21.718C7.72097 21.7016 7.69694 21.684 7.67601 21.663L7.67007 21.6618L7.67599 21.6677C7.69693 21.6867 7.72096 21.7027 7.74593 21.718ZM9.3237 11.902H9.32402C9.75697 11.901 10.1775 11.9 10.5962 11.903C10.9505 11.9075 11.1434 12.0555 11.1793 12.3381C11.2197 12.6924 11.0133 12.9302 10.6276 12.9347C10.0827 12.9414 9.54035 12.9405 8.99674 12.9397H8.99673C8.81536 12.9394 8.63384 12.9391 8.45204 12.9391C8.39165 12.9391 8.33163 12.9399 8.27176 12.9406C8.12264 12.9424 7.97449 12.9443 7.82406 12.9347C7.56389 12.9212 7.30822 12.8674 7.18262 12.5982C7.05702 12.3291 7.14673 12.0869 7.31719 11.8671C8.00797 10.9879 8.70324 10.1042 9.39851 9.22506L9.39852 9.22504L9.39853 9.22504C9.43889 9.17122 9.47925 9.1174 9.51962 9.06807C9.48657 9.01189 9.44379 9.01657 9.40204 9.02114C9.38711 9.02277 9.37231 9.02439 9.35813 9.02321C9.11591 9.02097 8.87257 9.02097 8.62922 9.02097C8.38588 9.02097 8.14254 9.02097 7.90031 9.01873C7.78817 9.01873 7.67603 9.00527 7.56838 8.98284C7.3127 8.92453 7.1557 8.66885 7.21402 8.41766C7.25439 8.2472 7.38896 8.10815 7.55941 8.06778C7.66706 8.04086 7.7792 8.02741 7.89134 8.02741C8.68978 8.02292 9.4927 8.02292 10.2911 8.02741C10.4347 8.02292 10.5737 8.04086 10.7128 8.07675C11.0178 8.17992 11.1479 8.46251 11.0268 8.75856C10.9191 9.01424 10.7487 9.23404 10.5782 9.45383C9.99061 10.2029 9.40299 10.9475 8.81538 11.6877C8.76604 11.746 8.72118 11.8043 8.64941 11.903C8.87832 11.903 9.10262 11.9025 9.3237 11.902ZM13.8527 9.54326C13.9604 9.4042 14.0725 9.27412 14.2565 9.23823C14.6108 9.16646 14.9428 9.39523 14.9472 9.75408C14.9607 10.6512 14.9562 11.5483 14.9472 12.4455C14.9472 12.6787 14.7947 12.8851 14.5749 12.9524C14.3507 13.0376 14.095 12.9703 13.9469 12.7774C13.8707 12.6832 13.8393 12.6653 13.7316 12.7505C13.3234 13.0824 12.8614 13.1407 12.3635 12.9793C11.5651 12.7191 11.2376 12.0956 11.1479 11.3375C11.0537 10.5166 11.3273 9.81688 12.063 9.38626C12.673 9.02292 13.292 9.05432 13.8527 9.54326ZM13.0947 10.1936C13.1622 10.1965 13.2284 10.209 13.2912 10.2305C13.4264 10.2742 13.5466 10.3605 13.633 10.4808C13.8841 10.8217 13.8841 11.3824 13.633 11.7233C13.5881 11.7816 13.5388 11.8309 13.4849 11.8713C13.3474 11.9731 13.1861 12.0202 13.0273 12.0167C12.8773 12.0145 12.7265 11.9672 12.5968 11.8712C12.5429 11.8308 12.4936 11.7815 12.4487 11.7232C12.3366 11.5662 12.2738 11.3778 12.2648 11.1804C12.2603 10.548 12.6057 10.1667 13.0947 10.1936ZM16.9613 11.2074C16.9254 10.0546 17.6835 9.19338 18.76 9.16198C19.9039 9.12609 20.7382 9.89314 20.7741 11.0145C20.81 12.1494 20.1147 12.9524 19.0426 13.06C17.8719 13.1766 16.9433 12.3288 16.9613 11.2074ZM18.0872 11.0997C18.0872 11.0855 18.0874 11.0715 18.0878 11.0576C18.1034 10.5584 18.3948 10.2218 18.8153 10.1981C18.847 10.1963 18.8794 10.1963 18.9125 10.1981C19.1234 10.2026 19.3207 10.3102 19.4463 10.4807C19.4638 10.504 19.4801 10.5284 19.4952 10.5538C19.591 10.7147 19.6386 10.9136 19.6375 11.1122C19.6366 11.3372 19.5737 11.5615 19.4481 11.7284L19.4418 11.7367L19.4411 11.7375C19.3538 11.8479 19.2406 11.9264 19.1167 11.9713C18.9549 12.0314 18.7787 12.031 18.6195 11.975C18.544 11.9488 18.4717 11.9098 18.4057 11.8578C18.3569 11.8223 18.3169 11.7781 18.2814 11.7295L18.2801 11.7278C18.1456 11.544 18.0783 11.3243 18.0872 11.1002L18.0872 11.0997ZM16.5666 10.3148C16.5666 10.5465 16.5671 10.7783 16.5675 11.0101C16.5685 11.4736 16.5695 11.9371 16.5666 12.4006C16.571 12.7191 16.3198 12.9837 16.0014 12.9927C15.9475 12.9927 15.8892 12.9882 15.8354 12.9748C15.6111 12.9165 15.4407 12.6787 15.4407 12.3961V8.83004C15.4407 8.75959 15.4402 8.68965 15.4397 8.61988C15.4387 8.48082 15.4377 8.34243 15.4407 8.20205C15.4451 7.85665 15.6649 7.63237 15.9969 7.63237C16.3378 7.62788 16.5666 7.85217 16.5666 8.21102C16.5695 8.67763 16.5685 9.14624 16.5675 9.61419V9.61436C16.567 9.84811 16.5666 10.0817 16.5666 10.3148Z" fill=""></path></svg>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.tiktok.com" style="color: black; font-size: 25px;">
									<i class="fa-brands fa-tiktok"></i>
								</a>
							</div>
						</div>
					</div>
			  		<canvas id="myChart" class="bieudo"></canvas>
			  		<div class="lienhe">
				  		<div class="lienhe2">
				  			<a href="">
				  				<i class="fa-solid fa-phone-flip"></i>
				  			</a>
				  		</div>
				  		<div><a href="about:blank" style="color: red;">0368374871</a></div>
				  	</div>
			  	</div>

			  <p><strong>Từ chối nhận hàng</strong></p>

			  <div class="row">
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Đơn shop hủy</p>
			  			</div>
			  			<div class="update_if">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Không liên hệ được</p>
			  			</div>
			  			<div class="update_klh">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>KH từ chối nhận</p>
			  			</div>
			  			<div class="update_tcn">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Hoàn vì lý do khác</p>
			  			</div>
			  			<div class="update_ldk">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  </div>
			</div>
		</div>
		<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
			<div class="container mt-5">
				<div class="row">
					{{-- <div class="shadow-sm p-3 mb-5 bg-body rounded col-6 inforps">
						<div class="show1 btn btn-info">Phát sinh</div>
						<div class="ps_dh">
							<p>
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p>
								<span>0</span>
								<span>SP</span>
							</p>
							<p>
								<span>0</span>đ
							</p>
						</div>
					</div> --}}

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Thành công</div>
						<div class="tc_dh">
							<p class="dh_tc">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_tc">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="money_tc">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Đang giao</div>
						<div class="dg_dh">
							<p class="dh_dg">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_dg">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="monney_dg">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Phí vận chuyển</div>
						<div class="pvc_dh">
							<p class="phi_giao">
								<span>0 đ</span>
								<span>Giao</span>
							</p>
							<p class="phi_hoan">
								<span>0 đ</span>
								<span>Hoàn</span>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="container bor_sayruou">
				<div class="donhoan">
						Đơn hoàn
				</div>
				<div class="thongtin">
					<h4>Thông tin website liên hệ</h4>
					<h4>Biểu đồ phần trăm</h4>
					<h4>Liên hệ trực tiếp</h4>
				</div>
				<div style="display: flex;justify-content: space-around; position: relative;" class="m-auto">
					<div class="application" style="position: absolute;top: 10px;right: 174%;">
						<div class="application_css">
							<div class="css_boder">
								<a href="https://www.youtube.com" style="color: red; font-size: 25px;">
									<i class="fa-brands fa-youtube"></i>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.facebook.com" style="color: #0278ad; font-size: 25px;">
									<i class="fa-brands fa-facebook"></i>
								</a>
							</div>
							<div class="css_boder zalo">
								<a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F" style="font-size: 25px;" class="d-flex align-items-center justify-content-center">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(3, 90, 218)"><path opacity="0.24" fill-rule="evenodd" clip-rule="evenodd" d="M19.26 2.61428H4.71429C3.19946 2.61428 1.97144 3.8423 1.97144 5.35714V18.96C1.97144 20.4748 3.19946 21.7029 4.7143 21.7029H19.26C20.7748 21.7029 22.0029 20.4748 22.0029 18.96V5.35714C22.0029 3.8423 20.7748 2.61428 19.26 2.61428ZM4.71429 2.35714C3.05744 2.35714 1.71429 3.70029 1.71429 5.35714V18.96C1.71429 20.6169 3.05744 21.96 4.7143 21.96H19.26C20.9169 21.96 22.26 20.6169 22.26 18.96V5.35714C22.26 3.70029 20.9169 2.35714 19.26 2.35714H4.71429Z" fill=""></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6.76817 21.9497C7.16225 21.9455 7.55616 21.9414 7.94962 21.9414C7.94903 21.9371 7.94835 21.933 7.94758 21.9289C7.94836 21.9329 7.94904 21.937 7.94964 21.9412C8.03935 21.9412 8.12906 21.9456 8.21877 21.9546H14.7005C15.2388 21.9546 15.777 21.9556 16.3153 21.9566C17.3919 21.9586 18.4684 21.9606 19.545 21.9546H19.5674C21.0656 21.9412 22.2677 20.7211 22.2588 19.2229V16.5988C22.2588 16.5825 22.2599 16.5623 22.2611 16.5411C22.2649 16.4752 22.2692 16.3999 22.2408 16.4015C22.1601 16.4059 22.0328 16.4348 21.9879 16.4796C21.6439 16.709 21.3103 16.9594 20.9767 17.2099C20.3095 17.7109 19.6423 18.2118 18.8917 18.5437C15.9708 19.5291 13.3874 19.5758 10.6455 18.9134C10.4954 18.8588 10.424 18.8649 10.2972 18.8758C10.2155 18.8828 10.1108 18.8918 9.94727 18.8877L9.88519 18.8898C9.61469 18.8982 9.22197 18.9104 8.75256 19.196C7.65358 19.5324 6.16307 19.5758 5.39283 19.4606C5.39609 19.468 5.39817 19.4744 5.39979 19.4803L5.39282 19.4653C5.38211 19.4532 5.3686 19.4426 5.35551 19.4323C5.31373 19.3993 5.27624 19.3698 5.34796 19.3083C5.36889 19.2948 5.38983 19.2809 5.41076 19.2669C5.45262 19.239 5.49449 19.2111 5.53636 19.1872C6.12397 18.8059 6.6757 18.3887 7.03904 17.7742C7.53298 17.1753 7.30402 16.9418 6.86756 16.4966L6.85111 16.4798C4.72468 14.31 4.09897 12.0076 4.32075 8.91062C4.58541 7.21057 5.3659 5.74826 6.49179 4.47434C7.1736 3.70281 7.9855 3.07931 8.87365 2.55898C8.88647 2.55082 8.90141 2.54418 8.91666 2.5374C8.9601 2.51808 9.00607 2.49763 9.01271 2.43787C9.01795 2.39067 8.94214 2.37507 8.91522 2.37507C8.40904 2.37507 7.90939 2.37055 7.41206 2.36604C6.42418 2.3571 5.44547 2.34823 4.44299 2.37508C3.00311 2.41546 1.69084 3.40713 1.71461 5.1741C1.72059 8.29626 1.7186 11.4164 1.71661 14.5359C1.71561 16.0954 1.71461 17.6548 1.71461 19.2141C1.71461 20.663 2.82256 21.8786 4.27141 21.9369C5.10253 21.9673 5.93572 21.9585 6.76817 21.9497ZM5.44976 19.5633L5.4601 19.5774C5.53529 19.6441 5.60991 19.7115 5.68409 19.7793C5.61213 19.7105 5.5378 19.6417 5.46012 19.5728L5.44976 19.5633ZM7.74593 21.718C7.72097 21.7016 7.69694 21.684 7.67601 21.663L7.67007 21.6618L7.67599 21.6677C7.69693 21.6867 7.72096 21.7027 7.74593 21.718ZM9.3237 11.902H9.32402C9.75697 11.901 10.1775 11.9 10.5962 11.903C10.9505 11.9075 11.1434 12.0555 11.1793 12.3381C11.2197 12.6924 11.0133 12.9302 10.6276 12.9347C10.0827 12.9414 9.54035 12.9405 8.99674 12.9397H8.99673C8.81536 12.9394 8.63384 12.9391 8.45204 12.9391C8.39165 12.9391 8.33163 12.9399 8.27176 12.9406C8.12264 12.9424 7.97449 12.9443 7.82406 12.9347C7.56389 12.9212 7.30822 12.8674 7.18262 12.5982C7.05702 12.3291 7.14673 12.0869 7.31719 11.8671C8.00797 10.9879 8.70324 10.1042 9.39851 9.22506L9.39852 9.22504L9.39853 9.22504C9.43889 9.17122 9.47925 9.1174 9.51962 9.06807C9.48657 9.01189 9.44379 9.01657 9.40204 9.02114C9.38711 9.02277 9.37231 9.02439 9.35813 9.02321C9.11591 9.02097 8.87257 9.02097 8.62922 9.02097C8.38588 9.02097 8.14254 9.02097 7.90031 9.01873C7.78817 9.01873 7.67603 9.00527 7.56838 8.98284C7.3127 8.92453 7.1557 8.66885 7.21402 8.41766C7.25439 8.2472 7.38896 8.10815 7.55941 8.06778C7.66706 8.04086 7.7792 8.02741 7.89134 8.02741C8.68978 8.02292 9.4927 8.02292 10.2911 8.02741C10.4347 8.02292 10.5737 8.04086 10.7128 8.07675C11.0178 8.17992 11.1479 8.46251 11.0268 8.75856C10.9191 9.01424 10.7487 9.23404 10.5782 9.45383C9.99061 10.2029 9.40299 10.9475 8.81538 11.6877C8.76604 11.746 8.72118 11.8043 8.64941 11.903C8.87832 11.903 9.10262 11.9025 9.3237 11.902ZM13.8527 9.54326C13.9604 9.4042 14.0725 9.27412 14.2565 9.23823C14.6108 9.16646 14.9428 9.39523 14.9472 9.75408C14.9607 10.6512 14.9562 11.5483 14.9472 12.4455C14.9472 12.6787 14.7947 12.8851 14.5749 12.9524C14.3507 13.0376 14.095 12.9703 13.9469 12.7774C13.8707 12.6832 13.8393 12.6653 13.7316 12.7505C13.3234 13.0824 12.8614 13.1407 12.3635 12.9793C11.5651 12.7191 11.2376 12.0956 11.1479 11.3375C11.0537 10.5166 11.3273 9.81688 12.063 9.38626C12.673 9.02292 13.292 9.05432 13.8527 9.54326ZM13.0947 10.1936C13.1622 10.1965 13.2284 10.209 13.2912 10.2305C13.4264 10.2742 13.5466 10.3605 13.633 10.4808C13.8841 10.8217 13.8841 11.3824 13.633 11.7233C13.5881 11.7816 13.5388 11.8309 13.4849 11.8713C13.3474 11.9731 13.1861 12.0202 13.0273 12.0167C12.8773 12.0145 12.7265 11.9672 12.5968 11.8712C12.5429 11.8308 12.4936 11.7815 12.4487 11.7232C12.3366 11.5662 12.2738 11.3778 12.2648 11.1804C12.2603 10.548 12.6057 10.1667 13.0947 10.1936ZM16.9613 11.2074C16.9254 10.0546 17.6835 9.19338 18.76 9.16198C19.9039 9.12609 20.7382 9.89314 20.7741 11.0145C20.81 12.1494 20.1147 12.9524 19.0426 13.06C17.8719 13.1766 16.9433 12.3288 16.9613 11.2074ZM18.0872 11.0997C18.0872 11.0855 18.0874 11.0715 18.0878 11.0576C18.1034 10.5584 18.3948 10.2218 18.8153 10.1981C18.847 10.1963 18.8794 10.1963 18.9125 10.1981C19.1234 10.2026 19.3207 10.3102 19.4463 10.4807C19.4638 10.504 19.4801 10.5284 19.4952 10.5538C19.591 10.7147 19.6386 10.9136 19.6375 11.1122C19.6366 11.3372 19.5737 11.5615 19.4481 11.7284L19.4418 11.7367L19.4411 11.7375C19.3538 11.8479 19.2406 11.9264 19.1167 11.9713C18.9549 12.0314 18.7787 12.031 18.6195 11.975C18.544 11.9488 18.4717 11.9098 18.4057 11.8578C18.3569 11.8223 18.3169 11.7781 18.2814 11.7295L18.2801 11.7278C18.1456 11.544 18.0783 11.3243 18.0872 11.1002L18.0872 11.0997ZM16.5666 10.3148C16.5666 10.5465 16.5671 10.7783 16.5675 11.0101C16.5685 11.4736 16.5695 11.9371 16.5666 12.4006C16.571 12.7191 16.3198 12.9837 16.0014 12.9927C15.9475 12.9927 15.8892 12.9882 15.8354 12.9748C15.6111 12.9165 15.4407 12.6787 15.4407 12.3961V8.83004C15.4407 8.75959 15.4402 8.68965 15.4397 8.61988C15.4387 8.48082 15.4377 8.34243 15.4407 8.20205C15.4451 7.85665 15.6649 7.63237 15.9969 7.63237C16.3378 7.62788 16.5666 7.85217 16.5666 8.21102C16.5695 8.67763 16.5685 9.14624 16.5675 9.61419V9.61436C16.567 9.84811 16.5666 10.0817 16.5666 10.3148Z" fill=""></path></svg>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.tiktok.com" style="color: black; font-size: 25px;">
									<i class="fa-brands fa-tiktok"></i>
								</a>
							</div>
						</div>
					</div>
			  		<canvas id="myCharts" class="bieudo"></canvas>
			  		<div class="lienhe">
				  		<div class="lienhe2">
				  			<a href="">
				  				<i class="fa-solid fa-phone-flip"></i>
				  			</a>
				  		</div>
				  		<div><a href="about:blank" style="color: red;">0368374871</a></div>
				  	</div>
			  	</div>

			  <p><strong>Từ chối nhận hàng</strong></p>

			  <div class="row">
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Đơn shop hủy</p>
			  			</div>
			  			<div class="update_if">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Không liên hệ được</p>
			  			</div>
			  			<div class="update_klh">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>KH từ chối nhận</p>
			  			</div>
			  			<div class="update_tcn">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Hoàn vì lý do khác</p>
			  			</div>
			  			<div class="update_ldk">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  </div>
			</div>
		</div>
	    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
	    	<div class="container mt-5">
				<div class="row">

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Thành công</div>
						<div class="tc_dh">
							<p class="dh_tc">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_tc">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="money_tc">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Đang giao</div>
						<div class="dg_dh">
							<p class="dh_dg">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_dg">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="monney_dg">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Phí vận chuyển</div>
						<div class="pvc_dh">
							<p class="phi_giao">
								<span>0 đ</span>
								<span>Giao</span>
							</p>
							<p class="phi_hoan">
								<span>0 đ</span>
								<span>Hoàn</span>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="container bor_sayruou">
				<div class="donhoan">
					Đơn hoàn
				</div>
				<div class="thongtin">
					<h4>Thông tin website liên hệ</h4>
					<h4>Biểu đồ phần trăm</h4>
					<h4>Liên hệ trực tiếp</h4>
				</div>
				<div style="display: flex;justify-content: space-around; position: relative;" class="m-auto">
					<div class="application" style="position: absolute;top: 10px;right: 174%;">
						
						<div class="application_css">
							<div class="css_boder">
								<a href="https://www.youtube.com" style="color: red; font-size: 25px;">
									<i class="fa-brands fa-youtube"></i>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.facebook.com" style="color: #0278ad; font-size: 25px;">
									<i class="fa-brands fa-facebook"></i>
								</a>
							</div>
							<div class="css_boder zalo">
								<a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F" style="font-size: 25px;" class="d-flex align-items-center justify-content-center">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(3, 90, 218)"><path opacity="0.24" fill-rule="evenodd" clip-rule="evenodd" d="M19.26 2.61428H4.71429C3.19946 2.61428 1.97144 3.8423 1.97144 5.35714V18.96C1.97144 20.4748 3.19946 21.7029 4.7143 21.7029H19.26C20.7748 21.7029 22.0029 20.4748 22.0029 18.96V5.35714C22.0029 3.8423 20.7748 2.61428 19.26 2.61428ZM4.71429 2.35714C3.05744 2.35714 1.71429 3.70029 1.71429 5.35714V18.96C1.71429 20.6169 3.05744 21.96 4.7143 21.96H19.26C20.9169 21.96 22.26 20.6169 22.26 18.96V5.35714C22.26 3.70029 20.9169 2.35714 19.26 2.35714H4.71429Z" fill=""></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6.76817 21.9497C7.16225 21.9455 7.55616 21.9414 7.94962 21.9414C7.94903 21.9371 7.94835 21.933 7.94758 21.9289C7.94836 21.9329 7.94904 21.937 7.94964 21.9412C8.03935 21.9412 8.12906 21.9456 8.21877 21.9546H14.7005C15.2388 21.9546 15.777 21.9556 16.3153 21.9566C17.3919 21.9586 18.4684 21.9606 19.545 21.9546H19.5674C21.0656 21.9412 22.2677 20.7211 22.2588 19.2229V16.5988C22.2588 16.5825 22.2599 16.5623 22.2611 16.5411C22.2649 16.4752 22.2692 16.3999 22.2408 16.4015C22.1601 16.4059 22.0328 16.4348 21.9879 16.4796C21.6439 16.709 21.3103 16.9594 20.9767 17.2099C20.3095 17.7109 19.6423 18.2118 18.8917 18.5437C15.9708 19.5291 13.3874 19.5758 10.6455 18.9134C10.4954 18.8588 10.424 18.8649 10.2972 18.8758C10.2155 18.8828 10.1108 18.8918 9.94727 18.8877L9.88519 18.8898C9.61469 18.8982 9.22197 18.9104 8.75256 19.196C7.65358 19.5324 6.16307 19.5758 5.39283 19.4606C5.39609 19.468 5.39817 19.4744 5.39979 19.4803L5.39282 19.4653C5.38211 19.4532 5.3686 19.4426 5.35551 19.4323C5.31373 19.3993 5.27624 19.3698 5.34796 19.3083C5.36889 19.2948 5.38983 19.2809 5.41076 19.2669C5.45262 19.239 5.49449 19.2111 5.53636 19.1872C6.12397 18.8059 6.6757 18.3887 7.03904 17.7742C7.53298 17.1753 7.30402 16.9418 6.86756 16.4966L6.85111 16.4798C4.72468 14.31 4.09897 12.0076 4.32075 8.91062C4.58541 7.21057 5.3659 5.74826 6.49179 4.47434C7.1736 3.70281 7.9855 3.07931 8.87365 2.55898C8.88647 2.55082 8.90141 2.54418 8.91666 2.5374C8.9601 2.51808 9.00607 2.49763 9.01271 2.43787C9.01795 2.39067 8.94214 2.37507 8.91522 2.37507C8.40904 2.37507 7.90939 2.37055 7.41206 2.36604C6.42418 2.3571 5.44547 2.34823 4.44299 2.37508C3.00311 2.41546 1.69084 3.40713 1.71461 5.1741C1.72059 8.29626 1.7186 11.4164 1.71661 14.5359C1.71561 16.0954 1.71461 17.6548 1.71461 19.2141C1.71461 20.663 2.82256 21.8786 4.27141 21.9369C5.10253 21.9673 5.93572 21.9585 6.76817 21.9497ZM5.44976 19.5633L5.4601 19.5774C5.53529 19.6441 5.60991 19.7115 5.68409 19.7793C5.61213 19.7105 5.5378 19.6417 5.46012 19.5728L5.44976 19.5633ZM7.74593 21.718C7.72097 21.7016 7.69694 21.684 7.67601 21.663L7.67007 21.6618L7.67599 21.6677C7.69693 21.6867 7.72096 21.7027 7.74593 21.718ZM9.3237 11.902H9.32402C9.75697 11.901 10.1775 11.9 10.5962 11.903C10.9505 11.9075 11.1434 12.0555 11.1793 12.3381C11.2197 12.6924 11.0133 12.9302 10.6276 12.9347C10.0827 12.9414 9.54035 12.9405 8.99674 12.9397H8.99673C8.81536 12.9394 8.63384 12.9391 8.45204 12.9391C8.39165 12.9391 8.33163 12.9399 8.27176 12.9406C8.12264 12.9424 7.97449 12.9443 7.82406 12.9347C7.56389 12.9212 7.30822 12.8674 7.18262 12.5982C7.05702 12.3291 7.14673 12.0869 7.31719 11.8671C8.00797 10.9879 8.70324 10.1042 9.39851 9.22506L9.39852 9.22504L9.39853 9.22504C9.43889 9.17122 9.47925 9.1174 9.51962 9.06807C9.48657 9.01189 9.44379 9.01657 9.40204 9.02114C9.38711 9.02277 9.37231 9.02439 9.35813 9.02321C9.11591 9.02097 8.87257 9.02097 8.62922 9.02097C8.38588 9.02097 8.14254 9.02097 7.90031 9.01873C7.78817 9.01873 7.67603 9.00527 7.56838 8.98284C7.3127 8.92453 7.1557 8.66885 7.21402 8.41766C7.25439 8.2472 7.38896 8.10815 7.55941 8.06778C7.66706 8.04086 7.7792 8.02741 7.89134 8.02741C8.68978 8.02292 9.4927 8.02292 10.2911 8.02741C10.4347 8.02292 10.5737 8.04086 10.7128 8.07675C11.0178 8.17992 11.1479 8.46251 11.0268 8.75856C10.9191 9.01424 10.7487 9.23404 10.5782 9.45383C9.99061 10.2029 9.40299 10.9475 8.81538 11.6877C8.76604 11.746 8.72118 11.8043 8.64941 11.903C8.87832 11.903 9.10262 11.9025 9.3237 11.902ZM13.8527 9.54326C13.9604 9.4042 14.0725 9.27412 14.2565 9.23823C14.6108 9.16646 14.9428 9.39523 14.9472 9.75408C14.9607 10.6512 14.9562 11.5483 14.9472 12.4455C14.9472 12.6787 14.7947 12.8851 14.5749 12.9524C14.3507 13.0376 14.095 12.9703 13.9469 12.7774C13.8707 12.6832 13.8393 12.6653 13.7316 12.7505C13.3234 13.0824 12.8614 13.1407 12.3635 12.9793C11.5651 12.7191 11.2376 12.0956 11.1479 11.3375C11.0537 10.5166 11.3273 9.81688 12.063 9.38626C12.673 9.02292 13.292 9.05432 13.8527 9.54326ZM13.0947 10.1936C13.1622 10.1965 13.2284 10.209 13.2912 10.2305C13.4264 10.2742 13.5466 10.3605 13.633 10.4808C13.8841 10.8217 13.8841 11.3824 13.633 11.7233C13.5881 11.7816 13.5388 11.8309 13.4849 11.8713C13.3474 11.9731 13.1861 12.0202 13.0273 12.0167C12.8773 12.0145 12.7265 11.9672 12.5968 11.8712C12.5429 11.8308 12.4936 11.7815 12.4487 11.7232C12.3366 11.5662 12.2738 11.3778 12.2648 11.1804C12.2603 10.548 12.6057 10.1667 13.0947 10.1936ZM16.9613 11.2074C16.9254 10.0546 17.6835 9.19338 18.76 9.16198C19.9039 9.12609 20.7382 9.89314 20.7741 11.0145C20.81 12.1494 20.1147 12.9524 19.0426 13.06C17.8719 13.1766 16.9433 12.3288 16.9613 11.2074ZM18.0872 11.0997C18.0872 11.0855 18.0874 11.0715 18.0878 11.0576C18.1034 10.5584 18.3948 10.2218 18.8153 10.1981C18.847 10.1963 18.8794 10.1963 18.9125 10.1981C19.1234 10.2026 19.3207 10.3102 19.4463 10.4807C19.4638 10.504 19.4801 10.5284 19.4952 10.5538C19.591 10.7147 19.6386 10.9136 19.6375 11.1122C19.6366 11.3372 19.5737 11.5615 19.4481 11.7284L19.4418 11.7367L19.4411 11.7375C19.3538 11.8479 19.2406 11.9264 19.1167 11.9713C18.9549 12.0314 18.7787 12.031 18.6195 11.975C18.544 11.9488 18.4717 11.9098 18.4057 11.8578C18.3569 11.8223 18.3169 11.7781 18.2814 11.7295L18.2801 11.7278C18.1456 11.544 18.0783 11.3243 18.0872 11.1002L18.0872 11.0997ZM16.5666 10.3148C16.5666 10.5465 16.5671 10.7783 16.5675 11.0101C16.5685 11.4736 16.5695 11.9371 16.5666 12.4006C16.571 12.7191 16.3198 12.9837 16.0014 12.9927C15.9475 12.9927 15.8892 12.9882 15.8354 12.9748C15.6111 12.9165 15.4407 12.6787 15.4407 12.3961V8.83004C15.4407 8.75959 15.4402 8.68965 15.4397 8.61988C15.4387 8.48082 15.4377 8.34243 15.4407 8.20205C15.4451 7.85665 15.6649 7.63237 15.9969 7.63237C16.3378 7.62788 16.5666 7.85217 16.5666 8.21102C16.5695 8.67763 16.5685 9.14624 16.5675 9.61419V9.61436C16.567 9.84811 16.5666 10.0817 16.5666 10.3148Z" fill=""></path></svg>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.tiktok.com" style="color: black; font-size: 25px;">
									<i class="fa-brands fa-tiktok"></i>
								</a>
							</div>
						</div>
					</div>
					
						
			  			<canvas id="myChartb" class="bieudo"></canvas>
			  		<div class="lienhe">
				  		<div class="lienhe2">
				  			<a href="">
				  				<i class="fa-solid fa-phone-flip"></i>
				  			</a>
				  		</div>
				  		<div><a href="about:blank" style="color: red;">0368374871</a></div>
				  	</div>
			  	</div>

			  <p><strong>Từ chối nhận hàng</strong></p>

			  <div class="row">
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Đơn shop hủy</p>
			  			</div>
			  			<div class="update_if">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Không liên hệ được</p>
			  			</div>
			  			<div class="update_klh">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>KH từ chối nhận</p>
			  			</div>
			  			<div class="update_tcn">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Hoàn vì lý do khác</p>
			  			</div>
			  			<div class="update_ldk">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  </div>
			</div>

	    </div>
	    <div class="tab-pane fade" id="pills-date" role="tabpanel" aria-labelledby="pills-date-tab">
	    	<div class="container mt-5 tuychon">
				<div class="row">
					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Thành công</div>
						<div class="tc_dh">
							<p class="dh_tc">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_tc">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="money_tc">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Đang giao</div>
						<div class="dg_dh">
							<p class="dh_dg">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_dg">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="monney_dg">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Phí vận chuyển</div>
						<div class="pvc_dh">
							<p class="phi_giao">
								<span>0 đ</span>
								<span>Giao</span>
							</p>
							<p class="phi_hoan">
								<span>0 đ</span>
								<span>Hoàn</span>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="container bor_sayruou">
				<div class="donhoan">
					Đơn hoàn
				</div>
				<div class="thongtin">
					<h4>Thông tin website liên hệ</h4>
					<h4>Biểu đồ phần trăm</h4>
					<h4>Liên hệ trực tiếp</h4>
				</div>
				<div style="display: flex;justify-content: space-around; position: relative;" class="m-auto">
					<div class="application" style="position: absolute;top: 10px;right: 174%;">
						
						<div class="application_css">
							<div class="css_boder">
								<a href="https://www.youtube.com" style="color: red; font-size: 25px;">
									<i class="fa-brands fa-youtube"></i>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.facebook.com" style="color: #0278ad; font-size: 25px;">
									<i class="fa-brands fa-facebook"></i>
								</a>
							</div>
							<div class="css_boder zalo">
								<a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F" style="font-size: 25px;" class="d-flex align-items-center justify-content-center">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(3, 90, 218)"><path opacity="0.24" fill-rule="evenodd" clip-rule="evenodd" d="M19.26 2.61428H4.71429C3.19946 2.61428 1.97144 3.8423 1.97144 5.35714V18.96C1.97144 20.4748 3.19946 21.7029 4.7143 21.7029H19.26C20.7748 21.7029 22.0029 20.4748 22.0029 18.96V5.35714C22.0029 3.8423 20.7748 2.61428 19.26 2.61428ZM4.71429 2.35714C3.05744 2.35714 1.71429 3.70029 1.71429 5.35714V18.96C1.71429 20.6169 3.05744 21.96 4.7143 21.96H19.26C20.9169 21.96 22.26 20.6169 22.26 18.96V5.35714C22.26 3.70029 20.9169 2.35714 19.26 2.35714H4.71429Z" fill=""></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6.76817 21.9497C7.16225 21.9455 7.55616 21.9414 7.94962 21.9414C7.94903 21.9371 7.94835 21.933 7.94758 21.9289C7.94836 21.9329 7.94904 21.937 7.94964 21.9412C8.03935 21.9412 8.12906 21.9456 8.21877 21.9546H14.7005C15.2388 21.9546 15.777 21.9556 16.3153 21.9566C17.3919 21.9586 18.4684 21.9606 19.545 21.9546H19.5674C21.0656 21.9412 22.2677 20.7211 22.2588 19.2229V16.5988C22.2588 16.5825 22.2599 16.5623 22.2611 16.5411C22.2649 16.4752 22.2692 16.3999 22.2408 16.4015C22.1601 16.4059 22.0328 16.4348 21.9879 16.4796C21.6439 16.709 21.3103 16.9594 20.9767 17.2099C20.3095 17.7109 19.6423 18.2118 18.8917 18.5437C15.9708 19.5291 13.3874 19.5758 10.6455 18.9134C10.4954 18.8588 10.424 18.8649 10.2972 18.8758C10.2155 18.8828 10.1108 18.8918 9.94727 18.8877L9.88519 18.8898C9.61469 18.8982 9.22197 18.9104 8.75256 19.196C7.65358 19.5324 6.16307 19.5758 5.39283 19.4606C5.39609 19.468 5.39817 19.4744 5.39979 19.4803L5.39282 19.4653C5.38211 19.4532 5.3686 19.4426 5.35551 19.4323C5.31373 19.3993 5.27624 19.3698 5.34796 19.3083C5.36889 19.2948 5.38983 19.2809 5.41076 19.2669C5.45262 19.239 5.49449 19.2111 5.53636 19.1872C6.12397 18.8059 6.6757 18.3887 7.03904 17.7742C7.53298 17.1753 7.30402 16.9418 6.86756 16.4966L6.85111 16.4798C4.72468 14.31 4.09897 12.0076 4.32075 8.91062C4.58541 7.21057 5.3659 5.74826 6.49179 4.47434C7.1736 3.70281 7.9855 3.07931 8.87365 2.55898C8.88647 2.55082 8.90141 2.54418 8.91666 2.5374C8.9601 2.51808 9.00607 2.49763 9.01271 2.43787C9.01795 2.39067 8.94214 2.37507 8.91522 2.37507C8.40904 2.37507 7.90939 2.37055 7.41206 2.36604C6.42418 2.3571 5.44547 2.34823 4.44299 2.37508C3.00311 2.41546 1.69084 3.40713 1.71461 5.1741C1.72059 8.29626 1.7186 11.4164 1.71661 14.5359C1.71561 16.0954 1.71461 17.6548 1.71461 19.2141C1.71461 20.663 2.82256 21.8786 4.27141 21.9369C5.10253 21.9673 5.93572 21.9585 6.76817 21.9497ZM5.44976 19.5633L5.4601 19.5774C5.53529 19.6441 5.60991 19.7115 5.68409 19.7793C5.61213 19.7105 5.5378 19.6417 5.46012 19.5728L5.44976 19.5633ZM7.74593 21.718C7.72097 21.7016 7.69694 21.684 7.67601 21.663L7.67007 21.6618L7.67599 21.6677C7.69693 21.6867 7.72096 21.7027 7.74593 21.718ZM9.3237 11.902H9.32402C9.75697 11.901 10.1775 11.9 10.5962 11.903C10.9505 11.9075 11.1434 12.0555 11.1793 12.3381C11.2197 12.6924 11.0133 12.9302 10.6276 12.9347C10.0827 12.9414 9.54035 12.9405 8.99674 12.9397H8.99673C8.81536 12.9394 8.63384 12.9391 8.45204 12.9391C8.39165 12.9391 8.33163 12.9399 8.27176 12.9406C8.12264 12.9424 7.97449 12.9443 7.82406 12.9347C7.56389 12.9212 7.30822 12.8674 7.18262 12.5982C7.05702 12.3291 7.14673 12.0869 7.31719 11.8671C8.00797 10.9879 8.70324 10.1042 9.39851 9.22506L9.39852 9.22504L9.39853 9.22504C9.43889 9.17122 9.47925 9.1174 9.51962 9.06807C9.48657 9.01189 9.44379 9.01657 9.40204 9.02114C9.38711 9.02277 9.37231 9.02439 9.35813 9.02321C9.11591 9.02097 8.87257 9.02097 8.62922 9.02097C8.38588 9.02097 8.14254 9.02097 7.90031 9.01873C7.78817 9.01873 7.67603 9.00527 7.56838 8.98284C7.3127 8.92453 7.1557 8.66885 7.21402 8.41766C7.25439 8.2472 7.38896 8.10815 7.55941 8.06778C7.66706 8.04086 7.7792 8.02741 7.89134 8.02741C8.68978 8.02292 9.4927 8.02292 10.2911 8.02741C10.4347 8.02292 10.5737 8.04086 10.7128 8.07675C11.0178 8.17992 11.1479 8.46251 11.0268 8.75856C10.9191 9.01424 10.7487 9.23404 10.5782 9.45383C9.99061 10.2029 9.40299 10.9475 8.81538 11.6877C8.76604 11.746 8.72118 11.8043 8.64941 11.903C8.87832 11.903 9.10262 11.9025 9.3237 11.902ZM13.8527 9.54326C13.9604 9.4042 14.0725 9.27412 14.2565 9.23823C14.6108 9.16646 14.9428 9.39523 14.9472 9.75408C14.9607 10.6512 14.9562 11.5483 14.9472 12.4455C14.9472 12.6787 14.7947 12.8851 14.5749 12.9524C14.3507 13.0376 14.095 12.9703 13.9469 12.7774C13.8707 12.6832 13.8393 12.6653 13.7316 12.7505C13.3234 13.0824 12.8614 13.1407 12.3635 12.9793C11.5651 12.7191 11.2376 12.0956 11.1479 11.3375C11.0537 10.5166 11.3273 9.81688 12.063 9.38626C12.673 9.02292 13.292 9.05432 13.8527 9.54326ZM13.0947 10.1936C13.1622 10.1965 13.2284 10.209 13.2912 10.2305C13.4264 10.2742 13.5466 10.3605 13.633 10.4808C13.8841 10.8217 13.8841 11.3824 13.633 11.7233C13.5881 11.7816 13.5388 11.8309 13.4849 11.8713C13.3474 11.9731 13.1861 12.0202 13.0273 12.0167C12.8773 12.0145 12.7265 11.9672 12.5968 11.8712C12.5429 11.8308 12.4936 11.7815 12.4487 11.7232C12.3366 11.5662 12.2738 11.3778 12.2648 11.1804C12.2603 10.548 12.6057 10.1667 13.0947 10.1936ZM16.9613 11.2074C16.9254 10.0546 17.6835 9.19338 18.76 9.16198C19.9039 9.12609 20.7382 9.89314 20.7741 11.0145C20.81 12.1494 20.1147 12.9524 19.0426 13.06C17.8719 13.1766 16.9433 12.3288 16.9613 11.2074ZM18.0872 11.0997C18.0872 11.0855 18.0874 11.0715 18.0878 11.0576C18.1034 10.5584 18.3948 10.2218 18.8153 10.1981C18.847 10.1963 18.8794 10.1963 18.9125 10.1981C19.1234 10.2026 19.3207 10.3102 19.4463 10.4807C19.4638 10.504 19.4801 10.5284 19.4952 10.5538C19.591 10.7147 19.6386 10.9136 19.6375 11.1122C19.6366 11.3372 19.5737 11.5615 19.4481 11.7284L19.4418 11.7367L19.4411 11.7375C19.3538 11.8479 19.2406 11.9264 19.1167 11.9713C18.9549 12.0314 18.7787 12.031 18.6195 11.975C18.544 11.9488 18.4717 11.9098 18.4057 11.8578C18.3569 11.8223 18.3169 11.7781 18.2814 11.7295L18.2801 11.7278C18.1456 11.544 18.0783 11.3243 18.0872 11.1002L18.0872 11.0997ZM16.5666 10.3148C16.5666 10.5465 16.5671 10.7783 16.5675 11.0101C16.5685 11.4736 16.5695 11.9371 16.5666 12.4006C16.571 12.7191 16.3198 12.9837 16.0014 12.9927C15.9475 12.9927 15.8892 12.9882 15.8354 12.9748C15.6111 12.9165 15.4407 12.6787 15.4407 12.3961V8.83004C15.4407 8.75959 15.4402 8.68965 15.4397 8.61988C15.4387 8.48082 15.4377 8.34243 15.4407 8.20205C15.4451 7.85665 15.6649 7.63237 15.9969 7.63237C16.3378 7.62788 16.5666 7.85217 16.5666 8.21102C16.5695 8.67763 16.5685 9.14624 16.5675 9.61419V9.61436C16.567 9.84811 16.5666 10.0817 16.5666 10.3148Z" fill=""></path></svg>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.tiktok.com" style="color: black; font-size: 25px;">
									<i class="fa-brands fa-tiktok"></i>
								</a>
							</div>
						</div>
					</div>
					
						
			  			<canvas id="mytuychon" class="bieudo"></canvas>
			  		<div class="lienhe">
				  		<div class="lienhe2">
				  			<a href="">
				  				<i class="fa-solid fa-phone-flip"></i>
				  			</a>
				  		</div>
				  		<div><a href="about:blank" style="color: red;">0368374871</a></div>
				  	</div>
			  	</div>

			  <p><strong>Từ chối nhận hàng</strong></p>

			  <div class="row">
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Đơn shop hủy</p>
			  			</div>
			  			<div class="update_if">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Không liên hệ được</p>
			  			</div>
			  			<div class="update_klh">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>KH từ chối nhận</p>
			  			</div>
			  			<div class="update_tcn">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Hoàn vì lý do khác</p>
			  			</div>
			  			<div class="update_ldk">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  </div>
			</div>
	    </div>
	    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
	    	
	    	<div class="container mt-5 tatca">
				<div class="row">
					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Thành công</div>
						<div class="tc_dh">
							<p class="dh_tc">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_tc">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="money_tc">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Đang giao</div>
						<div class="dg_dh">
							<p class="dh_dg">
								<span>0</span>
								<span>Đơn Hàng</span>
							</p>
							<p class="sl_dg">
								<span>0</span>
								<span>Sản Phẩm</span>
							</p>
							<p class="monney_dg">
								<span>0</span>đ
							</p>
						</div>
					</div>

					<div class="shadow-sm p-3 mb-5 bg-body rounded col-4 inforps">
						<div class="show1 btn btn-info">Phí vận chuyển</div>
						<div class="pvc_dh">
							<p class="phi_giao">
								<span>0 đ</span>
								<span>Giao</span>
							</p>
							<p class="phi_hoan">
								<span>0 đ</span>
								<span>Hoàn</span>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="container bor_sayruou">
				<div class="donhoan">
					Đơn hoàn
				</div>
				<div class="thongtin">
					<h4>Thông tin website liên hệ</h4>
					<h4>Biểu đồ phần trăm</h4>
					<h4>Liên hệ trực tiếp</h4>
				</div>
				<div style="display: flex;justify-content: space-around; position: relative;" class="m-auto">
					<div class="application" style="position: absolute;top: 10px;right: 174%;">
						
						<div class="application_css">
							<div class="css_boder">
								<a href="https://www.youtube.com" style="color: red; font-size: 25px;">
									<i class="fa-brands fa-youtube"></i>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.facebook.com" style="color: #0278ad; font-size: 25px;">
									<i class="fa-brands fa-facebook"></i>
								</a>
							</div>
							<div class="css_boder zalo">
								<a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F" style="font-size: 25px;" class="d-flex align-items-center justify-content-center">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(3, 90, 218)"><path opacity="0.24" fill-rule="evenodd" clip-rule="evenodd" d="M19.26 2.61428H4.71429C3.19946 2.61428 1.97144 3.8423 1.97144 5.35714V18.96C1.97144 20.4748 3.19946 21.7029 4.7143 21.7029H19.26C20.7748 21.7029 22.0029 20.4748 22.0029 18.96V5.35714C22.0029 3.8423 20.7748 2.61428 19.26 2.61428ZM4.71429 2.35714C3.05744 2.35714 1.71429 3.70029 1.71429 5.35714V18.96C1.71429 20.6169 3.05744 21.96 4.7143 21.96H19.26C20.9169 21.96 22.26 20.6169 22.26 18.96V5.35714C22.26 3.70029 20.9169 2.35714 19.26 2.35714H4.71429Z" fill=""></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6.76817 21.9497C7.16225 21.9455 7.55616 21.9414 7.94962 21.9414C7.94903 21.9371 7.94835 21.933 7.94758 21.9289C7.94836 21.9329 7.94904 21.937 7.94964 21.9412C8.03935 21.9412 8.12906 21.9456 8.21877 21.9546H14.7005C15.2388 21.9546 15.777 21.9556 16.3153 21.9566C17.3919 21.9586 18.4684 21.9606 19.545 21.9546H19.5674C21.0656 21.9412 22.2677 20.7211 22.2588 19.2229V16.5988C22.2588 16.5825 22.2599 16.5623 22.2611 16.5411C22.2649 16.4752 22.2692 16.3999 22.2408 16.4015C22.1601 16.4059 22.0328 16.4348 21.9879 16.4796C21.6439 16.709 21.3103 16.9594 20.9767 17.2099C20.3095 17.7109 19.6423 18.2118 18.8917 18.5437C15.9708 19.5291 13.3874 19.5758 10.6455 18.9134C10.4954 18.8588 10.424 18.8649 10.2972 18.8758C10.2155 18.8828 10.1108 18.8918 9.94727 18.8877L9.88519 18.8898C9.61469 18.8982 9.22197 18.9104 8.75256 19.196C7.65358 19.5324 6.16307 19.5758 5.39283 19.4606C5.39609 19.468 5.39817 19.4744 5.39979 19.4803L5.39282 19.4653C5.38211 19.4532 5.3686 19.4426 5.35551 19.4323C5.31373 19.3993 5.27624 19.3698 5.34796 19.3083C5.36889 19.2948 5.38983 19.2809 5.41076 19.2669C5.45262 19.239 5.49449 19.2111 5.53636 19.1872C6.12397 18.8059 6.6757 18.3887 7.03904 17.7742C7.53298 17.1753 7.30402 16.9418 6.86756 16.4966L6.85111 16.4798C4.72468 14.31 4.09897 12.0076 4.32075 8.91062C4.58541 7.21057 5.3659 5.74826 6.49179 4.47434C7.1736 3.70281 7.9855 3.07931 8.87365 2.55898C8.88647 2.55082 8.90141 2.54418 8.91666 2.5374C8.9601 2.51808 9.00607 2.49763 9.01271 2.43787C9.01795 2.39067 8.94214 2.37507 8.91522 2.37507C8.40904 2.37507 7.90939 2.37055 7.41206 2.36604C6.42418 2.3571 5.44547 2.34823 4.44299 2.37508C3.00311 2.41546 1.69084 3.40713 1.71461 5.1741C1.72059 8.29626 1.7186 11.4164 1.71661 14.5359C1.71561 16.0954 1.71461 17.6548 1.71461 19.2141C1.71461 20.663 2.82256 21.8786 4.27141 21.9369C5.10253 21.9673 5.93572 21.9585 6.76817 21.9497ZM5.44976 19.5633L5.4601 19.5774C5.53529 19.6441 5.60991 19.7115 5.68409 19.7793C5.61213 19.7105 5.5378 19.6417 5.46012 19.5728L5.44976 19.5633ZM7.74593 21.718C7.72097 21.7016 7.69694 21.684 7.67601 21.663L7.67007 21.6618L7.67599 21.6677C7.69693 21.6867 7.72096 21.7027 7.74593 21.718ZM9.3237 11.902H9.32402C9.75697 11.901 10.1775 11.9 10.5962 11.903C10.9505 11.9075 11.1434 12.0555 11.1793 12.3381C11.2197 12.6924 11.0133 12.9302 10.6276 12.9347C10.0827 12.9414 9.54035 12.9405 8.99674 12.9397H8.99673C8.81536 12.9394 8.63384 12.9391 8.45204 12.9391C8.39165 12.9391 8.33163 12.9399 8.27176 12.9406C8.12264 12.9424 7.97449 12.9443 7.82406 12.9347C7.56389 12.9212 7.30822 12.8674 7.18262 12.5982C7.05702 12.3291 7.14673 12.0869 7.31719 11.8671C8.00797 10.9879 8.70324 10.1042 9.39851 9.22506L9.39852 9.22504L9.39853 9.22504C9.43889 9.17122 9.47925 9.1174 9.51962 9.06807C9.48657 9.01189 9.44379 9.01657 9.40204 9.02114C9.38711 9.02277 9.37231 9.02439 9.35813 9.02321C9.11591 9.02097 8.87257 9.02097 8.62922 9.02097C8.38588 9.02097 8.14254 9.02097 7.90031 9.01873C7.78817 9.01873 7.67603 9.00527 7.56838 8.98284C7.3127 8.92453 7.1557 8.66885 7.21402 8.41766C7.25439 8.2472 7.38896 8.10815 7.55941 8.06778C7.66706 8.04086 7.7792 8.02741 7.89134 8.02741C8.68978 8.02292 9.4927 8.02292 10.2911 8.02741C10.4347 8.02292 10.5737 8.04086 10.7128 8.07675C11.0178 8.17992 11.1479 8.46251 11.0268 8.75856C10.9191 9.01424 10.7487 9.23404 10.5782 9.45383C9.99061 10.2029 9.40299 10.9475 8.81538 11.6877C8.76604 11.746 8.72118 11.8043 8.64941 11.903C8.87832 11.903 9.10262 11.9025 9.3237 11.902ZM13.8527 9.54326C13.9604 9.4042 14.0725 9.27412 14.2565 9.23823C14.6108 9.16646 14.9428 9.39523 14.9472 9.75408C14.9607 10.6512 14.9562 11.5483 14.9472 12.4455C14.9472 12.6787 14.7947 12.8851 14.5749 12.9524C14.3507 13.0376 14.095 12.9703 13.9469 12.7774C13.8707 12.6832 13.8393 12.6653 13.7316 12.7505C13.3234 13.0824 12.8614 13.1407 12.3635 12.9793C11.5651 12.7191 11.2376 12.0956 11.1479 11.3375C11.0537 10.5166 11.3273 9.81688 12.063 9.38626C12.673 9.02292 13.292 9.05432 13.8527 9.54326ZM13.0947 10.1936C13.1622 10.1965 13.2284 10.209 13.2912 10.2305C13.4264 10.2742 13.5466 10.3605 13.633 10.4808C13.8841 10.8217 13.8841 11.3824 13.633 11.7233C13.5881 11.7816 13.5388 11.8309 13.4849 11.8713C13.3474 11.9731 13.1861 12.0202 13.0273 12.0167C12.8773 12.0145 12.7265 11.9672 12.5968 11.8712C12.5429 11.8308 12.4936 11.7815 12.4487 11.7232C12.3366 11.5662 12.2738 11.3778 12.2648 11.1804C12.2603 10.548 12.6057 10.1667 13.0947 10.1936ZM16.9613 11.2074C16.9254 10.0546 17.6835 9.19338 18.76 9.16198C19.9039 9.12609 20.7382 9.89314 20.7741 11.0145C20.81 12.1494 20.1147 12.9524 19.0426 13.06C17.8719 13.1766 16.9433 12.3288 16.9613 11.2074ZM18.0872 11.0997C18.0872 11.0855 18.0874 11.0715 18.0878 11.0576C18.1034 10.5584 18.3948 10.2218 18.8153 10.1981C18.847 10.1963 18.8794 10.1963 18.9125 10.1981C19.1234 10.2026 19.3207 10.3102 19.4463 10.4807C19.4638 10.504 19.4801 10.5284 19.4952 10.5538C19.591 10.7147 19.6386 10.9136 19.6375 11.1122C19.6366 11.3372 19.5737 11.5615 19.4481 11.7284L19.4418 11.7367L19.4411 11.7375C19.3538 11.8479 19.2406 11.9264 19.1167 11.9713C18.9549 12.0314 18.7787 12.031 18.6195 11.975C18.544 11.9488 18.4717 11.9098 18.4057 11.8578C18.3569 11.8223 18.3169 11.7781 18.2814 11.7295L18.2801 11.7278C18.1456 11.544 18.0783 11.3243 18.0872 11.1002L18.0872 11.0997ZM16.5666 10.3148C16.5666 10.5465 16.5671 10.7783 16.5675 11.0101C16.5685 11.4736 16.5695 11.9371 16.5666 12.4006C16.571 12.7191 16.3198 12.9837 16.0014 12.9927C15.9475 12.9927 15.8892 12.9882 15.8354 12.9748C15.6111 12.9165 15.4407 12.6787 15.4407 12.3961V8.83004C15.4407 8.75959 15.4402 8.68965 15.4397 8.61988C15.4387 8.48082 15.4377 8.34243 15.4407 8.20205C15.4451 7.85665 15.6649 7.63237 15.9969 7.63237C16.3378 7.62788 16.5666 7.85217 16.5666 8.21102C16.5695 8.67763 16.5685 9.14624 16.5675 9.61419V9.61436C16.567 9.84811 16.5666 10.0817 16.5666 10.3148Z" fill=""></path></svg>
								</a>
							</div>
							<div class="css_boder">
								<a href="https://www.tiktok.com" style="color: black; font-size: 25px;">
									<i class="fa-brands fa-tiktok"></i>
								</a>
							</div>
						</div>
					</div>
					
						
			  			<canvas id="myChartall" class="bieudo"></canvas>
			  		<div class="lienhe">
				  		<div class="lienhe2">
				  			<a href="">
				  				<i class="fa-solid fa-phone-flip"></i>
				  			</a>
				  		</div>
				  		<div><a href="about:blank" style="color: red;">0368374871</a></div>
				  	</div>
			  	</div>

			  <p><strong>Từ chối nhận hàng</strong></p>

			  <div class="row">
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Đơn shop hủy</p>
			  			</div>
			  			<div class="update_if">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Không liên hệ được</p>
			  			</div>
			  			<div class="update_klh">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>KH từ chối nhận</p>
			  			</div>
			  			<div class="update_tcn">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  		<div class="border-bottom d-flex justify-content-between">
			  			<div>
			  				<p>Hoàn vì lý do khác</p>
			  			</div>
			  			<div class="update_ldk">
			  				<span>0</span>
			  				<span>Đơn Hàng</span>
			  				<span>0%</span>
			  				<i class="fa-solid fa-chevron-right"></i>
			  			</div>
			  		</div>
			  </div>
			</div>
	    </div>
	</div>




	<!-- <script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script> -->
	<script src="{{asset('css/famework/chart.js')}}"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js"></script> -->
    <script src="{{asset('css/famework/chartjs-plugin-datalabels.min.js')}}"></script>
    <script src="{{asset('css/famework/flatpickr/js/flatpickr.min.js')}}"></script>
	<script>

		
		$(document).ready(function() {
		  // Bắt sự kiện click vào nút Show Contact
		  $('#show-contact').click(function() {
		    // Ẩn các phần tử tab-pane khác
		    $('.tab-pane').removeClass('show active');
		    // Hiển thị phần tử tab-pane có id là contact
		    $('#pills-date').addClass('show active');
		  });
		});

    	$('.dinone').on('click', function(){
    		$('.list_request').slideUp();
    	})

    	// $(document).click(function(e){
    	// 	let $target = $(e.target);

    	// 	if(!$target.closest('.wrapper').length){
    	// 		$('.list_request').stop().slideUp();
    	// 	}
    	// })

    	var options = {
    		
			  tooltips: {
			    enabled: true
			  },
			  responsive: true,
			  plugins: {
			  	legend:{
				  		position : 'bottom',
				  		// display: false,
			  		labels: {
				        font: {
				          size: 13
				        },
			        },
			  	},
	
	        
	        
			    datalabels: {
			      
			         formatter: (value, ctx) => {
		                let sum = 0;
		                let dataArr = ctx.chart.data.datasets[0].data;
		                dataArr.map(data => {
		                    sum += data;
		                });
		                let percentage = (value*100 / sum).toFixed(2)+"%";
		                return percentage;
		            },
		            color: '#fff',
		        }
			  }
			};

			var data3 = [];
			var hoaithu = false;
			function chart(a,b){
				
				var tungay = $('#ngay_cbi_start').val();
				var denngay = $('#ngay_cbi_end').val();
				$.ajax({
		            url: "{!! route('data_chart') !!}",
		            type: 'POST',
		            data:{
		            	// id_kh : id_kh,
		            	info : a,
		            	tungay : tungay,
		            	denngay : denngay,
		            	_token: '{{ csrf_token() }}',
		            },
		            error: function(err) {

		            },
		            success: function(data) {
		            	let myChart = Chart.getChart(a); // Check if myChart already exists
						if (myChart) {
						  myChart.destroy(); // Destroy myChart if it exists
						}
		            	
		            	const ctx = document.getElementById(a);
						 myChart =   new Chart(ctx, {
						    type: 'doughnut',
						    plugins: [ChartDataLabels],
						    data: {
						      labels: ['Đơn shop hủy', 'Không liên hệ được', 'Từ chối nhận', 'Khác'],
						      datasets: [{
						        label: '# Số lượng',
						        data: data,
						        borderWidth:1,
						        // backgroundColor : 'black',
						        

						      }],

						 
						      radius: 'number',
						      // hoverOffset: 4
						    },
						    options: options
						  });
						  myChart.canvas.parentNode.style.height = '400px';
						  myChart.canvas.parentNode.style.width = '400px';
						  
						hoaithu = true;  
						var dateParts = tungay.split("-");
						var day = dateParts[0];
						var month = dateParts[1];
						var dateParts = denngay.split("-");
						var day2 = dateParts[0];
						var month1 = dateParts[1];
						 if(tungay != '' && denngay != ''){
						 	$('.show_select').html(day+'-'+month+' đến '+day2+'-'+month1);
						 	$('.change_time').css({
							  position: 'relative',
							  background: '#0d6efd',
							  'border-radius': '8px',
							 
							});
							$('.block_select').css({
								display : 'none'
							});
							$('.show_select').css('color','white')
						 }

						 tuychonthoigian(tungay,denngay);
		            }




		        }); 

			
				var sum_all = 0;
		        $.ajax({
	            url: "{!! route('data_chart_if') !!}",
	            type: 'POST',
	            data:{
	            	// id_kh : id_kh,
	            	info : a,
	            	tungay : tungay,
		           	denngay : denngay,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {
	            	data.forEach(function(ez){
	            		if(ez['sum']){
	            			sum_all = ez['sum'];
	            		}
	            	})
	            
	            	data.forEach(function(e){
	            		

	            		if(e['shophuy']){
	            			
	            			
	            			$('#'+b+' .update_if').html(` 
								<span>${e['shophuy']}</span>
				  				<span>Đơn Hàng</span>
				  				<span>${(e['shophuy'] / sum_all * 100).toFixed(2)}%</span>
				  				<i class="fa-solid fa-chevron-right"></i>
							`);
						}
						if(e['khonglienhedc']){
							$('#'+b+' .update_klh').html(` 
								<span>${e['khonglienhedc']}</span>
				  				<span>Đơn Hàng</span>
				  				<span>${(e['khonglienhedc'] / sum_all * 100).toFixed(2)}%</span>
				  				<i class="fa-solid fa-chevron-right"></i>
							`);
						}

						if(e['tuchoinhan']){
							$('#'+b+' .update_tcn').html(` 
								<span>${e['tuchoinhan']}</span>
				  				<span>Đơn Hàng</span>
				  				<span>${(e['tuchoinhan'] / sum_all * 100).toFixed(2)}%</span>
				  				<i class="fa-solid fa-chevron-right"></i>
							`);
						}

						if(e['khac']){
							$('#'+b+' .update_ldk').html(` 
								<span>${e['khac']}</span>
				  				<span>Đơn Hàng</span>
				  				<span>${(e['khac'] / sum_all * 100).toFixed(2)}%</span>
				  				<i class="fa-solid fa-chevron-right"></i>
							`);
						}
	            	})
	            }


	        });    
				
				 $('#exampleModal').modal('hide');
			}

    	
    		
			$(function(){
				let a = 'myChart';
				let b = 'pills-home';
				chart(a,b);

			})
		
			// function addData(chart, data) {
			//     //chart.data.labels.push(label);
			//     chart.data.datasets.forEach((dataset) => {
			//         dataset.data.push(data);
			//     });
			//     chart.update();
			// }

			// function removeData(chart) {
			//     //chart.data.labels.pop();
			//     chart.data.datasets.forEach((dataset) => {
			//         dataset.data.pop();
			//     });
			//     chart.update();
			// }

		function thongtindonhang(){
			$.ajax({
	            url: "{!! route('thongtindonhang') !!}",
	            type: 'POST',
	            data:{
	            	// id_kh : id_kh,
	            	// phanhoi : phanhoi,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {
	            	data.forEach(function(e){

	            		if(e['tc_dh']){
	            			$('#pills-home .dh_tc').html(` 
								<span>${e['tc_dh']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['tc_sp']){
							$('#pills-home .sl_tc').html(` 
								<span>${e['tc_sp']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tc_tien']){
							$('#pills-home .money_tc').html(` 
								<span>${e['tc_tien']}</span> đ
							`);
						}
					// Đang giao		
	         			
	         			if(e['dg_dh']){
	            			$('#pills-home .dh_dg').html(` 
								<span>${e['dg_dh']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['dg_sl']){
							$('#pills-home .sl_dg').html(` 
								<span>${e['dg_sl']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['dg_tien']){
							$('#pills-home .monney_dg').html(` 
								<span>${e['dg_tien']}</span> đ
							`);
						}

						// phí vận chuyển

						if(e['phigh']){
	            			$('#pills-home .phi_giao').html(` 
								<span>${e['phigh']} đ</span>
								<span>Giao</span>
							`);
						}
						if(e['phihd']){
							$('#pills-home .phi_hoan').html(` 
								<span>${e['phihd']} đ</span>
								<span>Hoàn</span>
							`);
						}


						// 7 ngày

						if(e['tc_s_dh']){
	            			$('#pills-profile .dh_tc').html(` 
								<span>${e['tc_s_dh']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['tc_s_sl']){
							$('#pills-profile .sl_tc').html(` 
								<span>${e['tc_s_sl']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tien_s']){
							$('#pills-profile .money_tc').html(` 
								<span>${e['tien_s']}</span> đ
							`);
							// console.log(e['tien_s'])
						}
					// Đang giao		
	         			
	         			if(e['donhang_sdg']){
	            			$('#pills-profile .dh_dg').html(` 
								<span>${e['donhang_sdg']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['soluong_sdg']){
							$('#pills-profile .sl_dg').html(` 
								<span>${e['soluong_sdg']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tien_sdg']){
							$('#pills-profile .monney_dg').html(` 
								<span>${e['tien_sdg']}</span> đ
							`);
						}

						// phí vận chuyển

						if(e['phivanchuyen_sgh']){
	            			$('#pills-profile .phi_giao').html(` 
								<span>${e['phivanchuyen_sgh']} đ</span>
								<span>Giao</span>
							`);
						}
						if(e['phivanchuyen_hsd']){
							$('#pills-profile .phi_hoan').html(` 
								<span>${e['phivanchuyen_hsd']} đ</span>
								<span>Hoàn</span>
							`);
						}


						// 30 ngày
						
						if(e['donhangsevent_ttc']){
	            			$('#pills-contact .dh_tc').html(` 
								<span>${e['donhangsevent_ttc']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['soluong_s_ttc']){
							$('#pills-contact .sl_tc').html(` 
								<span>${e['soluong_s_ttc']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tien_tsevent']){
							$('#pills-contact .money_tc').html(` 
								<span>${e['tien_tsevent']}</span> đ
							`);
						}
					// Đang giao		
	         			
	         			if(e['donhang_tsdg']){
	            			$('#pills-contact .dh_dg').html(` 
								<span>${e['donhang_tsdg']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['soluong_tsdg']){
							$('#pills-contact .sl_dg').html(` 
								<span>${e['soluong_tsdg']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tien_tsdg']){
							$('#pills-contact .monney_dg').html(` 
								<span>${e['tien_tsdg']}</span> đ
							`);
						}

						// phí vận chuyển

						if(e['phivanchuyen_tsgh']){
	            			$('#pills-contact .phi_giao').html(` 
								<span>${e['phivanchuyen_tsgh']} đ</span>
								<span>Giao</span>
							`);
						}
						if(e['phivanchuyen_thsd']){
							$('#pills-contact .phi_hoan').html(` 
								<span>${e['phivanchuyen_thsd']} đ</span>
								<span>Hoàn</span>
							`);
						}

						//tất cả

						// 30 ngày
						
						if(e['donhangsevent_ttcs']){
	            			$('.tatca .dh_tc').html(` 
								<span>${e['donhangsevent_ttcs']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['soluong_s_ttcs']){
							$('.tatca .sl_tc').html(` 
								<span>${e['soluong_s_ttcs']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tien_tsevents']){
							$('.tatca .money_tc').html(` 
								<span>${e['tien_tsevents']}</span> đ
							`);
						}
							// Đang giao		
	         			
	         			if(e['donhang_tsdgs']){
	            			$('.tatca .dh_dg').html(` 
								<span>${e['donhang_tsdgs']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['soluong_tsdgs']){
							$('.tatca .sl_dg').html(` 
								<span>${e['soluong_tsdgs']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tien_tsdgs']){
							$('.tatca .monney_dg').html(` 
								<span>${e['tien_tsdgs']}</span> đ
							`);
						}

						// phí vận chuyển

						if(e['phivanchuyen_tsghs']){
	            			$('.tatca .phi_giao').html(` 
								<span>${e['phivanchuyen_tsghs']} đ</span>
								<span>Giao</span>
							`);
						}
						if(e['phivanchuyen_thsds']){
							$('.tatca .phi_hoan').html(` 
								<span>${e['phivanchuyen_thsds']} đ</span>
								<span>Hoàn</span>
							`);
						}
	            	});




	            }

	        });
		}

	thongtindonhang();	 

	$('.show_select').on('click',function(){
		$('.block_select').slideToggle();
		// if($('.block_select').is(':visible')){
		// 	$('.block_select').hide();
		// }else{
		// 	$('.block_select').show();
		// }
	})

	$('.icon').on('click', function(){
		$('.list_request').slideToggle();
	});

	flatpickr('#ngay_cbi_start', {
        dateFormat: 'd-m-Y',
      
    });

	$('#show_tg').on('click',function(){
		$('#exampleModal').modal('show');
	})
    
    
    flatpickr('#ngay_cbi_end', {
        dateFormat: 'd-m-Y',
     
    });

    function tuychonthoigian(a,b){
    	
			$('.tuychon .dh_tc').html(` 
				<span>0</span>
				<span>Đơn Hàng</span>
			`);
		
		
			$('.tuychon .sl_tc').html(` 
				<span>0</span>
				<span>Sản Phẩm</span>
			`);
		

		
			$('.tuychon .money_tc').html(` 
				<span>0</span> đ
			`);
		
		// Đang giao		
			
			
			$('.tuychon .dh_dg').html(` 
				<span>0</span>
				<span>Đơn Hàng</span>
			`);
		
		
			$('.tuychon .sl_dg').html(` 
				<span>0</span>
				<span>Sản Phẩm</span>
			`);
		

		
			$('.tuychon .monney_dg').html(` 
				<span>0</span> đ
			`);
		

		// phí vận chuyển

	
			$('.tuychon .phi_giao').html(` 
				<span>0 đ</span>
				<span>Giao</span>
			`);
		
		
			$('.tuychon .phi_hoan').html(` 
				<span>0 đ</span>
				<span>Hoàn</span>
			`);
		
    	$.ajax({
	            url: "{!! route('tuychonthoigian') !!}",
	            type: 'POST',
	            data:{
	            	tungay : a,
	            	denngay : b,
	            	_token: '{{ csrf_token() }}',
	            },
	            error: function(err) {

	            },
	            success: function(data) {
	            	data.forEach(function(e){

	            		if(e['tc_dh']){
	            			$('.tuychon .dh_tc').html(` 
								<span>${e['tc_dh']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['tc_sp']){
							$('.tuychon .sl_tc').html(` 
								<span>${e['tc_sp']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['tc_tien']){
							$('.tuychon .money_tc').html(` 
								<span>${e['tc_tien']}</span> đ
							`);
						}
						// Đang giao		
	         			
	         			if(e['dg_dh']){
	            			$('.tuychon .dh_dg').html(` 
								<span>${e['dg_dh']}</span>
								<span>Đơn Hàng</span>
							`);
						}
						if(e['dg_sl']){
							$('.tuychon .sl_dg').html(` 
								<span>${e['dg_sl']}</span>
								<span>Sản Phẩm</span>
							`);
						}

						if(e['dg_tien']){
							$('.tuychon .monney_dg').html(` 
								<span>${e['dg_tien']}</span> đ
							`);
						}

						// phí vận chuyển

						if(e['phigh']){
	            			$('.tuychon .phi_giao').html(` 
								<span>${e['phigh']} đ</span>
								<span>Giao</span>
							`);
						}
						if(e['phihd']){
							$('.tuychon .phi_hoan').html(` 
								<span>${e['phihd']} đ</span>
								<span>Hoàn</span>
							`);
						}

	            	});
	            }


	    });
    }

    $('#contact-tab').on('click',function() {
    	$(this).css('background','none');
    	$(this).css('color','#0d6efd');
    	$('.show_select').css('color','white');
    	$('.show_select').html('Tất cả');
    	$('.change_time').css({
		  position: 'relative',
		  background: '#0d6efd',
		  'border-radius': '8px',
		 
		});
		$('.block_select').css('display','none')
    })

    function hide(){
    	$('.change_time').css({
		  background: 'none',
		 
		});

		$('.show_select').css('color','#0d6efd');
    }
    $('.change_time').on('click',function() {
    	$(this).css({
    		color: 'white',
			// background: '#0d6efd',
			'border-radius': '8px',
    	})
    	// $('.show_select').css('color','white');
    })


    function chechk_time(){
    	
    	var batdau = new Date($('#ngay_cbi_start').val());
		var ketthuc = new Date($('#ngay_cbi_end').val());

		if (batdau > ketthuc) {
		    alert('Khoảng thời gian không hợp lệ vui lòng chọn lại');
		}
    }
	</script>
</body>
</html>