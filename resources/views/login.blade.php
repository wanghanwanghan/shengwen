@extends('layouts.plane')
@section('body')

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>声纹管理系统</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">中保信联(北京)科技有限公司</p>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <form id="staff_login_form">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="staff_account" placeholder="登陆账号">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="staff_password" placeholder="登陆密码">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> 记住当前账号
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <a class="btn btn-primary btn-block btn-flat" onclick="staff_login();">登陆</a>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
    <!-- /.social-auth-links -->

    <a href="#">I forgot my password</a><br>
    <a href="#" class="text-center">Register a new membership</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });

  $("body").quietflow({
    theme : "bouncingBalls",
    specificColors : [
      "rgba(255, 10, 50, .5)",
      "rgba(10, 255, 50, .5)",
      "rgba(10, 50, 255, .5)",
      "rgba(0, 0, 0, .5)"
    ]
  })
</script>
@stop
