<?php
  require_once '../connect/connect.php';
//   $sql = "SELECT 
//         u.id,
//         u.fname,
//         u.lname,
//         a.rollno,
//         qp.qp_title,
//         r.obtained_marks,
//         r.total_marks,
//         r.mpercentage,
//         oqc.passpercentage
//     FROM quiz_results r
//     JOIN candidate u 
//         ON u.id = r.id
//     JOIN applications a
//         ON a.id = u.id
//     JOIN question_papers qp 
//         ON qp.qp_id = r.qp_id
//     JOIN onlinequizcall oqc 
//         ON oqc.qp_id = qp.qp_id
//     WHERE r.mpercentage >= oqc.passpercentage
//     AND a.id = ?;
//     ";
//   $sql = "SELECT 
//         u.id,
//         u.fname,
//         u.lname,
//         a.rollno,
//         qp.qp_title,
//         r.obtained_marks,
//         r.total_marks,
//         r.mpercentage,
//         p.title,
//         oqc.otest_center,
//         oqc.otest_date,
//         oqc.oreporting_time,
//         oqc.passpercentage
//     FROM quiz_results r
//     JOIN candidate u 
//         ON u.id = r.id
//     JOIN applications a
//         ON a.id = u.id
//     JOIN question_papers qp 
//         ON qp.qp_id = r.qp_id
//     JOIN posts p
//         ON p.post_id = qp.post_id
//     JOIN onlinequizcall oqc 
//         ON oqc.oqc_id = qp.qp_id
//     WHERE r.mpercentage >= oqc.passpercentage
//     AND u.id = ?;
//     ";
  $sql2 = "SELECT 
            u.id,
            u.fname,
            u.lname,
            u.profile_image,
            u.cnic,
            u.fathername,
            u.gender,
            u.religion,
            a.rollno,
            a.application_id,
            qp.qp_title,
            r.obtained_marks,
            r.total_marks,
            r.mpercentage,
            p.categery,
            oqc.otest_center,
            oqc.otest_date,
            oqc.oreporting_time,
            oqc.passpercentage
        FROM applications a
        JOIN candidate u 
            ON u.id = a.id
        JOIN posts p
            ON p.post_id = a.post_id
        JOIN question_papers qp
            ON qp.post_id = p.post_id
        JOIN onlinequizcall oqc
            ON oqc.qp_id = qp.qp_id
        JOIN quiz_results r
            ON r.id = u.id AND r.qp_id = qp.qp_id
        WHERE a.status = 'Accepted'       -- Only accepted applications
        AND r.mpercentage >= oqc.passpercentage  -- Candidate passed online quiz
        AND u.id = ?                     -- Candidate specific
        ORDER BY qp.qp_id ASC             -- Ensures quiz order matches post order

    ";
$stmt2 = $conn->prepare($sql2);

if ($stmt2 === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt2->bind_param(
  "i", 
  $user_id
);
if (!$stmt2->bind_param("i", $user_id)) {
    die("Bind Error: " . $stmt2->error);
}

$stmt2->execute();


if (!$stmt2->execute()) {
    die("Execute Error: " . $stmt2->error);
}

$result = $stmt2->get_result();
$answer = $result->num_rows;
echo $answer;
$status = "";


  // ////////////////////////////// Online Test Roll number Post /////////////////////
  // $sql2 = " SELECT
  // ap.application_id,
  // ap.post_id,
  // ap.rollno,
  // p.title AS post_title,
  // p.categery,
  // u.fname,
  // u.lname,
  // u.fathername,
  // u.gender,
  // u.religion,
  // u.cnic,
  // u.profile_image,
  // r.passpercentage,
  // oq.passpercentage
  // FROM applications ap
  // JOIN posts p ON ap.post_id = p.post_id
  // JOIN candidate u ON ap.id = u.id
  // JOIN quiz_results r ON r.id = u.id
  // JOIN onlinequizcall oq ON r.passpersentage = oq.passpercentage
  // AND a.status = 'Accepted'
  // WHERE a.id = ?
  // ";
  // $stmt22 = $conn->prepare($sql2);
  // $stmt22->bind_param(
  //   "i", 
  //   $user_id
  // );
  // $stmt22->execute();
  // $result2 = $stmt22->get_result();
  










?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Header</title>
    <link rel="stylesheet" href="../../AttachtableFile/bootstrap-5.3.7-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
    .roll-slip {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      border: 2px solid #000;
    }
    .roll-slip table td {
      padding: 8px;
    }
    .header-title {
      font-size: 1.4rem;
      font-weight: bold;
    }
    .sub-title {
      background-color: #ccc;
      padding: 5px 10px;
      display: inline-block;
      margin-top: 10px;
    }


  </style>
  </head>
  <body>  
    <!--
          Header
          -->
          <?php
          require '../others/UserHeader.php';
          ?>
      <!--
      Convas Start Here
      -->
    <?php
    require '../others/UserSidebar.php';
    ?>
    <main class="col-md-9 col-lg-10">
        <div class="accordion" id="accordionExample">
          <?php
              $counter = 1;
              while($row2 = $result->fetch_assoc()) {
                if ($row2['passpercentage'] > $row2['mpercentage']){$status = "FAIL";} 
                if ($row2['passpercentage'] <= $row2['mpercentage']) { $status = "PASS"; }

                
                        
            ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="<?= $counter ?>">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $counter ?>" aria-expanded="true" aria-controls="collapse<?= $counter ?>">
                <?php 
                  echo $row2['qp_title'] . " (Roll no #" . $counter . ")"; 
                ?>
              </button>
            </h2>
            <div id="collapse<?= $counter ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $counter ?>" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div id="roll-slip-<?= $counter ?>" class="roll-slip mb-4">

                  <div class="text-center mb-4">
                    <div class="d-flex justify-content-center align-items-center">
                      <img 
                      src="
                        <?php
                          if(!empty($row2['profile_image'])){
                            echo 'uploads/' . $row2['profile_image'];
                          }
                        ?>
                      " 
                      alt="Profile Image" 
                      class="rounded border rounded-4 me-2"
                      style=" object-fit: cover; width: 60px;"
                      >
                    
                      <div>
                        <div class="header-title"> Online Testing Service (OTS)</div>
                        <div>Sohail Ahmad Company</div>
                      </div>
                    </div>
                    <div class="sub-title mt-2">ROLL NUMBER SLIP for COMPUTER BASED TEST</div>
                  </div>

                  <div class="mb-3">
                    <strong>TEST CENTER:</strong> <?php echo $row2['otest_center']; ?>
                  </div>
                  <div class="mb-3 d-flex justify-content-between">
                    <div><strong>TEST DATE:</strong> <?php echo $row2['otest_date']; ?></div>
                    <div><strong>REPORTING TIME:</strong> <?php echo $row2['oreporting_time']; ?></div>
                  </div>
                  <div class="mb-3 d-flex justify-content-between">
                    <div><strong>ONLINE TEST MARKS:</strong> <?php echo $row2['obtained_marks']."/". $row2['total_marks']; ?></div>
                    <div><strong>PERCENTAGE:</strong> <?php echo $row2['mpercentage']; ?></div>
                    <div><strong>TEST STATUS:</strong> <?php echo $status; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-md-8">
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td><strong>ROLL NUMBER:</strong></td>
                            <td><?php echo $row2['rollno']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>TEST FOR:</strong></td>
                            <td><?php echo $row2['qp_title']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>CNIC No:</strong></td>
                            <td><?php echo $row2['cnic']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>NAME:</strong></td>
                            <td><?php echo $row2['fname']." ".$row2['lname'] ; ?></td>
                          </tr>
                          <tr>
                            <td><strong>FATHER NAME:</strong></td>
                            <td><?php echo $row2['fathername']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>DEPARTMENT:</strong></td>
                            <td><?php echo $row2['categery']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>GENDER:</strong></td>
                            <td><?php echo $row2['gender']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>RELIGION:</strong></td>
                            <td><?php echo $row2['religion']; ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-start">
                      <img 
                      src="
                        <?php
                          if(!empty($row2['profile_image'])){
                            echo '../uploads/' . $row2['profile_image'];
                          }
                        ?>
                      " 
                      alt="Profile Image" 
                      class="img-fluid w-100 rounded border rounded-4"
                      style=" object-fit: cover;"
                      >
                      
                    </div>
                  </div>
                  <div class="text-center">
                      <button class="btn btn-primary" onclick="window.location.href='download_roll2.php?id=<?= $row2['application_id'] ?>'">
                        <i class="fa-solid fa-print"></i> Download PDF
                    </button>


                  </div>

              </div>
            </div>
            </div>
          </div>
        <?php
          $counter++;
              }
        ?>
      </div>


    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>