<?php
/** 
* This file contains the body section of the home page 
* 
* 
* PHP version 7.1 
* 
* @author  Brendan Obilo <brendan.obilo@dcmail.ca>       
* @version 7.1 (November 02, 2024)
*/ 

    $title = "Home Page";
    $file = "index.php"; 
    $description = "Home page for my Students Grade Portal"; 
    $date = "November 02, 2024"; 
    $banner = "DC Grade Portal";
    include("./includes/header.php");
?>

<main>
  <div class="container py-4">

    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
      <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Welcome to the Student Grade Portal!</h1>
        <p class="col-md-8 fs-4">Here, you can access a streamlined view of your grades, organized and displayed in an intuitive format. With various customization options, you can tailor the portal to suit your needs and easily navigate through your academic progress.</p>
        <button class="btn btn-primary btn-lg" type="button">Welcome</button>
      </div>
    </div>

    <div class="row align-items-md-stretch">
      <div class="col-md-6">
        <div class="h-100 p-5 text-bg-dark rounded-3">
          <h2>Personalize your Student Grade Portal!</h2>
          <p>Change the background color and apply different text color options to make your portal uniquely yours. Experiment with various themes and styles to create a look that suits your preferences while keeping your grade information accessible and easy to read.</p>
          <button class="btn btn-outline-light" type="button">Personalize</button>
        </div>
      </div>
      <div class="col-md-6">
        <div class="h-100 p-5 bg-body-tertiary border rounded-3">
          <h2>Calculate Your CGPA</h2>
          <p>Easily track and calculate your Cumulative Grade Point Average (CGPA) right here in the Student Grade Portal! Simply input your grades, and the portal will handle the rest, providing you with an accurate CGPA to monitor your academic progress. Stay informed and motivated with each calculation as you work towards your goals!.</p>
          <button class="btn btn-outline-secondary" type="button">Calculate</button>
        </div>
      </div>
    </div>
  </div>
</main>

<?php
    include("./includes/footer.php");
?>
