<html>
<head>
	<script src="{{asset('public/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
	<script src="{{asset('public/layer/layer.js')}}"></script>
	<script type="text/javascript" src="{{asset('public/js/main.js')}}"></script>
</head>
<body>

<div class="box">

	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" id="ThisCustIdcard" value="<?php echo $_GET['idcard'] ?>">

	<div class="box-body">
		<table>
			<tr>
				<td style="width: 400px;height: 400px;" id="myimg">
					<div style="position:relative">
						<img id="myimg2" usemap="#planetmap" style="height: 400px;width: 400px" src="{{asset('public/img/zhijingmai.png')}}">
						<map name="planetmap" id="planetmap">
							<area shape="polygon" coords="37,135,47,144,48,205,38,215,26,207,28,145,37,135" onclick=$("#little_span").html('左手小指');$("#fingerNUM").html('0');>
							<area shape="polygon" coords="78,111,89,119,82,204,69,214,59,204,66,123,78,111" onclick=$("#little_span").html('左手无名指');$("#fingerNUM").html('1');>
							<area shape="polygon" coords="120,112,129,124,111,216,96,225,89,214,107,121,120,112" onclick=$("#little_span").html('左手中指');$("#fingerNUM").html('2');>
							<area shape="polygon" coords="154,159,161,171,138,237,124,242,119,231,143,163,154,159" onclick=$("#little_span").html('左手食指');$("#fingerNUM").html('3');>
							<area shape="polygon" coords="183,279,184,292,143,324,130,317,131,301,175,273,183,279" onclick=$("#little_span").html('左手拇指');$("#fingerNUM").html('4');>

							<area shape="polygon" coords="217,276,227,273,268,302,268,321,251,325,215,290,217,276" onclick=$("#little_span").html('右手拇指');$("#fingerNUM").html('5');>
							<area shape="polygon" coords="243,162,252,161,282,225,278,240,264,237,236,175,243,162" onclick=$("#little_span").html('右手食指');$("#fingerNUM").html('6');>
							<area shape="polygon" coords="279,113,291,119,311,213,302,226,290,223,271,127,279,113" onclick=$("#little_span").html('右手中指');$("#fingerNUM").html('7');>
							<area shape="polygon" coords="319,114,331,121,341,204,332,216,320,210,309,127,319,114" onclick=$("#little_span").html('右手无名指');$("#fingerNUM").html('8');>
							<area shape="polygon" coords="359,137,370,146,373,206,363,215,353,208,349,148,359,137" onclick=$("#little_span").html('右手小指');$("#fingerNUM").html('9');>
						</map>

						<div id="little_span" style="position:absolute;top:60px;left:165px;width:200px;height:20px;font-size:13px;color: red;"></div>
					</div>
					<div style="display: none" id="fingerNUM"></div>
				</td>
				<td style="width: 600px;height: 400px;">
					<table>
						<tr>
							<td width="400px" height="400px">
								<table width="400px" border="1">
									<tr>
										<td colspan="2" align="center">左手信息</td>
									</tr>

									<tr>
										<td align="center">左手拇指</td>
										<td align="center" id="4" count="0"></td>
									</tr>
									<tr>
										<td align="center">左手食指</td>
										<td align="center" id="3" count="0"></td>
									</tr>
									<tr>
										<td align="center">左手中指</td>
										<td align="center" id="2" count="0"></td>
									</tr>
									<tr>
										<td align="center">左手无名指</td>
										<td align="center" id="1" count="0"></td>
									</tr>
									<tr>
										<td align="center">左手小指</td>
										<td align="center" id="0" count="0"></td>
									</tr>
									<tr>
										<td colspan="2" align="center">右手信息</td>
									</tr>

									<tr>
										<td align="center">右手拇指</td>
										<td align="center" id="5"></td>
									</tr>
									<tr>
										<td align="center">右手食指</td>
										<td align="center" id="6"></td>
									</tr>
									<tr>
										<td align="center">右手中指</td>
										<td align="center" id="7"></td>
									</tr>
									<tr>
										<td align="center">右手无名指</td>
										<td align="center" id="8"></td>
									</tr>
									<tr>
										<td align="center">右手小指</td>
										<td align="center" id="9"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>

</div>

</body>
</html>


<script>

	$(function () {

        var url ='/data/ajax';
        var data={
            _token:$("input[name=_token]").val(),
            type  :'select_fv_cust_info',
            key   :$("#ThisCustIdcard").val()
        };

        $.post(url,data,function (response) {

            if(response.error=='0')
            {
                $("#0").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#1").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#2").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#3").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#4").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#5").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#6").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#7").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#8").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                $("#9").append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");

                $("#little_span").html(response.msg);

            }else if(response.error=='1')
            {
                var fvdata=response.fvdata;
                var fpdata=response.fpdata;

                for (var i=0;i<=9;i++)
                {
                    if (typeof fvdata[i]!=="undefined")
                    {
                        parent.$("#myFV_"+i).html(fvdata[i]);
                        $("#"+i).append("<a href='#' onclick=yanzheng($(this).parent().attr('id'));>验证</a>&nbsp;&nbsp;&nbsp;&nbsp;" +
							"<a href='#' onclick=chongzhi($('#ThisCustIdcard').val(),$(this).parent().attr('id'));>重置</a>");
                    }else
                        {
                            $("#"+i).append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");
                        }

                    if (typeof fpdata[i]!=="undefined")
                    {
                        parent.$("#myFP_"+i).html(fpdata[i]);
                    }
                }

                $("#little_span").html(response.msg);

            }else
			{

			}

        },'json');

    });

    function chongzhi(idcard,markNUM) {

        var url ='/data/ajax';
        var data={
            _token:$("input[name=_token]").val(),
            type  :'chongzhi_fv',
            key   :idcard
        };
        $.post(url,data,function (response) {

            if (response.error=='0')
            {
                parent.$("#myFV_"+markNUM).html('');
                parent.$("#myFP_"+markNUM).html('');
                $("#"+markNUM).children().remove();
                $("#"+markNUM).append("<a href='#' onclick=capture($(this).parent().attr('id'));>采集</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>0</span>");

            }else
            {
                layer.msg(response.msg);
            }

        },'json');

    }

//    $("#myimg").click(function(e) {
//        layer.msg("X: " + (e.pageX - $("#myimg2").offset().left) + ", Y: " + (e.pageY - $("#myimg2").offset().top));
//    });

</script>