<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php echo $msg_detail; ?></title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<link href="static/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-responsive.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
</head>

<body class="fixed-top" style="background-color:#FFFFFF;">
<div class="msg">
  <div class="img">
	<?php if($msg_type == 0):?>
    <img src="static/images/information.gif" width="32" height="32" border="0" alt="information" />
    <?php elseif($msg_type == 1):?>
    <img src="static/images/warning.gif" width="32" height="32" border="0" alt="warning" />
    <?php else:?>
    <img src="static/images/confirm.gif" width="32" height="32" border="0" alt="confirm" />
    <?php endif;?>
  </div>
  <div class="info">
  <h1><?php echo $msg_detail; ?></h1>
  <?php if($auto_redirect):?><p id="redirectionMsg"><?php echo $this->lang->line('auto_redirection');?></p><?php endif;?>
  <ul>
    <?php foreach($links as $link):?><li><a href="<?php echo $link['href']; ?>" <?php if(isset($link['target'])):?>target="<?php echo $link['target'];?>"<?php endif;?>><?php echo $link['text']; ?></a></li><?php endforeach;?>
  </ul>
  </div>
</div>
<?php if($auto_redirect):?>
<script language="JavaScript">
var seconds = <?php echo $seconds; ?>;
var defaultUrl = "<?php echo $default_url; ?>";

onload = function()
{
  if (defaultUrl == 'javascript:history.go(-1)' && window.history.length == 0)
  {
    document.getElementById('redirectionMsg').innerHTML = '';
    return;
  }

  window.setInterval(redirection, 1000);
}
function redirection()
{
  if (seconds <= 0)
  {
    window.clearInterval();
    return;
  }

  seconds --;
  document.getElementById('spanSeconds').innerHTML = seconds;

  if (seconds == 0)
  {
    window.clearInterval();
    location.href = defaultUrl;
  }
}
</script>
<?php endif;?>
</body>
</html>