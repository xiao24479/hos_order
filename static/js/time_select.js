$(".anniu").css("display", "block");
	$('.divdate').DatePicker({
		flat: true,
		date: [$("#start_date").val(),$("#end_date").val()],
		current: $("#start_date").val(),
		format: 'Y年m月d日',
		calendars: 2,
		mode: 'range',
		starts: 1,
		onChange: function(formated) {
			$('#inputDate').val(formated.join(' - '));
		}
	});
	$('.lxdate').DatePicker({
		flat: true,
		date: [''],
		current: '',
		format: 'Y-m-d',
		calendars: 1,
		starts: 1,
		onChange: function(formated) {
			$('#nextdate').val(formated);
			$('.date_lx').hide();
		}
	});
	$("#nextdate").focus(function(){
		$('.date_lx').css("display", "block");
		$('.date_lx .datepicker').css({"width":"210px",'height':'160px','background':'black'});
	});
  	$('.date_div').css("display", "none");
		$(".anniu .guanbi").click(function(){
		$('.date_div').css("display", "none");
	});
	$("#inputDate").focus(function(){
        $("#gaoji").hide();
		$('.date_div').css("display", "block");
        $('.date_div .datepicker').css({"width":"420px",'height':'160px','background':'black'});
	});
	$(".anniu .today").click(function(){
		$('#inputDate').val(get_day(0) + " - " + get_day(0));
		$('.date_div').css("display", "none");
	});
	$(".anniu .week").click(function(){
		$('#inputDate').val(get_day(-6) + " - " + get_day(0));
		$('.date_div').css("display", "none");
	});
	$(".anniu .month").click(function(){
		$('#inputDate').val(get_day(-29) + " - " + get_day(0));
		$('.date_div').css("display", "none");
	});
	$(".anniu .year").click(function(){
		$('#inputDate').val(get_day(-364) + " - " + get_day(0));
		$('.date_div').css("display", "none");
	});

	function get_day(day){  
	       var today = new Date();  
	       var targetday_milliseconds=today.getTime() + 1000*60*60*24*day;
	       today.setTime(targetday_milliseconds); /* 注意，这行是关键代码 */   
	       var tYear = today.getFullYear();  
	       var tMonth = today.getMonth();  
	       var tDate = today.getDate();  
	       tMonth = doHandleMonth(tMonth + 1);  
	       tDate = doHandleMonth(tDate);
	       return tYear + "年" + tMonth + "月" + tDate + "日";  
	}
	function doHandleMonth(month){  
	       var m = month;  
	       if(month.toString().length == 1){  
	          m = "0" + month;  
	       }  
	       return m;  
	}