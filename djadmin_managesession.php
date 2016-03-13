<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>DJ Admin Manage Sesion</title>
        <link rel="stylesheet" href="assets/css/main.css" />
    </head>
    <body>
        <?php if (login_check($mysqli) == true) :
				$showgrid=1;

				/*
				**		delete
				*/

				if (isset($_GET['delid']) && is_numeric($_GET['delid']))
				{
					// get the 'id' variable from the URL
					$id = $_GET['delid'];

					// delete record from database
					if ($stmt = $mysqli->prepare("UPDATE ". PFXTBL . "_sesion SET activa=0 WHERE id = ? LIMIT 1"))
					{
						$stmt->bind_param("i",$id);
						$stmt->execute();
						$stmt->close();
						echo "Sesion deleted ok";
					}
				
				}

				
				/*
				**		add session (form)
				*/
				if (isset($_GET['addsession']) && ($_GET['addsession']=='1') || isset($_POST['addsession']) && ($_POST['addsession']=='1'))
				{
					// it's the first step for INSERT, just show form
					/*form default values*/
					$title="Session title";
					$recdate="2016-06-25";
					$lenghtmin="60";
					$bpm="XXX";
					$coments="write here a short description (1000 char max)";
					?>					
					<p>New session form<p/>
					<form action="djadmin_managesession.php" method="post" enctype="multipart/form-data">
					<div>
					<input type="hidden" name="addsession" value="2" />
					<strong>Title: *</strong> <input type="text" name="frm_title" value="<?php echo $title; ?>"/><br/>
					<strong>Rec Date: *</strong> <input type="text" name="frm_recdate" value="<?php echo $recdate; ?>"/><br/>
					<input type="hidden" name="MAX_FILE_SIZE" value="314572800" /> <!-- 300 Mb ? -->
					<strong>Mp3 File: </strong> <input name="frm_mp3file" type="file" /><br/><br/>
					<input type="hidden" name="MAX_FILE_SIZE" value="314572800" /> <!-- 300 Mb ? -->
					<strong>Ogg File: </strong> <input name="frm_oggfile" type="file" /><br/><br/>
					<strong>Lenght (min): *</strong> <input type="text" name="frm_lenght" value="<?php echo $lenghtmin; ?>" maxlength="3" size="3" /><br/>
					<strong>bpm: </strong> <input type="text" name="frm_bpm" value="<?php echo $bpm; ?>" maxlength="3" size="3" /><br/>
					<strong>coments: </strong><textarea rows="4" cols="50" name="frm_coments"> <?php echo $coments; ?> </textarea><br/>
					<p>* required</p>
					<input type="submit" name="submit" value="Submit" />
					</div>
					</form>
					<?
					$showgrid=0;
				}
				
				/*
				**		add session (INSERT)
				*/
				if (isset($_POST['addsession']) && ($_POST['addsession']=='2'))
				{
					// it's the second step for INSERT, just INSERT
					$nmp3file_name=$_FILES['frm_mp3file']['name'] ;
					$nmp3file_type=$_FILES['frm_mp3file']['type']	;
					$nmp3file_size=$_FILES['frm_mp3file']['size'] ;
					$nmp3file_tmpname=$_FILES['frm_mp3file']['tmp_name'] ;

					$noggfile_name=$_FILES['frm_oggfile']['name'] ;
					$noggfile_type=$_FILES['frm_oggfile']['type']	;
					$noggfile_size=$_FILES['frm_oggfile']['size'] ;
					$noggfile_tmpname=$_FILES['frm_oggfile']['tmp_name'] ;
					

					$ntitle = htmlentities($_POST['frm_title'], ENT_QUOTES);
					$nrecdate = htmlentities($_POST['frm_recdate'], ENT_QUOTES);

					$nmp3file = htmlentities($_POST['frm_mp3file'], ENT_QUOTES);
					$noggfile = htmlentities($_POST['frm_oggfile'], ENT_QUOTES);

					$nlenght = htmlentities($_POST['frm_lenght'], ENT_QUOTES);
					$nbpm = htmlentities($_POST['frm_bpm'], ENT_QUOTES);
					$ncomments = htmlentities($_POST['frm_coments'], ENT_QUOTES);
					
					$f_archivosubido=0;
					
					if ($nmp3file_name!=''){
						echo "<p>" . $nmp3file_name . " (" .$nmp3file_type . ") size: " . $nmp3file_size . "</p>";

						if (!(strpos($nmp3file_type, "mp3")  && ($nmp3file_size < 300000000))) {
							echo "mp3 and < 300mb file type/sizes.";
						}else{
							if (move_uploaded_file($nmp3file_tmpname, RUTASESIONES.$nmp3file_name)){
							   echo "<p>MP3 File Loaded ok</p>";$f_archivosubido=1;
							}else{
							   echo "<p>MP3 Error uploading file</p>";
							}
						} 
					}

					if ($noggfile_name!=''){
						echo "<p>" . $noggfile_name . " (" .$noggfile_type . ") size: " . $noggfile_size . "</p>";

						if (!(strpos($noggfile_type, "ogg")  && ($noggfile_size < 300000000))) {
							echo "ogg and < 300mb file type/sizes.";
						}else{
							if (move_uploaded_file($noggfile_tmpname, RUTASESIONES.$noggfile_name)){
							   echo "<p>OGG File Loaded ok</p>";$f_archivosubido=1;
							}else{
							   echo "<p>OGG Error uploading file</p>";
							}
						} 
					}

				if ($f_archivosubido==1)
				{
				// starts INSERT INTO
				if ($stmt = $mysqli->prepare("INSERT " . PFXTBL . "_sesion (titulo, fecha, fileplaymp3, fileplayogg, duracion, bpm, comentarios, fechapublicacion, activa) VALUES (?, ?, ?, ?, ?, ?, ?, ? ,?)"));
					{
					$currdate=date("Y-m-d");
					$nactiva=1;
					$stmt->bind_param("ssssisssi", $ntitle, $nrecdate, $nmp3file_name , $noggfile_name , $nlenght , $nbpm , $ncomments, $currdate, $nactiva);
					$stmt->execute();
					$stmt->close();
					}

				echo '<p>Alta ok</p>';
				}
				else
				{
					echo "<p>Necesitas subir al menos un archivo (mp3 o ogg) para dar de alta</p>";
				}
				
			}

				


				


				/*
				**		edit session (form)
				*/
				if (isset($_GET['editsession']) && (is_numeric($_GET['editsession'])))
				{
					// it's the first step for INSERT, just show form
					$id = $_GET['editsession'];

					if($stmt = $mysqli->prepare("SELECT * FROM " . PFXTBL . "_sesion WHERE id=?"))
					{
						$stmt->bind_param("i", $id);
						$stmt->execute();
						$stmt->bind_result($id, $ntitle, $recdate, $mp3file, $oggfile, $lenghtmin, $bpm, $coments, $reldate, $activa);
						$stmt->fetch();
						$stmt->close();
					}
					// show an error if the query has an error
					else
					{
						echo "<p>Error: could not prepare SQL statement</p>";
					}

	
					?>
					<p>Modify session form<p/>
					<form action="djadmin_managesession.php" method="post" enctype="multipart/form-data">
					<div>
					<input type="hidden" name="editsession" value="2" />
					<input type="hidden" name="editsession_id" value="<?php echo $id; ?>" />
					<strong>Title:</strong> <input type="text" name="frm_title" value="<?php echo $ntitle; ?>"/><br/>
					<strong>Rec Date:</strong> <input type="text" name="frm_recdate" value="<?php echo $recdate; ?>"/><br/>
					<strong>Rel Date:</strong> <input type="text" name="frm_reldate" value="<?php echo $reldate; ?>"/><br/>
					<!--
					<input type="hidden" name="MAX_FILE_SIZE" value="314572800" /> 
					<strong>Mp3 File: </strong> <input name="frm_mp3file" type="file" disabled /><br/><br/>
					<input type="hidden" name="MAX_FILE_SIZE" value="314572800" /> 
					<strong>Ogg File: </strong> <input name="frm_oggfile" type="file" disabled /><br/><br/>
					 -->
					<strong>Lenght (min):</strong> <input type="text" name="frm_lenght" value="<?php echo $lenghtmin; ?>" maxlength="3" size="3" /><br/>
					<strong>bpm: </strong> <input type="text" name="frm_bpm" value="<?php echo $bpm; ?>" maxlength="3" size="3" /><br/>
					<strong>coments: </strong><textarea rows="4" cols="50" name="frm_coments"> <?php echo $coments; ?> </textarea><br/>
					<input type="submit" name="submit" value="Modify" />
					</div>
					</form>
					<?
					$showgrid=0;
			}
				
				/*
				**		edit session (INSERT)
				*/
				if (isset($_POST['editsession']) && ($_POST['editsession']=='2') && isset($_POST['editsession_id']) && is_numeric($_POST['editsession_id']))
				{
					// it's the second step for INSERT, just INSERT
					$id = $_POST['editsession_id'];
					
					$ntitle = htmlentities($_POST['frm_title'], ENT_QUOTES);
					$nrecdate = htmlentities($_POST['frm_recdate'], ENT_QUOTES);
					$nreldate = htmlentities($_POST['frm_reldate'], ENT_QUOTES);
//					$nmp3file = htmlentities($_POST['frm_recdate'], ENT_QUOTES);
//					$noggfile = htmlentities($_POST['frm_recdate'], ENT_QUOTES);
					$nlenght = htmlentities($_POST['frm_lenght'], ENT_QUOTES);
					$nbpm = htmlentities($_POST['frm_bpm'], ENT_QUOTES);
					$ncomments = htmlentities($_POST['frm_coments'], ENT_QUOTES);
					
					// starts INSERT INTO
					if ($stmt = $mysqli->prepare("UPDATE " . PFXTBL . "_sesion SET titulo=?, fecha=?, duracion=?, bpm=?, comentarios=?, fechapublicacion=? WHERE id= ?"));
					{
						$stmt->bind_param("ssisssi", $ntitle, $nrecdate, $nlenght , $nbpm , $ncomments, $nreldate, $id);
						$stmt->execute();
						$stmt->close();
					}

					echo '<p>Modificado ok ok</p>';
				}



				/*
				**		view grid session
				*/
			if ($showgrid)
			{
				if ($stmt = $mysqli->query("SELECT * FROM ". PFXTBL . "_sesion WHERE activa=1 ORDER BY fechapublicacion, id DESC")) 
				{
					if ($stmt->num_rows > 0) 
					{
					// If the user exists get variables from result.

					// display records in a table
					echo "<table border='10' cellpadding='10'>";
					// set table headers
					echo "<tr><th></th><th></th><th></th><th>Title</th><th>Rel Date</th></tr>";

					while ($row = $stmt->fetch_object())
						{
						// set up a row for each record
						echo "<tr>";
						echo "<td><a href='djadmin_managesessiontracks.php?edittrackssession=" . $row->id . "'>&nbsp;Tracks&nbsp;</a></td>";
						echo "<td><a href='djadmin_managesession.php?editsession=" . $row->id . "'>&nbsp;Edit&nbsp;</a></td>";
						echo "<td><a href='djadmin_managesession.php?delid=" . $row->id . "'>&nbsp;Delete&nbsp;</a></td>";
						echo "<td>&nbsp;" . $row->titulo . "&nbsp;</td>";
						echo "<td>&nbsp;" . $row->fechapublicacion . "&nbsp;</td>";
						echo "</tr>";
						}
					echo "</table>";
				  
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
			<p>Upload new <a href='djadmin_managesession.php?addsession=1'>session</a> - Return to <a href="djadmin_manage.php">Manage main menu</a> - Return to <a href="djadmin.php">login page</a></p>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="djadmin.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>