<?php
/** 
* This file contains the body section of the grades page 
* 
* 
* PHP version 7.1 
* 
* @author  Brendan Obilo <brendan.obilo@dcmail.ca>       
* @version 7.1 (November 02, 2024)
*/ 
    $title = "Grade Page";
    $file = "grade.php"; 
    $description = "Grade page and student details for my Students Grade Portal"; 
    $date = "November 02, 2024"; 
    $banner = "DC Academic Performance";
    $is_error = FALSE;

    include("./includes/header.php");
    
    if(!isset($_SESSION['user']))
    {
        // A message stating the user has successfully logged out onto the session:
        $_SESSION["message"] = "You have to log in first";
        // Redirect the user to the login.php page:
        header("Location:login.php");
	    ob_flush();
    }

    $user = $_SESSION['user'];
    $y = $user['user_id'];

?>

<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <symbol id="calendar-event" viewBox="0 0 16 16">
    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
  </symbol>

  <symbol id="alarm" viewBox="0 0 16 16">
    <path d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z"/>
    <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z"/>
  </symbol>

  <symbol id="list-check" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
  </symbol>
</svg>

<?php
    // Check if there is a session message
    $msg = isset($_SESSION["message"])?$_SESSION["message"]:"";
    $output = "";
    // User passed no student id in the URL
    if(trim($y) === "")
    {
        $is_error = TRUE;
        $output = "No student Id is passed in the URL"; 
    } 
    // User passed numeric value for student ID
    else if ( ! ctype_digit($y)) 
    {
        $is_error = TRUE;
        $output = "The Student Id does not exist";
    } 
    // Student ID is passeed in the URL
    else
    {
        // 1- connect to the database

        $conn = db_connect();

        // prepare sql statement for execution
        prepare_statement($conn, "student_retrieve");
        prepare_statement($conn, "marks_retrieve");
        
        // 3- Execute the sql statement
        $result = pg_execute($conn,"student_retrieve",array($y));
        $result_marks = pg_execute($conn,"marks_retrieve",array($y));

        // Retrieve number of records
        $records = pg_num_rows($result);
        $records_marks = pg_num_rows($result_marks);

        // check if there is a record for the data in the database
        if ($records) 
        {
            $is_error = FALSE;
            // 4- retrieve the fields from the results and build the table
            for($i=0; $i<$records; $i++)
            {
                $full_name = pg_fetch_result($result,$i,"full_name");
                $program_code = pg_fetch_result($result,$i,"program_code");
                $email = pg_fetch_result($result,$i,"email");
            }
        } 
        // When the wrong student id is passed in the url
        else 
        {
            $is_error = TRUE;
            $output = "The Student Id does not exist";
        }
        
    }
   
?>
<main class='min-vh-70'>
<?php 
    if($msg != '')
    {
        echo"<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                $msg
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    }
    
    // If the student ID is not passed or wrong student ID is passed
    if ($is_error) 
    {
        echo"<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                $output
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    } 
    // If the correct and existing student id is passed in the url
    else 
    {
        echo "<div class='d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center'>
        <div class='list-group'>
            <a href='#' class='list-group-item list-group-item-action d-flex gap-3 py-3' aria-current='true'>
                <img src='https://github.com/twbs.png' alt='twbs' width='32' height='32' class='rounded-circle flex-shrink-0'>
                <div class='d-flex gap-2 w-100 justify-content-between'>
                    <div>
                        <h6 class='mb-0'>Student Fullname</h6>
                        <p class='mb-0 opacity-75'>$full_name</p>
                    </div>
                    <small class='opacity-50 text-nowrap'></small>
                </div>
            </a>
            <a href='#' class='list-group-item list-group-item-action d-flex gap-3 py-3' aria-current='true'>
                <img src='https://github.com/twbs.png' alt='twbs' width='32' height='32' class='rounded-circle flex-shrink-0'>
                <div class='d-flex gap-2 w-100 justify-content-between'>
                    <div>
                        <h6 class='mb-0'>Program</h6>
                        <p class='mb-0 opacity-75'>$program_code</p>
                    </div>
                    <small class='opacity-50 text-nowrap'></small>
                </div>
            </a>
            <a href='#' class='list-group-item list-group-item-action d-flex gap-3 py-3' aria-current='true'>
                <img src='https://github.com/twbs.png' alt='twbs' width='32' height='32' class='rounded-circle flex-shrink-0'>
                <div class='d-flex gap-2 w-100 justify-content-between'>
                    <div>
                        <h6 class='mb-0'>Email</h6>
                        <p class='mb-0 opacity-75'>$email</p>
                    </div>
                    <small class='opacity-50 text-nowrap'></small>
                </div>
            </a>
        </div>
      </div>";

    }
?>

<?php
    // The student Id passed in the url has marks for courses taken per semester
    if( ! $is_error && $records_marks)
    {
        echo"
            <div class='container'>
                <table class='table table-bordered table-striped table-sm'>
                    <thead>
                        <tr>
                            <th scope='col'>Course Code</th>
                            <th scope='col'>Course Name</th>
                            <th scope='col'>Highest Grade</th>
                            <th scope='col'>Date Achieved</th>
                        </tr>
                    </thead>
                    <tbody>";
                        for($i=0; $i<$records_marks; $i++)
                        {
                            $course_code = pg_fetch_result($result_marks,$i,"course_code");
                            $course_name = pg_fetch_result($result_marks,$i,"course_name");
                            $highest_grade = pg_fetch_result($result_marks,$i,"highest_grade");
                            $date_achieved = pg_fetch_result($result_marks,$i,"date_achieved");
                            $formatted_date = (new DateTime($date_achieved))->format('Y-m-d');
                            echo"
                                <tr>
                                    <td>$course_code</td>
                                    <td>$course_name</td>
                                    <td>$highest_grade</td>
                                    <td>$formatted_date</td>
                                </tr>";    
                        }
                            echo"
                    </tbody>
                </table>
            </div>";
    } 
    // The student id passed in the url has no score yet because the student is in the first semester
    if( ! $is_error && ! $records_marks)
    {
        echo"
            <div class='alert alert-info' role='alert'>
                Student is in the first semester and has no final marks.
            </div>";
    }
?>
</main>

<?php
    include("./includes/footer.php");
?>