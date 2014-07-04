var loading = function(){
	
};

var opt = {
	address : 'http://localhost/server/index.php'
};

$(document).ready(function(){
	$("#refresh").click(function(){
		$.ajax({url:opt.address, type:'get', dataType:'json' , data:{'m':'process'},  async : true, 
			error: function(res){
				console.log(res);
			},
			success: function(res){
				console.log(res);
				$("#status").text(res.status+" , "+res.progress);
				
				if(res.progress == "complete")
				{	
					$("#refresh").hide();
					$("#status").append(" , click 'close' for close this window and goto view 3d");
				}
			},
		});	
	});

	if(location.search.indexOf('?') != -1)
	{
		$("#process").show();
		$("#upload").hide();
		
		$("#refresh").trigger('click');
	}

	//menu 菜单切换
	$("#menu ul li").click(function(){
		var _opt = ["upload","process"];
		var _d = ($(this).attr("data"));
		for(_k in _opt){
		if(_opt[_k] == _d) $("#"+_d).show();
		else $("#"+_opt[_k]).hide();
		}
	});
	
	//close 关闭
	$(".close").click(function(){
		$("#box",parent.document).hide();
		
		$("#loadTrigger",parent.document).trigger('click');
	});
	
	//弹出input用于创建bucket
	$(".create").click(function(){
		$("#pop").show();
	});
	
	//
	$("#bn").focus(function(){
		$(this).val("");
	});
	
	//get bucket list
	$.ajax({url:opt.address, dataType:'json' , data:{'m':'bucketlist'} , async : true, 
		error:function(res){	
			alert('get bucket list error '+res);
		},
		
		success:function(res){
			console.log(res);
			for(var _i in res)
			{
				$("#bucketOp").append("<option value = '"+res[_i]+"' >"+res[_i]+"</option>");
			}
		}
		
	});
	
	$("#bn").blur(function(){
		var _name = $(this).val();
		if(_name != '' && !confirm("are you sure use this name?"))
		{
			return;
		}
		
		$("#pop").hide();
		
		$.ajax({url:opt.address, dataType:'json', data:{'m':'create','f':_name}, async:true, 
			error:function(res){
				alert(res);
			},
			success:function(res){
				console.log(res);
				$("#bucketOp").append("<option value = '"+res.key+"' >"+res.key+"</option>");
				alert(res.key);
			},
		});
	});
	
	
	$("#formsubmit").click(function(){
		var key = $("#bucketOp option:selected").val();
		$("#uploadform").attr('action',opt.address+"?m=upload&key="+key);
		$("#uploadform").submit();
	});
});