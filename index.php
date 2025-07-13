<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Online Bank Loan Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    body {
      background-color: #f8f9fa;
      color: #343a40;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #343a40;
    }
    .navbar-brand, .nav-link {
      color: #f8f9fa !important;
      font-weight: bold;
    }
   .nav-link {
  position: relative;
  transition: color 0.3s ease;
}

.nav-link:hover {
  color: #d3bfff !important;
}

.nav-link::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  height: 3px;
  width: 0;
  background-color: #d3bfff;
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 100%;
}

    .hero {
      padding: 80px 0;
      background: linear-gradient(to right, #4c4caa, #8360c3);
      color: #ffffff;
    }
    .hero h1, .hero p {
      color: #ffffff;
    }
    .hero .btn-primary {
      background-color: #ffffff;
      color: #4c4caa;
      border: none;
    }
    .hero .btn-primary:hover {
      background-color: #d3bfff;
      color: #343a40;
    }
    .hero .btn-outline-primary {
      color: #ffffff;
      border: 2px solid #ffffff;
    }
    .hero .btn-outline-primary:hover {
      background-color: #ffffff;
      color: #4c4caa;
    }
    .section {
      padding: 60px 0;
      background-color: #ffffff;
      color: #343a40;
    }
    .section h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #4c4caa;
    }
    
    .btn-custom {
      background-color: #4c4caa;
      color: white;
      border: none;
    }
    .btn-custom:hover {
      background-color: #3a3a8a;
    }
    .service-box {
      background: #f3f4f8;
      color: #4c4caa;
      padding: 20px;
      border-radius: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .service-box:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 20px rgba(76, 76, 170, 0.3);
    }
    .about-img {
      max-width: 100%;
      border-radius: 10px;
    }
    #contact {
      background-color: #4c4caa;
      color: #ffffff;
    }
  
#about, #services {
  background-color: #d3bfff; /* Light purple background */
  color: #102336; /* Dark text for contrast */
}

.service-box {
  background: #ffffff; /* White background for service boxes */
  color: #102336; /* Dark text */
  padding: 20px;
  border-radius: 10px;
  transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
}

.service-box:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  background-color: #5D88BB; /* Change to blue on hover */
  color: #ffffff; /* Change text color to white on hover for better visibility */
}


   
    .form-control, textarea {
      border-radius: 8px;
    }
    label {
      font-weight: 500;
    }
    .btn-light {
      background-color: #ffffff;
      color: #4c4caa;
    }
    .btn-light:hover {
      background-color: #d3bfff;
      color: #343a40;
    }
    
  </style>
</head>
<body>

  <!-- Header/Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <!-- Logo and Portal Name on Left -->
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/mylogo.png" alt="Logo" width="90" class="me-2">
      <span>Online Bank Loan Portal</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="managers-login.php">Managers Login</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Prevent content from hiding under fixed navbar -->
<style>
  body {
    padding-top: 80px; /* Adjust according to your navbar height */
  }
</style>


  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="display-5 fw-bold">Welcome to Online Bank Loan Portal</h1>
          <p class="lead mt-3">Apply for loans easily with secure & fast processing. Personal, home, or business loans at your fingertips.</p>
          <div class="mt-4">
            <a href="customer/customer_login.php" class="btn btn-primary btn-lg me-3">Login</a>
            <a href="Customer/register.php" class="btn btn-outline-primary btn-lg">Register</a>
          </div>
        </div>
        <div class="col-md-6 text-center">
          <img src="img/mybg.png" alt="Hero Image" class="img-fluid">
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="section">
    <div class="container">
      <h2>About Us</h2>
      <div class="row align-items-center">
        <div class="col-md-6">
          <p>At Online Bank Loan Portal, we aim to transform the loan process by digitizing every step. With our easy-to-use platform, you can apply for a wide range of loans from the comfort of your home. We focus on transparency, speed, and customer support to ensure the best experience for every user. Our mission is to empower individuals and businesses through accessible financial services.</p>
        </div>
        <div class="col-md-6 text-center">
          <img src="img/about1.png" class="about-img" alt="About Image">
        </div>
      </div>
    </div>
  </section>

 <!-- Services Section -->
<section id="services" class="section py-5">
  <div class="container">
    <h2 class="text-center mb-4">Our Services</h2>
    <div class="row g-4 text-center">
      
      <div class="col-md-4">
        <div class="service-box p-4 border rounded shadow-sm">
          <div class="icon mb-3">
            <i class="bi bi-wallet2 fs-1 text-primary"></i>
          </div>
          <h5>Personal Loans</h5>
          <p>Quick and easy loans with flexible repayment options.</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="service-box p-4 border rounded shadow-sm">
          <div class="icon mb-3">
            <i class="bi bi-house-door fs-1 text-success"></i>
          </div>
          <h5>Home Loans</h5>
          <p>Turn your dream of owning a home into reality.</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="service-box p-4 border rounded shadow-sm">
          <div class="icon mb-3">
            <i class="bi bi-briefcase fs-1 text-warning"></i>
          </div>
          <h5>Business Loans</h5>
          <p>Fund your startup or expand your business operations.</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="service-box p-4 border rounded shadow-sm">
          <div class="icon mb-3">
            <i class="bi bi-mortarboard fs-1 text-info"></i>
          </div>
          <h5>Education Loans</h5>
          <p>Finance your academic journey with ease and confidence.</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="service-box p-4 border rounded shadow-sm">
          <div class="icon mb-3">
            <i class="bi bi-truck-front fs-1 text-danger"></i>
          </div>
          <h5>Vehicle Loans</h5>
          <p>Own your favorite car or bike with simple EMIs.</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="service-box p-4 border rounded shadow-sm">
          <div class="icon mb-3">
            <i class="bi bi-heart-pulse fs-1 text-danger"></i>
          </div>
          <h5>Medical Loans</h5>
          <p>Access urgent funds for medical emergencies instantly.</p>
        </div>
      </div>
      
    </div>
  </div>
</section>


  <!-- Contact Us -->
  <section id="contact" class="section">
    <div class="container">
      <h2 class="text-center mb-5 text-white">Contact Us</h2>
      <div class="row align-items-center">
        <div class="col-md-6 text-center mb-4 mb-md-0">
          <img src="img/3.png" style="height: 90%; object-fit: cover;" alt="Contact Image" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
          <form>
            <div class="mb-3">
              <label>Name</label>
              <input type="text" class="form-control" placeholder="Your Name">
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" placeholder="Your Email">
            </div>
            <div class="mb-3">
              <label>Message</label>
              <textarea class="form-control" rows="4" placeholder="Your Message"></textarea>
            </div>
            <button type="submit" class="btn btn-light">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </section>


<!-- Success Message (hidden initially) -->
<div id="successMessage" class="alert alert-success text-center" style="display: none; margin-top: 20px;">
  <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
  <p class="mt-3">Thank you for contacting us! We’ll get back to you soon. 😊</p>
</div>

<!-- JS for Contact Form Submission -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.querySelector("#contact form");

    if (contactForm) {
      contactForm.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent actual form submission

        // Show success message on the page
        const successMessage = document.getElementById("successMessage");
        successMessage.style.display = "block";

        // Optionally, reset the form after submission
        contactForm.reset();
      });
    }
  });
</script>




  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
