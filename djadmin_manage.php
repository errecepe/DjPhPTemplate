<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>DJ Admin Main Menu</title>
        <link rel="stylesheet" href="assets/css/main.css" />
    </head>
    <body>
        <?php if (login_check($mysqli) == true) : ?>
            <p>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</p>
			<ul>
				<li><a href='djadmin_managesession.php'>Manage sessions</a></li>
				<li><a href='djadmin_managesession.php?addsession=1'>Upload new session</a></li>
				<li><a href='djadmin_managetracks.php'>Manage Tracks</a></li>
			</ul>
			<p>Return to <a href="djadmin.php">login page</a></p>
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="djadmin.php">login</a>.
            </p>
        <?php endif; ?>
    </body>
</html>