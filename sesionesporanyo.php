<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
//***************************
	
	//muestra las sesiones del año $y
	$y=$_GET["y"];
	if (is_numeric($y))
	{
		
									
		if ($stmt = $mysqli->query("select * from ".PFXTBL."_sesion where YEAR(fecha)=".$y." order by fecha desc")) 
				{
					if ($stmt->num_rows > 0) 
					{
					echo "<ul class='tracks'>";
					while ($row = $stmt->fetch_object())
						{
						echo "<li><a href='javascript:void(0);' onclick='getPlayer(" . $row->id .")'>#" . $row->titulo."</a>&nbsp;<a href='./sesiones/".$row->fileplaymp3."' style='font-size: 0.8em;position: relative;	top: 0.5em;' title='descarga la sesion en mp3. Boton derecho, guardar enlace como...'>mp3</a></li>";
						}
					 } else {
					// Not rows 
					echo 'No rows';
					}
				} 
				else
				{	
					//sql error 
					echo 'sql error';
				}	
		
		
		
		
	}

?>