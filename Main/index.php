<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Testing and Recruitment</title>
  <link rel="icon" type="image/x-icon" href="../Picture/Sproject.png">

  <!-- Bootstrap 5 -->
   <link rel="stylesheet" href="../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>
<body>
  <!-- Header -->
   <?php
    require_once 'MainHeader.php';
   ?>

   <!-- Body -->
  <div class="container-fluid">
    <div class="row">
      <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active" data-bs-interval="10000">
            <img src="../Picture/3.png" class="d-block w-100 object-fit-cover" alt="..." style="height: 320px;">
            <div class="carousel-caption d-none d-md-block bg-info bg-opacity-50 rounded-3">
              <h3 class="fw-bold">Admission | Recruitment | promotion</h3>
              <p>Conducting entry tests, recruitment screening, and promotion exams with transparency.</p>
            </div>
          </div>
          <div class="carousel-item" data-bs-interval="2000">
            <img src="../Picture/3.png" class="d-block w-100 object-fit-cover" alt="..." style="height: 320px;">
            <div class="carousel-caption d-none d-md-block bg-info bg-opacity-50 rounded-3">
              <h3 class="fw-bold">Online Quizing System</h3>
              <p>The system automates the process of test creation, submission, and result generation, saving time and effort.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="../Picture/3.png" class="d-block w-100 object-fit-cover" alt="..." style="height: 320px;">
            <div class="carousel-caption d-none d-md-block bg-info bg-opacity-50 rounded-3">
              <h3 class="fw-bold">Transparent Results</h3>
              <p>Fast, reliable, and fair evaluation for all candidates nationwide.</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Hero Section -->
  <section class="bg-light text-center py-5">
    <div class="container">
      <h1 class="fw-bold">Online testing website with transparency</h1>
      <p class="text-muted mb-4">Register once, apply for posts, and take your tests securely online.</p>
      <a href="../Login&Registration/SignUp.php" class="btn btn-primary btn-lg me-2">Register</a>
      <a href="MAllProject.php" class="btn btn-outline-primary btn-lg">Apply for Post</a>
    </div>
  </section>

  <!-- Announcement Section -->
<div class="container-fluid mb-4">
  <div class="alert alert-info">
    <div class="row align-items-center py-2">
      <!-- Announcement -->
      <div class="col-12 col-md-4 text-center text-md-start mb-2 mb-md-0">
        <strong>📢 Announcement</strong>
      </div>

      <!-- Marquee -->
      <div class="col-12 col-md-8">
        <marquee behavior="scroll" direction="left">
          You can check your test results and download your roll number slip directly from your account. Please log in regularly to stay updated with new announcements and important notifications.
        </marquee>
      </div>
    </div>
  </div>
</div>

  <!-- Quick Links -->
  <div class="container text-center mb-5">
    <h4 class="mb-3">Quick Links</h4>
    <div class="row g-3">
      <div class="col-6 col-md-2">
        <div class="card h-100 shadow-sm">
          <a href="../Login&Registration/SignUp.php" class="text-decoration-none text-reset">
            <div class="card-body">
            <i class="fa-solid fa-user-plus fa-2x text-primary mb-2"></i>
            <h6 class="card-title">Register</h6>
            <small class="text-muted">Create your account</small>
          </div>
          </a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card h-100 shadow-sm">
          <a href="../Login&Registration/UserLogin.php" class="text-reset text-decoration-none">
            <div class="card-body">
              <i class="fa-solid fa-right-to-bracket fa-2x text-success mb-2"></i>
              <h6 class="card-title">Login</h6>
              <small class="text-muted">Face recognition login</small>
            </div>
          </a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card h-100 shadow-sm">
          <a href="MAllProject.php" class="text-decoration-none text-reset">
            <div class="card-body">
              <i class="fa-solid fa-folder-open fa-2x text-warning mb-2"></i>
              <h6 class="card-title">All Posts</h6>
              <small class="text-muted">View available jobs</small>
            </div>
          </a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card h-100 shadow-sm">
          <a href="MAllProject.php" class="text-decoration-none text-reset">
            <div class="card-body">
              <i class="fa-solid fa-file-signature fa-2x text-danger mb-2"></i>
              <h6 class="card-title">Apply</h6>
              <small class="text-muted">Submit your application</small>
            </div>
          </a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card h-100 shadow-sm">
          <a href="Mtestlist.php" class="text-reset text-decoration-none">
          <div class="card-body">
            <i class="fa-solid fa-pen-to-square fa-2x text-info mb-2"></i>
            <h6 class="card-title">Test</h6>
            <small class="text-muted">Attempt your exam</small>
          </div>
          </a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <div class="card h-100 shadow-sm">
          <a href="Mtestlist.php" class="text-reset text-decoration-none">
          <div class="card-body">
            <i class="fa-solid fa-chart-bar fa-2x text-secondary mb-2"></i>
            <h6 class="card-title">Results</h6>
            <small class="text-muted">Check your marks</small>
          </div>
          </a>
        </div>
      </div>
    </div>
  </div>
   <div class="stats-strip text-center text-white" style="background: linear-gradient(135deg, rgba(109,40,217,0.9), rgba(6,182,212,0.9));">
  <div class="container-fluid p-3">
    <h4 class="fw-bold mb-1">Access All Your Test Information Easily</h4>
    <p class="mb-0">Check your results, download roll number slips, and stay updated—directly from your account.</p>
  </div>
</div>

  <!-- FAQ Section -->
<div class="container-fluid">
  <h2 class="text-center mt-5 mb-4 fw-bold">Frequently asked questions</h2>
  
  <div class="row">
    <div class="col-md-6">
      <div class="accordion" id="faqAccordionLeft">
        
        <div class="accordion-item mb-3 shadow-sm rounded-3">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
              What we offering?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
            <div class="accordion-body">
              We offers various admission and recruitment tests for universities and government jobs.
            </div>
          </div>
        </div>

        <div class="accordion-item mb-3 shadow-sm rounded-3">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              From Where Can I Get the Information About the Online Testing Service?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
            <div class="accordion-body">
              You can get details from the official website and advertisements.
            </div>
          </div>
        </div>

        <div class="accordion-item mb-3 shadow-sm rounded-3">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
              What are fee details for recruitment posts?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
            <div class="accordion-body">
              No Fee are required for the applying to any post.
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="col-md-6">
      <div class="accordion" id="faqAccordionRight">

        <div class="accordion-item mb-3 shadow-sm rounded-3">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
              Can any correction be made after submission of online application?
            </button>
          </h2>
          <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
            <div class="accordion-body">
              No corrections are allowed after final submission of the application.
            </div>
          </div>
        </div>

        <div class="accordion-item mb-3 shadow-sm rounded-3">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
              From Where Can I Get the Application Forms?
            </button>
          </h2>
          <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
            <div class="accordion-body">
              Application forms are available on the official website.
            </div>
          </div>
        </div>

        <div class="accordion-item mb-3 shadow-sm rounded-3">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
              How Can I Register for Test?
            </button>
          </h2>
          <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
            <div class="accordion-body">
              Registration can be done online through the our portal portal.
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- Footer -->
   <?php
      require_once 'MainFooter.php';
   ?>


  

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
