<?php
    $name = "";
    $error = "";

    if(isset($_GET["fullName"]) && $_GET["fullName"] != ""){
        $name = $_GET["fullName"];
    } else{
        $error = "Your must provide your name";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greeting</title>
</head>
<body>
    <a href="./index.php"></a>
    <?php
        if($error == ""){
            echo "<h1>Hello $name</h1>";
        } else {
            echo "<h1>$error</h1>";
        }
    ?>
</body>
</html>