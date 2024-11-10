<?php 
    $title = "Home Page";
    $passwordpgAdmin = "Xurepgadmin711!";
    $passwordserver = "100952871";
    $name = "Brendan Xure";
    $file = "index.php";
    $description = "Home page for my inft2100 web site";
    $date = "October 30, 2024";
    $banner = "Week 8: Introduction to PHP!";

    if(isset($_GET['year'])){
        $y = $_GET['year'];

    }
    else
    {
        $y =0;
    }
    if(isset($_GET['x'])){
        $a = $_GET['x'];

    }
    else
    {
        $a =0;
    }
    
    include("./includes/header.php");
    // require("./includes/header.php");
?>

    <h2><?php echo "Welcome to PHP!"; ?></h2>
    <h3><?php echo $y; ?></h3>
    <?php 
        $num1 = 20;
        $num2 = 4;
        $result = $num1 + $num2;
    ?>
    <h4><?php  echo "The sum of $num1 and $num2 is $result"; ?></h4>
    <?php 
        for($i=0; $i<5; $i++){
            echo "<p>Num = $i</p>";
        }
    ?>
    <table border=1>
        <tr>
            <th>Movie Number</th>
            <th>Title</th>
            <th>Year</th>
        </tr>

        <?php
            $output = "";
            // 1- connect to the database

            $conn = pg_connect("host=127.0.0.1 dbname=obilob_db user=obilob password=$passwordpgAdmin");

            // 2- Write the sql statement
            $sql = "SELECT movie_num, title, year FROM movies WHERE year <$1 AND year >$2";
            $stmt = pg_prepare($conn,"movie_retrieve",$sql);

            // 3- Execute the sql statement
            // $result = pg_query($conn, $sql);
            $result = pg_execute($conn,"movie_retrieve",array($y,$a));

            $records = pg_num_rows($result);

            // 4- retrieve the fields from the results and build the table
            for($i=0; $i<$records; $i++){
                $n = pg_fetch_result($result, $i, "movie_num");
                $t = pg_fetch_result($result, $i, "title");
                $y = pg_fetch_result($result, $i, "year");
                $output.="<tr><td>$n</td><td>$t</td><td>$y</td></tr>";
            }
            echo $output
        ?>
    </table>
</body>
</html>