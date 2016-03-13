<?php
/**
 * These are the database login details
 */  
define("HOST", "x.x.x.x");		// The host you want to connect to.
define("USER", "sqluser");			// The database username. 
define("PASSWORD", "sqlpass");		// The database password. 
define("DATABASE", "sqldatabase");		// The database name.

/*
* Authentication options
*/
define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");

define("ALLOW_NEWREGISTER", false);		//register.php enabled ? true or false

define("SECURE", FALSE);				// FOR DEVELOPMENT ONLY!!!!




/*
*  Table Prefixes
*/

define("PFXTBL" , "tableprefix");


/*
** Stuff
*/

define("RUTASESIONES","//web//htdocs//domain//home//site//sesiones//");
define("SHOWLOGINLINK",1);

/*
** personal config
*/

define("DEFAULTSESSIONID" ,"21");

define("SESSIONTEXT","session");
define("SESSIONSEP","&gt;&nbsp;");

define("MAINTITTLE","dj remixes");
define("DJNAME" , "dj");
define("CONTACTURL" , "https://www.twitter.com/dj"); // $MAINTITTLE by LINKTO $CONTACTURL desc $DJNAME
define("CONTACTURL2" ,"");
define("CONTACTURL2DESC" , "");
define("CONTACTURL3","");
define("CONTACTURL3DESC" , " ");
define("CONTACTEMAIL" , "dj@mail.com");

?>