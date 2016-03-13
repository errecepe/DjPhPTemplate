<!DOCTYPE HTML>
<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
?>

<!--
	Original HTML5 code by html5up.net
	Eventually by HTML5 UP
	html5up.net | @n33co

	Filled with php, ajax and js code by errecepe
	
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><?echo MAINTITTLE;?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	</head>
	<body>
	<script>$(document).ready(function() {	
	<?php	if ($_GET["s"]!='')	{$s=$_GET["s"];}else{$s=DEFAULTSESSIONID;} /*esto para seguir funcionando el ?s= */ ?>
		getPlayer(<?php echo $s;?>,1); // el ,1 para que no lo ponga en marcha
    }); 
	//document.ready


	function getPlayer(s,dontplayit)
					{	
						dontplayit = dontplayit || 0;
						$.ajax({
							type: "GET",
							url: 'player.php',
							data: "s=" + s,
							success: function(data){
								$('#player').html(data);
								if (dontplayit==0){mp3player.play();}
							}
						});
					}
	
				function getSesionsPorAnyo(anyo)
					{	
						$.ajax({
							type: "GET",
							url: 'sesionesporanyo.php',
							data: "y=" + anyo,
							success: function(data){
								$('#sesionesporanyo').html(data);
							}
						});
					}

</script>


		<!-- Header -->
			<header id="header">
				<div id="player">

				</div>
				
			<!--
				<ul class="icons">
					<li>aa</li>
				</ul>
				-->
				<ul class="icons">
					<li><a href="javascript:void(0);" onclick="getSesionsPorAnyo(2015)"><span class="label">2015</span></a></li>
				</ul>

				<div id="sesionesporanyo">
					
				</div>
			</header>
		<!-- Footer -->
			<footer id="footer">
				<!-- <ul class="icons"><li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li></ul> -->
				<ul class="copyright">
					<li><?echo MAINTITTLE;?> by <a href='<?echo CONTACTURL;?>' target='_blank'><?echo DJNAME;?></a></li>
					<? if (CONTACTURL2!=''){?>
					<li><a href='<?echo CONTACTURL2;?>' target='blank'><?echo CONTACTURL2DESC;?></a></li>
					<?}?>
					<? if (CONTACTURL3!=''){?>
					<li><a href='<?echo CONTACTURL3;?>' target='blank'><?echo CONTACTURL3DESC;?></a></li>
					<?}?>
					<li><a href='mailto:<?echo CONTACTEMAIL;?>'><?echo CONTACTEMAIL;?></a></li>
					<li>Original template: <a href="http://html5up.net">HTML5 UP</a></li>
					<li>DjPhPTemplate 0.2 coded by: <a href='https://twitter.com/errecepe' target='_blank'>@errecepe</li>
					<?if (SHOWLOGINLINK) echo "<li><a href='djadmin_manage.php' target='_blank'>login</li>";?>
				</ul>
			</footer>
		<!-- Scripts -->
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>