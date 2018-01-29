<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>声纹系统</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="{{asset('public/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css')}}">
	{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">--}}
	{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">--}}
	<link rel="stylesheet" href="{{asset('public/bower_components/AdminLTE/dist/css/AdminLTE.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/bower_components/AdminLTE/plugins/iCheck/square/blue.css')}}">
	<link rel="stylesheet" href="{{asset('public/bower_components/AdminLTE/dist/css/skins/skin-blue.min.css')}}">
	{{--<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>--}}
	{{--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>--}}
	<script src="{{asset('public/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
	<script src="{{asset('public/js/jquery.cookie.js')}}"></script>
	<script src="{{asset('public/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('public/bower_components/AdminLTE/dist/js/app.min.js')}}"></script>
	<script src="{{asset('public/bower_components/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>

	<script src="{{asset('public/layer/layer.js')}}"></script>
	<script src="{{asset('public/laydate/laydate.js')}}"></script>
	<script src="{{asset('public/laypage/laypage/laypage.js')}}"></script>
	<script src="{{asset('public/js/myjs.js')}}"></script>
	<link rel="stylesheet" href="{{asset('public/css/mycss.css')}}">
	<link rel="stylesheet" href="{{asset('public/zTree/css/zTreeStyle/zTreeStyle.css')}}">
	<script src="{{asset('public/zTree/js/jquery.ztree.core.min.js')}}"></script>
	<script src="{{asset('public/zTree/js/jquery.ztree.excheck.min.js')}}"></script>
	<script src="{{asset('public/background/quietflow.min.js')}}"></script>

	<script src="{{asset('public/xeditable/dist/bootstrap3-editable/css/bootstrap-editable.css')}}"></script>
	<script src="{{asset('public/xeditable/dist/bootstrap3-editable/js/bootstrap-editable.js')}}"></script>

	<link rel="stylesheet" href="{{asset('public/css/bootstrap-slider.css')}}">
	<script src="{{asset('public/js/bootstrap-slider.js')}}"></script>

	<link href="{{asset('public/css/style.css')}}" rel="stylesheet" type="text/css" media="all"/>

	{{--身份证读卡器--}}
	<script type="text/javascript" src="{{asset('public/js/baseISSObject.js')}}"></script>
	<script type="text/javascript" src="{{asset('public/js/baseISSOnline.js')}}"></script>
	<script type="text/javascript" src="{{asset('public/js/common.js')}}"></script>

	{{--指静脉--}}
	<link rel="stylesheet" href="{{asset('public/css/zhijingmai.css')}}">
	<script type="text/javascript" src="{{asset('public/js/main.js')}}"></script>
	<script type="text/javascript" src="{{asset('public/js/fingerprint.js')}}"></script>
	<script type="text/javascript" src="{{asset('public/js/baseMoth.js')}}"></script>
	<script type="text/javascript" src="{{asset('public/js/dhtmlxCommon.js')}}"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
@yield('body')
</body>
</html>
