<?php
$title = "Sticky Form/Data Validation Example";

include "./includes/header.php";

//empty out error and result regardless of method that got you here
$error = "";
$result = "";

if($_SERVER["REQUEST_METHOD"] == "GET"){
	//default mode when the page loads the first time
	//can be used to make decisions and initialize variables
	$num = "";
}else if($_SERVER["REQUEST_METHOD"] == "POST"){
	//the page got here from submitting the form, let's try to process
	$num = trim($_POST["inputted_number"]); //the name of the input box on the form, white-space removed
	
	//let's do some data validation
	if(!isset($num) || $num == ""){
		//means the user did not enter anything
		$error .= "You must enter something into the text box.";
	}else if(!is_numeric($num)){
		//means the user entered something, but not a number
		//give them a detailed message
		$error .= "The value entered <u>MUST</u> be a number, you entered: " . $num;
		//empty out the invalid data
		$num = "";
	}
	
	if($error == ""){  //if error is an empty string
		//no errors, do the math
		$result = $num . " squared is " . ($num * $num);
	}else{
		//there were problems, concatentate the TRY AGAIN message
		$error .= "<br/>Please Try Again";
		
	}
	
}
//NOTE: 
//the first two echos below show the errors or the result (these are empty the first time the page loads)
//the third of the following echo'es makes this page self-referring
//the name of the current file is outputted placed in the action of the form
//and the fourth of the following echo'es is what makes the form sticky, the
//number previously entered on the form, is automatically displayed in the value of the text input box
?>
<title>Sticky Form/Data Validation Example</title>
<h2><?php echo $result; ?></h2>
<h3><?php echo $error; ?></h3>

<form action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="post" >
	Enter a number: <input type="text" name="inputted_number" value="<?php echo $num; ?>" size="5" />
	<br/><input type="submit" value="Square the number" />
</form>
<?php
	include "./includes/footer.php";
?>