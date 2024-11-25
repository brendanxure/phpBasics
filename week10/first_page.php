<?php
	// start the session
	 session_start();
    $_SESSION['name'] = "Tom";
    $_SESSION['user_id'] = "333";
	$_SESSION['password'] = "552";

?>
	<strong>Session currently contains:</strong><br/>
	<p>Session Id: <em><?php echo session_id(); ?> </em></p>
	<pre><?php print_r($_SESSION);?></pre>
	<br />
	<p>To see something specific, you just need to reference the array element  by name:
	<b><?php echo $_SESSION['name']; ?></b></p>
	<p>Let's see what happens on the <a href="next_page.php">next page.</a></p>
	