@extends('admin/default')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel='shortcut icon' href="{{ asset('css/img/logo.png') }}" />
    <title>Đăng ký</title>

</head>

<body >
    @if(Session::get('error') != null)
        <div class="alert alert-danger" role="alert">
          <strong><i class="fa-solid fa-triangle-exclamation"></i></strong> {{Session::get('error')}}
          <a href="#" class="close" style="float: right;" data-bs-dismiss="alert" aria-label="close">&times;</a>
        </div>
    @endif
    <div style="text-align:center; margin-top: 0.7rem;">
        <img src="{{ asset('css/img/logo.png') }}" style="width: 8%;height: auto; object-fit:cover ;">
    </div>

    <div class="text-start d-flex" style="font-family: 'Baloo Bhaina 2', serif;">
        <div class="container d-lg-flex flex-row justify-content-lg-center align-items-lg-center mt-2" id="sign-in">
            <div class=" d-xl-flex justify-content-xl-center" id="real-container" style="width: 100%;"><img id="itc" src="{{ asset('css/img/dangky.png') }}" style="width: 40%;height: auto;border-radius: 10px 0px 0px 10px;box-shadow: -2px 1px 4px;margin-left: -2px;">
                <form action="{{route('dangky_tc')}}" method="POST" class="text-center d-flex flex-column flex-grow-0 align-items-lg-center" id="sign-in-form" style="width: 40%;height: auto;box-shadow: 2px 1px 4px 0px;border-radius: 0px 10px 10px 0px;">
                        @csrf
                        <h1 class="text-center" style="margin-top: 30px;color: var(--bs-red);font-size: 50px;">Đăng ký</h1>
                        <div class="text-start d-lg-flex flex-column justify-content-lg-center align-items-lg-start sign-in-input" style="width: 80%;margin-top: 25px;"><label class="form-label" style="font-size: 20px;">Tên tài khoản</label><input required name="name_ac" class="form-control" type="text" style="width: 100%;border-radius: 10px;"></div>
                        <div class="text-start d-lg-flex flex-column justify-content-lg-center align-items-lg-start sign-in-input" style="width: 80%;margin-top: 25px;"><label class="form-label" style="font-size: 20px;">Địa chỉ</label><input required name="address" class="form-control" type="text" style="width: 100%;border-radius: 10px;"></div>
                        <div class="d-lg-flex flex-column justify-content-lg-start align-items-lg-start" style="width: 80%;margin-top: 20px;"><label class="form-label" style="font-size: 20px;">Số điện thoại</label><input required pattern="^0\d{9}$" name="sdt" class="form-control" type="text" style="border-radius: 10px;"></div>
                        <div class="d-lg-flex flex-column justify-content-lg-start align-items-lg-start" style="width: 80%;margin-top: 20px;"><label class="form-label" style="font-size: 20px;">Mật khẩu</label><input class="form-control" required name="matkhau" type="password" style="width: 100%;border-radius: 10px;"></div>
                        <div class="d-lg-flex flex-column justify-content-lg-start align-items-lg-start" style="width: 80%;margin-top: 20px;"><label class="form-label" style="font-size: 20px;">Nhập lại mật khẩu</label><input required name="matkhau_nl" class="form-control" type="password" style="width: 100%;border-radius: 10px;"></div><button class="btn btn-primary" type="submit" style="width: 150px;margin-top: 20px;margin-bottom: 20px;background: var(--bs-danger);border-color: rgba(255,255,255,0);font-size: 20px;">Đăng ký</button>
                        <h6 style="margin-bottom: 10px;font-size: 20px;">Đã có tài khoản? <a href="{{route('dang_nhap')}}"><span style="color: rgb(255, 3, 34);">Đăng nhập ngay</span></a></h6>
                    </form>
            </div>
        </div>
    </div>
    
    <script src="{{asset('css/famework/jquery-3.6.3.min.js')}}"></script>

</body>

</html>

