<!DOCTYPE html>
<html lang="en" style="font-family: Acme, sans-serif;">
@extends('admin/default')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
    <title>Đăng nhập</title>
</head>

<body >
    @if(Session::get('error') != null)
        <div class="alert alert-danger" role="alert">
          <strong><i class="fa-solid fa-triangle-exclamation"></i></strong> {{Session::get('error')}}
          <a href="#" class="close" style="float: right;" data-bs-dismiss="alert" aria-label="close">&times;</a>
        </div>
    @endif
    <div style="text-align:center; margin-top: 1rem;">
        <img src="{{ asset('css/img/logo.png') }}" style="width: 8%;height: auto; object-fit:cover ;">
    </div>
    <div class="text-start d-flex" style="font-family: 'Baloo Bhaina 2', serif;">
        <div class="container d-lg-flex flex-row justify-content-lg-center align-items-lg-center mt-3" id="sign-in">
           
          
            <div class="d-xl-flex justify-content-xl-center" id="real-container" style="width: 100%;">
                <form class="text-center d-flex flex-column flex-grow-0 align-items-lg-center"  action="{{route('check_ac')}}" method="POST" id="sign-in-form" style="width: 40%;height: auto;box-shadow: -2px 1px 4px 0px;border-radius: 10px 0px 0px 10px;">
                    @csrf
                    <h1 class="text-center" style="margin-top: 35px;color: var(--bs-red);font-size: 50px;">Đăng nhập</h1>
                    <div class="text-start d-lg-flex flex-column justify-content-lg-center align-items-lg-start sign-in-input" style="width: 80%;margin-top: 50px;border-radius: 0px;"><label class="form-label" style="font-size: 20px;">Số điện thoại</label><input class="form-control" pattern="^0\d{9}$" type="text" style="width: 100%;border-radius: 10px;" name="email"></div>
                    <div class="d-lg-flex flex-column justify-content-lg-start align-items-lg-start" style="width: 80%;margin-top: 20px;"><label class="form-label" style="font-size: 20px;">Mật khẩu</label><input name = "password" class="form-control" type="password" style="width: 100%;border-radius: 10px;"></div>
                    <div class="form-check text-start" style="width: 80%;margin-top: 15px;"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1" style="font-size: 20px;">Lưu đăng nhập</label></div><button class="btn btn-primary" type="submit" style="width: 150px;margin-top: 20px;margin-bottom: 20px;background: var(--bs-danger);border-color: rgba(255,255,255,0);font-size: 20px;">Đăng nhập</button>
                    <h6 style="margin-bottom: 10px;font-size: 20px;">Không có tài khoản?<a href="{{route('dangky_ac')}}"> <span style="color: rgb(255, 3, 34);">Đăng ký ngay</span></a></h6>
                </form><img id="itc" src="{{ asset('css/img/dangnhap.jpg') }}" style="width: 40%;height: auto; object-fit:cover ;border-radius: 0px 10px 10px 0px;box-shadow: 2px 1px 4px;margin-left: -2px;">
            </div>
        </div>
    </div>
    
    <script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>
</body>

</html>