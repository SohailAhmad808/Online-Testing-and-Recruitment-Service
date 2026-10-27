
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Testing and Recruitment</title>
  <link rel="icon" type="image/x-icon" href="../Picture/Sproject.png">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>
<body>
  <!-- Header -->
   <?php
    require_once 'MainHeader.php';
   ?>

   <!-- Body -->
<!-- Result & Roll Slip Info Section -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-3">Access Your Results & Roll Slip</h2>
    <p class="text-muted mb-4">
      Check your test results and download your roll number slip directly from your account dashboard.
    </p>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4 p-4">
          <i class="fa-solid fa-circle-info fa-3x text-primary mb-3"></i>
          <h5 class="fw-semibold mb-2">Your Account</h5>
          <p class="text-muted">
            Log in to view detailed marks, test status, and download your roll number slip safely.
          </p>
          <a href="../Login&Registration/UserLogin.php" class="btn btn-primary mt-3">
            Go to My Account
          </a>
        </div>
      </div>
    </div>
  </div>
</section>





  <!-- Footer -->
   <?php
      require_once 'MainFooter.php';
   ?>
  <!-- JS (Updated for multiple tests) -->

  <script src="../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  <script>
  // Demo DB - Multiple tests per roll
  const demoResults = {
    "12345": [
      { test: "Math", name: "Ali Khan", marks: 85, status: "Pass" },
      { test: "English", name: "Ali Khan", marks: 72, status: "Pass" },
      { test: "Science", name: "Ali Khan", marks: 49, status: "Fail" }
    ],
    "67890": [
      { test: "Math", name: "Sara Ahmed", marks: 42, status: "Fail" },
      { test: "English", name: "Sara Ahmed", marks: 64, status: "Pass" }
    ],
    "11111": [
      { test: "Science", name: "John Doe", marks: 76, status: "Pass" }
    ]
  };

  document.getElementById("resultForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const roll = document.getElementById("rollNumber").value.trim();
    const resultBox = document.getElementById("resultBox");
    const tableBody = document.getElementById("resultTableBody");

    tableBody.innerHTML = ""; // Clear old results
    resultBox.classList.remove("d-none");

    if (demoResults[roll]) {
      demoResults[roll].forEach(res => {
        const row = `
          <tr>
            <td>${res.test}</td>
            <td>${res.name}</td>
            <td>${roll}</td>
            <td>${res.marks}</td>
            <td class="${res.status === 'Pass' ? 'text-success fw-bold' : 'text-danger fw-bold'}">${res.status}</td>
          </tr>
        `;
        tableBody.insertAdjacentHTML("beforeend", row);
      });
    } else {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="text-danger fw-bold">No record found for Roll Number: ${roll}</td>
        </tr>
      `;
    }
  });
</script>
</body>
</html>
