<?php
session_start();
    $name = "";
    $greeting_message = "";
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $name = trim($_POST["fName"]);

        if(!isset($name) || $name == ""){
            $error_message = "You must enter your name!";
        } 
        elseif (strlen($name)<5){
            $error_message = "You name must be at least 5 characters";
        }

        if($error_message == ""){
            $greeting_message = "Hello $name";
            $_SESSION['greeting'] = $greeting_message;
            header("Location:welcome.php");
            ob_flush();
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Week 10: Example 2</h1>
    <!-- <h2><?php echo $greeting_message ?></h2> -->
    <form action="./index.php" method="post">
        <div>
            <label for="fName">First Name:</label>
            <input type="text" name="fName" id="fName">
            <span><?php echo $error_message; ?></span>
        <div>
            <input type="submit" value="Submit">
        </div>
        </div>
    </form>
</body>
</html>