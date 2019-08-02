<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php echo $admin['name'] . '-' . $title; ?></title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<link href="static/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-responsive.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
<link href="static/css/bootstrap-fullcalendar.css" rel="stylesheet" />
</head>
<body class="fixed-top">
<?php echo $top; ?>
<div id="container" class="row-fluid">
<?php echo $sider_menu; ?>

<div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

	  <div class="row-fluid">
<div class="span3">
	
	<div class="widget green">
<!--		<div class="widget-title">
			<h4><i class="icon-calendar"></i> Draggable Events</h4>
		</div>-->
		<div class="widget-body">
			<div id='external-events'>
				<div class='external-event label'>休息</div>
				<div class='external-event label'>发工资</div>
				<div class='external-event label'>My Event 3</div>
				<div class='external-event label'>My Event 4</div>
				<div class='external-event label'>My Event 5</div>
				<div class='external-event label'>My Event 6</div>
				<div class='external-event label'>My Event 7</div>
				<div class='external-event label'>My Event 8</div>
				<div class='external-event label'>My Event 9</div>
				<div class='external-event label'>My Event 10</div>
				<p>
					<input type='checkbox' id='drop-remove' />
					remove after drop
				</p>
			</div>
		</div>
	</div>
	
	
	</div>
	<div class="span9 responsive" data-tablet="span9 fix-margin" data-desktop="span9">
	<!-- BEGIN CALENDAR PORTLET-->
	<div class="widget yellow">
<!--		<div class="widget-title">
			<h4><i class="icon-calendar"></i> <?php echo $this->lang->line('calendar');?></h4>
			<span class="tools">
				<a href="javascript:;" class="icon-chevron-down"></a>
				<a href="javascript:;" class="icon-remove"></a>
			</span>
		</div>-->
		<div class="widget-body">
			<div id="calendar" class="has-toolbar"></div>
		</div>
	</div>
	<!-- END CALENDAR PORTLET-->
	</div>
	</div>

</div>
</div>
</div>
</div>
<script src="static/js/jquery.js"></script>
<script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="static/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="static/js/fullcalendar.min.js"></script>
<script src="static/js/bootstrap.min.js"></script>
<script src="static/js/gcal.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
<script type="text/javascript">
var Script = function () {


    /* initialize the external events
     -----------------------------------------------------------------*/

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });


    /* initialize the calendar
     -----------------------------------------------------------------*/

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
		buttonText:{
			today:    '今天',
			month:    '月',
			week:     '周',
			day:      '日'
	    },
		firstDay:1,
		monthNames:['一月','二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
		monthNamesShort:['一月','二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
		dayNames:['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
		dayNamesShort:['日', '一', '二', '三', '四', '五', '六'],
        editable: true,
        droppable: true,
		dayClick: function(date, allDay, jsEvent, view) {
			//alert(date);
		},
        eventClick: function(calEvent, jsEvent, view) {
			 //$(this).remove();
		},
		drop: function(date, allDay) {
            var originalEventObject = $(this).data('eventObject'); 
            var copiedEventObject = $.extend({}, originalEventObject);

            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            if ($('#drop-remove').is(':checked')) {
                $(this).remove();
            }

        },
        events: $.fullCalendar.gcalFeed
		("https://www.google.com/calendar/feeds/echoenhui%40gmail.com/private-b0c2919f5f9fdc11461626d40b8ce216/basic",
		  {
		   className:'gcal-event',
		   editable:true,
		   currentTimezone:'Asia/Shanghai'
		  }
		)
    });
}();
</script>
</body>
</html>