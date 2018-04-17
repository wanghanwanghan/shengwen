var ZKIDROnlineUrl = "http://127.0.0.1:22001/ZKBIOOnline/zkfvapi2";


function getZKBIOOnlineRandomNum() 
{
    var random = parseInt(Math.random() * 10000);
    return random;
}

function openDevice()
{
	$.ajax( {
		type : "GET",
		url : ZKIDROnlineUrl+"/open?randnumber=" + getZKBIOOnlineRandomNum(),
		dataType : "json",
		async: false,
		//timeout:1000,
		success : function(result) 
		{
			var ret = result.ret;
			if(ret == 0)
			{
				alert("连接成功")
			}
			else
			{
				alert("操作失败！错误码="+ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) 
		{
			alert("操作失败！");
	    }
	});
}

function closeDevice()
{
	$.ajax( {
		type : "GET",
		url : ZKIDROnlineUrl+"/close?randnumber=" + getZKBIOOnlineRandomNum(),
		dataType : "json",
		async: false,
		//timeout:1000,
		success : function(result) 
		{
			var ret = result.ret;
			if(ret == 0)
			{
				alert("关闭成功")
			}
			else
			{
				alert("操作失败！错误码="+ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) 
		{
			alert("操作失败！");
	    }
	});
}


function capture()
{
	$.ajax( {
		type : "GET",
		url : ZKIDROnlineUrl+"/capture?nfiq=3&randnumber=" + getZKBIOOnlineRandomNum(),
		dataType : "json",
		async: false,
		//timeout:1000,
		success : function(result) 
		{
			var ret = result.ret;
			if(ret == 0)
			{
				var jpgFPBase64 = result.data.fingerprint.image;
				var jpgVeinBase64 = result.data.fingervein[0].image;
				$("#featureFP").val(result.data.fingerprint.template);
				$("#featureVein").val(result.data.fingervein[0].template);
				document.getElementById("jpgFPBase64").src="data:image/png;base64,"+jpgFPBase64;
				document.getElementById("jpgVeinBase64").src="data:image/png;base64,"+jpgVeinBase64;
			}
			else
			{
				alert("操作失败！错误码="+ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) 
		{
			alert("操作失败！");
	    }
	});
}


function getFeature(obj)
{
	$.ajax( {
		type : "GET",
		url : ZKIDROnlineUrl+"/IDFP?OP-DEV=1&CMD-URL=2",
		dataType : "json",
		async: false,
		success : function(result) 
		{
			var ret = result.ret;
			if(ret == 0)
			{
				var feature = result.data.feature;
				var jpgBase64 = result.data.jpgBase64;
				$('#'+obj).val(feature);
				document.getElementById("rawBase64Img").src="data:image/png;base64,"+jpgBase64;
			}
			else
			{
				alert("操作失败！错误码="+ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown)
		{
			alert("操作失败！");
	    }
	});
}

function transferredMeaning(src)
{
	src = src.replace(/\+/g, "%2B");
	src = src.replace(/&/g, "%26");
	src = src.replace(/\%/g, "%25");
	src = src.replace(/\//g, "%2F");
	src = src.replace(/\?/g, "%3F");
	src = src.replace(/\#/g, "%23");
	src = src.replace(/\=/g, "%3D");
	src = src.replace(/\ /g, "%20");
	return src;
}


function clearData()
{
	$("#featureFP").val("");
	$("#featureVein").val("");
	$("#jpgFPBase64").val("");
	$("#jpgVeinBase64").val("");
}



function doVerifyVein()
{
	var regTemplate = "WkZWAQwNAAAAAAAAEAkIASABAACZdl1mn0IfYg9iD2YPbg7WDk4Oxw5MDsZORgbGT8wGwkeMLs6VKodm3UrdZo5uXOcOTh5mDk4e5h5uHOYeDgyOXg5Mjm6ObI7VMsVjlwONZ4dLjWePbI1kD2wdZh+uHeYeTh2sT04Nrk+cT7zHNMVhxyPBY4sji2MNIgliia4NZg0uHWwNbF1sTXxdLFxcXTzDMMsy2zKLM58zjzObI40jnSPdI90k3Sh9bF0s7WZZKG04eXjXJIsw2zSLkJ8kjzG/MZk43yHdLN0m3apZJllsWTJYbPwSeHi3EEshtwTbFb8M+RD9CNk43QzNCd0F3ElZB1hN3BV8SdkQeU2rA4kD7xDrseUA+QH9qPk5/SjZGX0snFz9BJxM3QS8TNkGuE43N0MJAAAAAIAH4EAERADgA+ABAAYA4APwAYAHAuAH8AHAPwTwB/BBwB8G8Af4/eAfAuAD/P3HDwTgAwD8/w8kAAAAPP8fDgDgABz//w/g8wEI5P8PgP8BAID/PxR/AACA/z84PgAAAGd/bB4AcIADfEAIGPgHAzwAGDz/jwEcAPz//v8DDgD/f7j/Bw7A/z8Q/g8P4Qc/ADzfB+MDHiAY/id3AD4AOPg/fwAOAADwf/MABAAE4H/hAMALxOB/wAT4A8zAeEAA8IAMQBBgAAAADEAAABAHAArgAAEAA/IB4AAAAED/A+ABAAD8/4LwAeAOfngA8AL/H3hwAHgA/7cYOAA/APfjAxDgPwDjwQUA4D8AwwEAGIB/AOcAABAAhwfDAAAIgAN/gDEeTIADf8wD/A+BAz/AA/gPgAMfgAOcHcYDD8ADDvjjAQ/gDw/I8QAP8J8DgPMAB4CfAQF/gAOAjwEBfoAHwd8HAD6ADwf+DwAegAcDno8ADsMPAw+HAI7jDwcHgkCG/w8=";
	var fpTemplate = "WkZWAQwNAAAAAAAAEAkIASABAACZdl1mn0IfYg9iD2YPbg7WDk4Oxw5MDsZORgbGT8wGwkeMLs6VKodm3UrdZo5uXOcOTh5mDk4e5h5uHOYeDgyOXg5Mjm6ObI7VMsVjlwONZ4dLjWePbI1kD2wdZh+uHeYeTh2sT04Nrk+cT7zHNMVhxyPBY4sji2MNIgliia4NZg0uHWwNbF1sTXxdLFxcXTzDMMsy2zKLM58zjzObI40jnSPdI90k3Sh9bF0s7WZZKG04eXjXJIsw2zSLkJ8kjzG/MZk43yHdLN0m3apZJllsWTJYbPwSeHi3EEshtwTbFb8M+RD9CNk43QzNCd0F3ElZB1hN3BV8SdkQeU2rA4kD7xDrseUA+QH9qPk5/SjZGX0snFz9BJxM3QS8TNkGuE43N0MJAAAAAIAH4EAERADgA+ABAAYA4APwAYAHAuAH8AHAPwTwB/BBwB8G8Af4/eAfAuAD/P3HDwTgAwD8/w8kAAAAPP8fDgDgABz//w/g8wEI5P8PgP8BAID/PxR/AACA/z84PgAAAGd/bB4AcIADfEAIGPgHAzwAGDz/jwEcAPz//v8DDgD/f7j/Bw7A/z8Q/g8P4Qc/ADzfB+MDHiAY/id3AD4AOPg/fwAOAADwf/MABAAE4H/hAMALxOB/wAT4A8zAeEAA8IAMQBBgAAAADEAAABAHAArgAAEAA/IB4AAAAED/A+ABAAD8/4LwAeAOfngA8AL/H3hwAHgA/7cYOAA/APfjAxDgPwDjwQUA4D8AwwEAGIB/AOcAABAAhwfDAAAIgAN/gDEeTIADf8wD/A+BAz/AA/gPgAMfgAOcHcYDD8ADDvjjAQ/gDw/I8QAP8J8DgPMAB4CfAQF/gAOAjwEBfoAHwd8HAD6ADwf+DwAegAcDno8ADsMPAw+HAI7jDwcHgkCG/w8=";
	$.ajax( {
		type : "POST",
		url : ZKIDROnlineUrl+"/verifyvein?threshold=72",
		dataType : "json",
		data:JSON.stringify({'reg':regTemplate,
			'ver':fpTemplate}),
		async: true,
		success : function(data) 
		{
			//返回码
			var ret = null;
 			ret = data.ret;
 			//接口调用成功返回时
 			if(ret == 0)
			{
				alert("score:" + data.score);
			}
			else
			{
				alert("ret:" + data.ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) 
		{
			alert("请安装指纹驱动或启动该服务!");
	    }
	});
}

function doVerifyFinger()
{
	var regTemplate = "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";
	var fpTemplate = "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";
	$.ajax( {
		type : "POST",
		url : ZKIDROnlineUrl+"/verifyfinger?threshold=35",
		dataType : "json",
		data:JSON.stringify({'reg':regTemplate,
			'ver':fpTemplate}),
		async: true,
		success : function(data) 
		{
			//返回码
			var ret = null;
 			ret = data.ret;
 			//接口调用成功返回时
 			if(ret == 0)
			{
				alert("score:" + data.score);
			}
			else
			{
				alert("ret:" + data.ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) 
		{
			alert("请安装指纹驱动或启动该服务!");
	    }
	});
}



function doMergeFinger()
{
	var template1 = "TxtTUzIxAAAGWFsECAUHCc7QAAAeWWkBAAAAhoVIf1ixAFAPegBEAE5SngCGADoKowByWFUIdgBrAO4JM1isAM8P0AAPAChWNgDUANcPYgD5WNMPPABiABgPrlgPAdwMTwDLAexXxAAIAQkIawAfWe8NFAB8AAkNrlg2AB8N/AAcAKldngAsAIsNrwAgWPwNoQAjAFUMtFgiAB8KeQDSAAFXowASAB4I5gAmWNoHdwCRAIoNfljaAFUPpABHAKpSrgCBADQKqgD1WNUPfQBhANoKRFh3AFUMxAArAFFZQADuANwNVABDWB0OiAAXASQPFVi4AMkPFgALAM1W8gDGAC4MZwAxWI0O0QAOAUcH2VgJAY8DYADuAfxW9AD0AKYNgQApWfQOYQA+Ac4DHVg+ANMIwADYABBUAwEYAZgKngCVWNIOoADSAJQPv1jGAEUPwABvAD9XwgDaAEQH+ACHWNUMUwDxABkNgVhVACENrACQAC5XbQAVAecP3gDSWNQOEwCeAA8PmlgfAesOdwDhAfVXZAA1APYN3gBmWNEJrwAoATgONFgZAesNHwCLAN1RxAArAQQO1wBBWM0JnAAWAE4I8VgXAZYL9QDzAY9f7wDSf3L/RPj3RtD2LJI5CAv1H3FEDhI0MA8E2Yu+JwlGBfL6aO3fsk8B3f8pB/cN66Dk577I2YknCCBU0fNhDAHxmBOkXAsAjIt5f/C3Ok/Q8AkFgf8zBbyi4ZYFlBWRRIEbU2KAwP4sA9xs11Bk+aPhOAZkBPcoUAjhkNGVHA18JdMRzJalkEAC9FD3E+sA+Qh0f3RYeIMmBu4H/XbWI1dnyIvB/M8Pe9sw+/LtvPf4AMCUfYPheSoBLBVvC5eFrQFVBxP1u6VIkk3/rfnA8SOn8AF5A+33Hfp3Xb/1gYHOAVaI8japA7X9kHso9FTYYAxxiwn7APF7UlwWpfs+GN8Rg6cwD6LsNRHPGFRBEQvl+Hr4HPjg0Pv4cAc6EF/2q99/gEYBIQ//DaymaAFBGq33V/RwVfsHfe0ZGSwBettv+MYBLQFsC++vuAQ5Bbn70wjoVA+cwfXt7lgLVlasA2EP+QO/AcyljP1xESUN8A1zTQD6Wfhm8W7Uf61kB/0HQRDcFY+ivPuKAGYT/BBbUPANAQoZG4uEnqPI8On/3G7s/Dda8QOBCy4Mf/TMqIKEtcCSjZShBNxNIEgBAtHhxAJYRQBpbQcAnAB2m8D9ngsAasVwxTjDw/+hBAB2BByb/AQA2gUQOvkFWPYgMP4cAMwGRpjBwMDAwMAFwMaYwcDAwFlMocH5XgEtB2vFxZUHBsMNE//A/mvIAJxKksPEwcPBOsHHmXMFAQEVPQTA+1wBehcDKQ3FmBHIpsJqcf8MxaAcfv/FREY5BMW1JUbBwBAAyRpV/8aaw8CdwsBqBAQGNSf0IgkAsuImxRz+MBEAoypSksCYdX7AbxIAZjKPpsKfZ8HAwaF7A1hnNfH9Kw7FqzBGWUDARcP6ygCuYiNk/sD+V0LAFliTRhf/+206wPk+wYUFAIZU3/8uVwGtWCbAPgX/YZjDQxYAfl7TJ/owMUFG/sI2yABwOGiIwVtK/9AAODndwC7//MDmwMZm/v6MEAB+oRz7pjA9OP1JDMV9bXj8KC/+VxLFemh+Jv7BMU/+BcDEpgsAd3BXZKE7D1hkdlqLPsHAAH0hMfwwDQB3vkz4PVZM/xAArbmtgJzFw5PCZ8HLAKYmrMHCw8fEB8HHmsHAwAcAskYwMygMAK2EN08E/can/8EqEgCmQzRHZUD/NMI3BsWgj2wxwAsAdJWWwGaY/8E0CQB5UEkwHP8KAMGuN53ExpppBAAzs1DwBgYtslD/RAYAvrNKp0EbAM7FtAVidZnCmJZ7xf8FwfkaBQD5xy18wAC+kTz9LiIAFg7MXgD8/8DAKfw6/fimwf//wP//OsHHmMHCwMEIAAvNMc7DNwcA083owMU8BwCc1lPAO0IBWKPWT0E0BMUz3ARPCQC/3EY5RPh1BgB33lfAOEwCWMXwXsH+BMVN8yiCBBDFAWmoBBaQBgkwAxDOy33DXhFlF23/wjjCDkizKADD/v04XAlIYyz6/n5zBfswmAUQyS0DMNcQN2L2/sFV/8M7/0U6/ggQmDwDgcBbWxFAQwzDB9WnSFeBQg==";
	var template2 = "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";
	var template3 = "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";

	$.ajax( {
		type : "POST",
		url : ZKIDROnlineUrl+"/mergefinger",
		dataType : "json",
		data:JSON.stringify({'templates':[template1,template2,template3]}),
		async: true,
		success : function(data) 
		{
			//返回码
			var ret = null;
 			ret = data.ret;
 			//接口调用成功返回时
 			if(ret == 0)
			{
				alert("template:" + data.data.template);
			}
			else
			{
				alert("ret:" + data.ret);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) 
		{
			alert("请安装指纹驱动或启动该服务!");
	    }
	});
}