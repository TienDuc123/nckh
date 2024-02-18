<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" href="{{ asset('css/icon/fontawesome-free-6.2.0-web/css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('css/famework/bootstrap-5.2.2-dist/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/famework/datatables.min.css')}}">
	

</head>
<body>
	@php 
		use Illuminate\Support\Facades\Auth;
	@endphp
	
	<script src="{{asset('css/famework/jquery-3.6.0.min.js')}}"></script>
	<script src="{{ asset('css/icon/fontawesome-free-6.2.0-web/js/all.js')}}"></script>
	<script src="{{ asset('css/famework/bootstrap-5.2.2-dist/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('css/famework/datatables.min.js')}}"></script>

</body>
</html>