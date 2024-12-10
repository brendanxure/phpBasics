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
    $passwordpgAdmin = "100952871";
    // Connect to the database
    $conn = pg_connect("host=127.0.0.1 dbname=obilob_db user=obilob password=$passwordpgAdmin");

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
function prepare_statement($conn, $defined_statement)
{
    $student_retrieve = "student_retrieve";
    $marks_retrieve = "marks_retrieve";
    $login = "login";
    $last_access = "last_access";
    $register_user = "register_user"; 
    $register_student = "register_student";

    if($defined_statement == $student_retrieve){
        // Write the sql statement for student information retrieval
        $sql = "SELECT u.first_name || ' ' || u.last_name AS full_name, program_code, u.email 
                FROM students s
                INNER JOIN users u ON s.student_id = u.user_id
                WHERE student_id = $1;";
        $stmt = pg_prepare($conn,"student_retrieve",$sql);
    }
    else if($defined_statement == $marks_retrieve)
    {
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
    else if($defined_statement == $login)
    {
        // To get the student and user record using the user/student id
        $sql_login = "SELECT *
                      FROM users u
                      INNER JOIN students s ON u.user_id = s.student_id
                      WHERE user_id = $1 AND student_id = $1";
        $stmt_login = pg_prepare($conn, "login", $sql_login);
    }    
    else if($defined_statement == $last_access)
    {
        // To update the user last access
        $sql_last_access = "UPDATE users
                            SET last_access = NOW()
                            WHERE user_id = $1";
        $stmt_last_access = pg_prepare($conn, "last_access", $sql_last_access);
    }
    else if($defined_statement == $register_user)
    {
        // To register user details into the user table
        $sql_register_user = "INSERT INTO users(user_id, first_name, last_name, email, birth_date, password)
                              VALUES ($1, $2, $3, $4, $5, crypt($6, gen_salt('bf')))";
        $stmt_register_user = pg_prepare($conn, "register_user", $sql_register_user);

    }
    else if($defined_statement == $register_student)
    {
        // To register user details into student table
        $sql_register_student = "INSERT INTO students(student_id, program_code) 
                                 VALUES ($1, $2)";
        $stmt_register_student = pg_prepare($conn, "register_student", $sql_register_student);
    }
    
}
;?>
