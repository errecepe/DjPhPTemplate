<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

		if ($_GET["s"]!=''){$s=$_GET["s"];}else{$s=DEFAULTSESSIONID;} //aqui el else, es para mostrar el $DEFAULTSESSIONID

		//muestra la session $s (o la ultima)

		if ($stmt = $mysqli->query("select * from ".PFXTBL."_sesion where id=".$s)) 
				{
					if ($stmt->num_rows > 0) 
					{
					$row = $stmt->fetch_object();

					echo "<h1>" .SESSIONTEXT ."&nbsp;". SESSIONSEP ."</span>".$row->titulo."</h1>";
					echo "<audio id='mp3player' controls='controls'><source id='mp3player-mp3' src='sesiones/". $row->fileplaymp3."' type='audio/mpeg'></audio><ul class='tracks'>";
					if ($stmt2 = $mysqli->query("SELECT t.* , st.* FROM ".PFXTBL. "_track t INNER JOIN ".PFXTBL. "_sesiontrack AS st ON ( t.id = st.track_id ) WHERE st.sesion_id =".$s." order by orden")) 
						{
							if ($stmt2->num_rows > 0) 
							{
							while ($row2 = $stmt2->fetch_object())
								{
									echo "<li>".$row2->autor. "&nbsp;-&nbsp;" . $row2->titulo."</li>";
								}
							 } else {echo 'No tracks';}
						} 
						else
						{	
							//sql error 
							echo 'sql2 error';
						}	
					echo "</ul>";
					if ($row->duracion=='0'){$duracion="XX";}else{$duracion=$row->duracion;}
					if ($row->bpm==''){$bpm="XXX";}else{$bpm=$row->bpm;}
					$fechapublicacion=$row->fechapublicacion;
					$fechagrabacion=$row->fecha;
					echo "<ul class='tracks2'><li><a href='index.php?s=$s' style='enlacepequenyo' title='Obten el enlace directo a esta sesion'>link it</a></li><li>$duracion min</li><li>$bpm bpm</li><li>Rec Date $fechagrabacion</li><li>Rel Date $fechapublicacion</li>";
					echo "<p>".utf8_encode($row->comentarios)."</p>";

					 } else {echo 'No rows';}
				} 
				else
				{	
					//sql error 
					echo 'sql error';
				}	

?>