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
				$showgrid=1;

				/*
				**		delete
				*/

				if (isset($_GET['delid']) && is_numeric($_GET['delid']))
				{
					// get the 'id' variable from the URL
					$id = $_GET['delid'];

					// delete record from database
					if ($stmt = $mysqli->prepare("UPDATE ". PFXTBL . "_track SET activa=0 WHERE id = ? LIMIT 1"))
					{
						$stmt->bind_param("i",$id);
						$stmt->execute();
						$stmt->close();
						echo "Track deleted ok";
					}
				
				}

				
				/*
				**		add track (form)
				*/
				if (isset($_GET['addtrack']) && ($_GET['addtrack']=='1') || isset($_POST['addtrack']) && ($_POST['addtrack']=='1'))
				{
					// it's the first step for INSERT, just show form
					/*form default values*/
					$title="Track title";
					$author="Author name";
					$ytid="3l436MSlivQ";
					?>					
					<p>New track form<p/>
					<form action="djadmin_managetracks.php" method="post" enctype="multipart/form-data">
					<div>
					<input type="hidden" name="addtrack" value="2" />
					<strong>Title:</strong> <input type="text" name="frm_title" value="<?php echo $title; ?>"/><br/>
					<strong>Author:</strong> <input type="text" name="frm_author" value="<?php echo $author; ?>"/><br/>
					<strong>Youtube ID:</strong> <input type="text" name="frm_ytid" value="<?php echo $ytid; ?>" maxlength="3" size="3" /><br/>
					<input type="submit" name="submit" value="Submit" />
					</div>
					</form>
					<?
					$showgrid=0;
				}
				
				/*
				**		add session (INSERT)
				*/
				if (isset($_POST['addtrack']) && ($_POST['addtrack']=='2'))
				{
					// it's the second step for INSERT, just INSERT

					$ntitle = htmlentities($_POST['frm_title'], ENT_QUOTES);
					$nauthor = htmlentities($_POST['frm_author'], ENT_QUOTES);
					$nytid = htmlentities($_POST['frm_ytid'], ENT_QUOTES);
			
				// starts INSERT INTO
				if ($stmt = $mysqli->prepare("INSERT " . PFXTBL . "_track (titulo, autor, yt_id, activa) VALUES (?, ?, ?, ?)"));
					{
					$currdate=date("Y-m-d");
					$nactiva=1;
					$stmt->bind_param("sssi", $ntitle, $nauthor, $nytid, $nactiva);
					$stmt->execute();
					$stmt->close();
					}
				echo '<p>Alta ok</p>';
					
				}

		
				


				/*
				**		edit session (form)
				*/
				if (isset($_GET['edittrack']) && (is_numeric($_GET['edittrack'])))
				{
					// it's the first step for INSERT, just show form
					$id = $_GET['edittrack'];

					if($stmt = $mysqli->prepare("SELECT * FROM " . PFXTBL . "_track WHERE id=?"))
					{
						$stmt->bind_param("i", $id);
						$stmt->execute();
						$stmt->bind_result($id, $title, $author, $ytid, $activa);
						$stmt->fetch();
						$stmt->close();
					}
					// show an error if the query has an error
					else
					{
						echo "<p>Error: could not prepare SQL statement</p>";
					}

	
					?>
					<p>Modify track form<p/>
					<form action="djadmin_managetracks.php" method="post" enctype="multipart/form-data">
					<div>
					<input type="hidden" name="edittrack" value="2" />
					<input type="hidden" name="edittrack_id" value="<?php echo $id; ?>" />
					<strong>Title:</strong> <input type="text" name="frm_title" value="<?php echo $title; ?>"/><br/>
					<strong>Author:</strong> <input type="text" name="frm_author" value="<?php echo $author; ?>"/><br/>
					<strong>Youtube ID:</strong> <input type="text" name="frm_ytid" value="<?php echo $ytid; ?>" maxlength="3" size="3" /><br/>
					<input type="submit" name="submit" value="Modify" />
					</div>
					</form>
					<?
					$showgrid=0;
			}
				
				/*
				**		edit session (INSERT)
				*/
				if (isset($_POST['edittrack']) && ($_POST['edittrack']=='2') && isset($_POST['edittrack_id']) && is_numeric($_POST['edittrack_id']))
				{
					// it's the second step for INSERT, just INSERT
					$id = $_POST['edittrack_id'];
					
					$mtitle = htmlentities($_POST['frm_title'], ENT_QUOTES);
					$mauthor = htmlentities($_POST['frm_author'], ENT_QUOTES);
					$mytid = htmlentities($_POST['frm_ytid'], ENT_QUOTES);
					// starts INSERT INTO
					if ($stmt = $mysqli->prepare("UPDATE " . PFXTBL . "_track SET titulo=?, autor=?, yt_id=? WHERE id= ?"));
					{
						$stmt->bind_param("sssi", $mtitle, $mauthor, $mytid, $id);
						$stmt->execute();
						$stmt->close();
					}

					echo '<p>Modificado ok</p>';
				}



				/*
				**		view grid session
				*/
			if ($showgrid)
			{
				if ($stmt = $mysqli->query("SELECT * FROM ". PFXTBL . "_track WHERE activa=1 ORDER BY autor,titulo")) 
				{
					if ($stmt->num_rows > 0) 
					{
					// If the user exists get variables from result.

					// display records in a table
					echo "<table border='10' cellpadding='10'>";
					// set table headers
					echo "<tr><th></th><th></th><th>Title</th><th>Autor</th><th>Youtube ID</th></tr>";

					while ($row = $stmt->fetch_object())
						{
						// set up a row for each record
						echo "<tr>";
						echo "<td><a href='djadmin_managetracks.php?edittrack=" . $row->id . "'>&nbsp;Edit&nbsp;</a></td>";
						echo "<td><a href='djadmin_managetracks.php?delid=" . $row->id . "'>&nbsp;Delete&nbsp;</a></td>";
						echo "<td>&nbsp;" . $row->titulo . "&nbsp;</td>";
						echo "<td>&nbsp;" . $row->autor . "&nbsp;</td>";
						echo "<td>&nbsp;" . $row->yt_id . "&nbsp;</td>";
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
			<p>Add new <a href='djadmin_managetracks.php?addtrack=1'>track</a> - Return to <a href="djadmin_manage.php">Manage main menu</a> - Return to <a href="djadmin.php">login page</a></p>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="djadmin.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>