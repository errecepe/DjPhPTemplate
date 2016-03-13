<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>DJ Admin Manage Tracks</title>
        <link rel="stylesheet" href="assets/css/main.css" />
    </head>
    <body>
        <?php if (login_check($mysqli) == true) :
				
					if (isset($_GET['edittrackssession']) )
						{$sesion_id=$_GET['edittrackssession'];}else{
						
						if (isset($_POST['edittrackssession']) ){$sesion_id=$_POST['edittrackssession'];}else{echo "NEED A SESION ID";}

						}
							
				
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
				**		add existent track form
				*/
				if ( isset($_GET['addexistenttrack']) && ($_GET['addexistenttrack']=='1'))
				{
					// it's the first step for INSERT, just show form
					/*form default values*/
				
					?>					
					<p>Select track<p/>
					<form action="djadmin_managesessiontracks.php" method="post" enctype="multipart/form-data">
					<div>
					<input type="hidden" name="addexistenttrack" value="2" />
					<input type="hidden" name="edittrackssession" value="<?echo $sesion_id;?>" />
					<select name="track_id">
						<?
						if ($stmt = $mysqli->query("select * from ".PFXTBL."_track order by id")) 
							{
								if ($stmt->num_rows > 0) 
								{
								while ($row = $stmt->fetch_object())
									{
									echo "<option value='".$row->id ."'>".$row->autor."-".$row->titulo."</option>";
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
						?>
					</select><br/>
					<input type="submit" name="submit" value="Add" />
					</div>
					</form>
					<?
					$showgrid=0;
				}
				
				/*
				**		add session (INSERT)
				*/
				if (isset($_POST['addexistenttrack']) && ($_POST['addexistenttrack']=='2'))
				{
					// it's the second step for INSERT, just INSERT
					
					$track_id = htmlentities($_POST['track_id'], ENT_QUOTES);
				
				// starts INSERT INTO
					if ($stmt = $mysqli->prepare("INSERT " . PFXTBL . "_sesiontrack (sesion_id, track_id, orden) VALUES (?, ?, ?)"));
						{
						$stmt->bind_param("iii", $sesion_id, $track_id,$track_id);
						$stmt->execute();
						$stmt->close();
						}
				echo '<p>Alta ok</p>';
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
									
					if ($stmt = $mysqli->query("SELECT t.* , st.* FROM ".PFXTBL. "_track t INNER JOIN ".PFXTBL. "_sesiontrack AS st ON ( t.id = st.track_id ) WHERE st.sesion_id =".$sesion_id." order by orden")) 
					{
						if ($stmt->num_rows > 0) 
						{
						// If the user exists get variables from result.

						// display records in a table
						echo "<table border='10' cellpadding='10'>";
						// set table headers
						echo "<tr><th></th><th>#</th><th>Title</th><th>Author</th><th>YouTube Id</th></tr>";

						while ($row = $stmt->fetch_object())
							{
							// set up a row for each record
							echo "<tr>";
							echo "<td><a href='djadmin_managesessiontracks.php?delid=" . $row->id . "'>&nbsp;Delete&nbsp;</a></td>";
							echo "<td>&nbsp;" . $row->orden. "&nbsp;</td>";
							echo "<td>&nbsp;" . $row->titulo . "&nbsp;</td>";
							echo "<td>&nbsp;" . $row->autor . "&nbsp;</td>";
							echo "<td>&nbsp;" . $row->yt_id . "&nbsp;</td>";
							echo "</tr>";
							}
						echo "</table>";
					  
						 } else {
						// Not rows 
						echo 'No Tracks for this sesion';
						}
					} 
					else
					{	
						//sql error 
						echo 'sql error';
					}		
			}

			?>
			<p>Add new existent <a href='djadmin_managesessiontracks.php?addexistenttrack=1&edittrackssession=<?echo $sesion_id;?>'>track</a> - Return to <a href="djadmin_manage.php">Manage main menu</a> - Return to <a href="djadmin.php">login page</a></p>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="djadmin.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>