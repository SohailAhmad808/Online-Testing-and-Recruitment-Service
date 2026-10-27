<?php
  include_once 'connection.php';
  // ensure PHP uses Pakistan time
    date_default_timezone_set('Asia/Karachi');
    // Get current datetime
    $now = date('Y-m-d H:i:s');
    echo($now);
      // ===================== Fetch Tests =====================
    // Ongoing Tests: tests that are currently active
    $ongoing_sql = "SELECT qp.qp_id, p.title AS post_title, qp.number_of_questions, qp.duration, qp.test_start_time,
                DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) AS test_end_time
              FROM question_papers qp
              JOIN posts p ON p.post_id = qp.post_id
              WHERE qp.test_start_time <= NOW()
                AND DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) >= NOW()
              ORDER BY qp.test_start_time ASC;
";

                $ongoing_stmt = $conn->prepare($ongoing_sql);
                
                $ongoing_stmt->execute();
                $ongoing_result = $ongoing_stmt->get_result();

    // Upcoming Tests: tests that start in the future
    $upcoming_sql = "SELECT qp.qp_id, p.title AS post_title, qp.number_of_questions, qp.duration, qp.test_start_time
                  FROM question_papers qp
                  JOIN posts p ON p.post_id = qp.post_id
                  WHERE qp.test_start_time > NOW()
                  ORDER BY qp.test_start_time ASC;
                  ";

    $upcoming_stmt = $conn->prepare($upcoming_sql);

    $upcoming_stmt->execute();
    $upcoming_result = $upcoming_stmt->get_result();

    // Previous Tests: tests that ended in the past
    $previous_sql = "SELECT qp.qp_id, p.title AS post_title, qp.number_of_questions, qp.duration, qp.test_start_time,
                      DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) AS test_end_time
                FROM question_papers qp
                JOIN posts p ON p.post_id = qp.post_id
                WHERE DATE_ADD(qp.test_start_time, INTERVAL qp.duration MINUTE) < NOW()
                ORDER BY qp.test_start_time DESC;
                ";

                  $previous_stmt = $conn->prepare($previous_sql);
                  $previous_stmt->execute();
                  $previous_result = $previous_stmt->get_result();
?>
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
<div class="container-fluid">

          <h2 class="text-center bg-primary-subtle p-3">
            <i class="fa-solid fa-clipboard-list me-2"></i>
            Available Tests
          </h2>
          <?php $row= 11; ?>

          <!--
            Ongoing Tests
          -->


          <section class="mb-4">
            <h4><i class="fa-solid fa-circle text-danger me-2"></i>Ongoing Tests</h4>
            <div class="row">
              <?php if($row = $ongoing_result->fetch_assoc()) { 
                $end_time = date('d M Y H:i', strtotime($row['test_end_time']));
              ?>
              <div class="col-sm-10 mx-auto my-2">
                <div class="card shadow-lg hover-shadow text-center">
                  <div class="card-body border-start border-4 border-danger">
                    <h5 class="card-title fw-semibold"><?= $row['post_title'] ?></h5>
                    <p class="text-muted small mb-2"><?= date('d M Y H:i', strtotime($row['test_start_time'])) ?> | End: <?= $end_time ?></p>
                    <span class="badge bg-danger mb-2">Open Now</span>
                    <div class="text-end">
                    <a href="../Login&Registration/UserLogin.php" class="btn btn-danger">Attemt It</a>
                    </div>
                  </div>
                </div>
              </div>
              <?php 
              }else{
                ?>
                <p>No ongoing Test are availabel</p>
                <?php
              }
              ?>
            </div>
          </section>

          <!--
            Upcoming Tests
          -->

          <section class="mb-4">
            <h4><i class="fa-solid fa-circle text-primary me-2"></i>Upcoming Tests</h4>
            <div class="row">
              <?php if($row = $upcoming_result->fetch_assoc()) { ?>
              <div class="col-sm-6 my-2">
                <div class="card ">
                  <div class="card-body border-start border-4 border-primary">
                    <h5 class="card-title"><?= $row['post_title'] ?></h5>
                    <p class="text-muted">Scheduled: <?= date('d M Y H:i', strtotime($row['test_start_time'])) ?></p>
                    <span class="badge bg-primary">Upcoming</span>
                    <?php
                        $remaining = strtotime($row['test_start_time']) - time();

                        if ($remaining > 0) {
                            $hours   = floor($remaining / 3600);
                            $minutes = floor(($remaining % 3600) / 60);
                            $seconds = $remaining % 60;
                        } else {
                            $hours = $minutes = $seconds = 0;
                        }
                    ?>
                    <p class="mt-2">
                        <strong>Starts In:</strong>
                        <span>
                            <?= $hours ?>h <?= $minutes ?>m <?= $seconds ?>s
                        </span>
                    </p>
                  </div>
                </div>
              </div>
              <?php
              }else{
                ?>
                <p>No Upcomming Test are availabel</p>
                <?php
              }
              ?>
            </div>
          </section>

          <!--
            Previous Tests
          -->
          <section class="mb-4">
            <h4><i class="fa-solid fa-circle text-secondary me-2"></i>Previous Tests</h4>
            <div class="row">
              <?php if($row = $previous_result->fetch_assoc()) { ?>
              <div class="col-sm-6 my-2">
                <div class="card ">
                  <div class="card-body border-start border-4 border-secondary">
                    <h5 class="card-title"><?= $row['post_title'] ?></h5>
                    <p class="card-text">Test completed on <?= date('d M Y H:i', strtotime($row['test_end_time'])) ?></p>
                    <div class="text-end">
                      <a href="../Login&Registration/UserLogin.php" class="btn btn-outline-secondary">View Result</a>
                    </div>
                  </div>
                </div>
              </div>
              <?php 
              }else{
                ?>
                <p>No prevouse Test are availabel</p>
                <?php
              }
              ?>
            </div>
          </section>
            
        </div>

<!-- Countdown Script -->




  <!-- Footer -->
   <?php
      require_once 'MainFooter.php';
   ?>

  <!-- Bootstrap JS -->
   <script>
  // Example countdown for upcoming test
  function countdownTimer(id, targetDate) {
    let countdownEl = document.getElementById(id);
    let x = setInterval(function () {
      let now = new Date().getTime();
      let distance = targetDate - now;

      if (distance < 0) {
        clearInterval(x);
        countdownEl.innerHTML = "Test Started!";
      } else {
        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);
        countdownEl.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
      }
    }, 1000);
  }

  // Example: Upcoming Test (20 Sept 2025, 10:00 AM)
  countdownTimer("countdown1", new Date("Sep 20, 2025 10:00:00").getTime());
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
