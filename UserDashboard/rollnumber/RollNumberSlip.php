<?php
  require_once '../connect/connect.php';
  $sql = "SELECT 
    a.application_id,
    a.post_id,
    a.rollno,
    p.title AS post_title,
    p.categery,
    rc.test_center,
    rc.test_date,
    rc.reporting_time,
    u.fname,
    u.lname,
    u.fathername,
    u.gender,
    u.religion,
    u.cnic,
    u.profile_image
FROM applications a
JOIN posts p ON a.post_id = p.post_id
JOIN rollcreater rc ON a.post_id = rc.post_id
JOIN candidate u ON a.id = u.id
WHERE a.id = ?
AND a.status = 'Accepted'
ORDER BY a.application_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
  "i", 
  $user_id
);
$stmt->execute();
$result = $stmt->get_result();
 
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

$result2 = $stmt2->get_result();
$answer = $result2->num_rows;
$status = "";

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
        <div class="accordion" id="accordionPhysical">
          <?php
              $counter = 1;
              while($row = $result->fetch_assoc()) {
                        
            ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="<?= $counter ?>">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-phy-<?= $counter ?>" aria-expanded="true" aria-controls="collapse-phy-<?= $counter ?>">
                <?php 
                  echo $row['post_title'] . " (Roll no #" . $counter . ")"; 
                ?>
              </button>
            </h2>
            <div id="collapse-phy-<?= $counter ?>" class="accordion-collapse collapse" aria-labelledby="heading-phy-<?= $counter ?>" data-bs-parent="#accordionPhysical">
              <div class="accordion-body">
                <div id="roll-slip-<?= $counter ?>" class="roll-slip mb-4">

                  <div class="text-center mb-4">
                    <div class="d-flex justify-content-center align-items-center">
                      <img 
                      src="../../Picture/Sproject.png" alt="Admin Profile"
                      alt="Profile Image" 
                      class="rounded border rounded-4 me-2"
                      style=" object-fit: cover; width: 60px;"
                      >
                    
                      <div>
                        <div class="header-title"> Online Testing Service (OTS)</div>
                        <div>Sohail Ahmad Company</div>
                      </div>
                    </div>
                    <div class="sub-title mt-2">ROLL NUMBER SLIP for PHYSICAL BASED TEST</div>
                  </div>

                  <div class="mb-3">
                    <strong>TEST CENTER:</strong> <?php echo $row['test_center']; ?>
                  </div>
                  <div class="mb-3 d-flex justify-content-between">
                    <div><strong>TEST DATE:</strong> <?php echo $row['test_date']; ?></div>
                    <div><strong>REPORTING TIME:</strong> <?php echo $row['reporting_time']; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-md-8">
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td><strong>ROLL NUMBER:</strong></td>
                            <td><?php echo $row['rollno']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>TEST FOR:</strong></td>
                            <td><?php echo $row['post_title']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>CNIC No:</strong></td>
                            <td><?php echo $row['cnic']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>NAME:</strong></td>
                            <td><?php echo $row['fname']." ".$row['lname'] ; ?></td>
                          </tr>
                          <tr>
                            <td><strong>FATHER NAME:</strong></td>
                            <td><?php echo $row['fathername']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>DEPARTMENT:</strong></td>
                            <td><?php echo $row['categery']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>GENDER:</strong></td>
                            <td><?php echo $row['gender']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>RELIGION:</strong></td>
                            <td><?php echo $row['religion']; ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-start">
                      <img 
                      src="
                        <?php
                          if(!empty($row['profile_image'])){
                            echo '../uploads/' . $row['profile_image'];
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
                      <button class="btn btn-primary" onclick="window.location.href='download_roll.php?id=<?= $row['application_id'] ?>'">
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


      <div class="accordion" id="accordionComputer">
          <?php
              $counterr = 1;
              while($row2 = $result2->fetch_assoc()) {
                if ($row2['passpercentage'] > $row2['mpercentage']){$status = "FAIL";} 
                if ($row2['passpercentage'] <= $row2['mpercentage']) { $status = "PASS"; }

                
                        
            ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading-com-<?= $counterr ?>">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-com-<?= $counterr ?>" aria-expanded="true" aria-controls="collapse-com-<?= $counterr ?>">
                <?php 
                  echo $row2['qp_title'] . " (Roll no #" . $counterr . ")"; 
                ?>
              </button>
            </h2>
            <div id="collapse-com-<?= $counterr ?>" class="accordion-collapse collapse" aria-labelledby="heading-com-<?= $counterr ?>" data-bs-parent="#accordionComputer">
              <div class="accordion-body">
                <div id="roll-slip-<?= $counterr ?>" class="roll-slip mb-4">

                  <div class="text-center mb-4">
                    <div class="d-flex justify-content-center align-items-center">
                      <img 
                      src="../../Picture/Sproject.png" 
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
          $counterr++;
              }
        ?>
      </div>

    </main>
  </div>
    
  <script src="../../AttachtableFile/bootstrap-5.3.7-dist/js/bootstrap.bundle.js"></script>
  </body>
</html>