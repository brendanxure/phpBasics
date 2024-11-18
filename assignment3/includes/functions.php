<?php 
/** 
* This file contains the funtion section that connects the database and retrieves information of students 
* 
* 
* PHP version 7.1 
* 
* @author  Brendan Obilo <brendan.obilo@dcmail.ca>       
* @version 7.1 (November 02, 2024)
*/ 
?>

<?php
/** 
* This function connects the web page to the database by calling pg_connect() and takes no parameter 
* 
*  
* @author Brendan Obilo <brendan.obilo@dcmail.ca> 
* 
* @return $conn returns the connection resource
*/ 
function db_connect() 
{
    $password_server = "100952871";
    // Connect to the database
    $conn = pg_connect("host=127.0.0.1 dbname=obilob_db user=obilob password=$password_server");

    // Return the connection resource
    return $conn;
}

/** 
* This function prepare the sql statement/query for proper execution to prevent code injection from hackers
* 
* @param Left Hand Operand   $conn the connection resource for the database 
*  
* @author Brendan Obilo <brendan.obilo@dcmail.ca>
* 
* @return returns nothing 
*/ 
function prepare_statement($conn)
{
     // Write the sql statement for student information retrieval
     $sql = "SELECT u.first_name || ' ' || u.last_name AS full_name, program_code, u.email 
             FROM students s
             INNER JOIN users u ON s.student_id = u.user_id
             WHERE student_id = $1;";
     $stmt = pg_prepare($conn,"student_retrieve",$sql);

    // Write the sql statement for student grade retrieval 
    $sql_marks = "SELECT m.course_code, c.course_name, m.final_mark AS highest_grade, m.achieved_at AS date_achieved
                  FROM marks m
                  INNER JOIN courses c ON m.course_code = c.course_code
                  INNER JOIN (SELECT MAX(final_mark) as final_highest_mark, course_code
                  FROM marks m
                  WHERE student_id = $1
                  GROUP BY course_code) AS a ON m.course_code = a.course_code AND m.final_mark = a.final_highest_mark
                  WHERE student_id = $1";
    $stmt_marks = pg_prepare($conn, "marks_retrieve", $sql_marks);
}
;?>
