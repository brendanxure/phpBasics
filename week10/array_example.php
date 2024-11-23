<?php
    
    // Indexed Array
    $names = array("Mira", "Mia");

    echo $names[0];

    echo "<br>";
    print_r($names);

    echo "<br>";
    $ages1 = array(24,47,29,19);
    //echo $ages1[0];
    print_r($ages1);
    //echo "Test";
    
    // Associative Array

    $ages2 = array(
        'Mira'=>11,
        'Muath'=>47,
        'Mia'=>6,
        'Sue'=>19
    );
    echo "<br>";

    echo $ages2['Muath'];
    
    // Multi-dimensional array

    $ages3 = array(
            array(19,22), 
            array(17,35), 
            array(40,21));
    echo "<br>";
    echo $ages3[0][1];

    // Informational Functions
    echo "<br>";
    echo "Count = ".count($ages3);
    echo "<br>";
    print_r(array_keys($ages2));

    // Transformative Functions

    array_push($names, "Brendan", "Xure");
    echo "<br>";
    print_r($names);

    array_pop($names);
    echo "<br>";
    print_r($names);

    // Traversing Functions
    echo "<br>";
    echo current($names);

    echo "<br>";
    echo next($names);

    echo "<br>";
    echo next($names);

    echo "<br>";
    echo reset($names);

    echo "<br>";
    foreach ($ages1 as $e) {
        echo $e."<br>";
    }

    foreach ($ages2 as $key => $value) {
        echo "Name = $key and age = $value <br>";
    }

    
?>