<?php
    session_start();
    $greeting_message = "";
    if(isset($_SESSION['greeting'])){
        $greeting_message = $_SESSION['greeting'];
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
    <h1><?php echo $greeting_message ?></h1>
</body>
</html>