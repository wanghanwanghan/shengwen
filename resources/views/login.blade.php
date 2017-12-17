@extends('layouts.plane')
@section('body')

	<body>
	<div class="login-form">
		<div class="top-login">
{{--			<span><img style="width: 68px;height: 68px;" src="{{asset('public/img/guohui.jpg')}}" alt=""/></span>--}}
			<span><img style="width: 68px;height: 68px;" src="{{asset('public/img/zbxl_big_logo.png')}}" alt=""/></span>
		</div>
		<h1>社会养老保险领取资格综合认证平台</h1>
		<div class="login-top">
			{{csrf_field()}}
			<form id="staff_login_form">
				<div class="login-ic">
					<i ></i>
					<input type="text" class="form-control" name="staff_account" placeholder="登陆账号">
					<div class="clear"> </div>
				</div>
				<div class="login-ic">
					<i class="icon"></i>
					<input type="password" class="form-control" name="staff_password" placeholder="登陆密码">
					<div class="clear"> </div>
				</div>

				<div class="log-bwn">
					<a class="btn btn-block btn-danger btn-lg" onclick="staff_login();">登陆</a>
				</div>
			</form>
		</div>
		<p class="copy">© {{date('Y',time())}} 中保信联（北京）科技有限公司</p>
	</div>
	</body>

	<script>

		$(document).keyup(function (event) {

		    if (event.keyCode=='13')
			{
                staff_login();
			}

        });

	</script>

@stop