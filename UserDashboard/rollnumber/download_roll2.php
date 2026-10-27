<?php
require '../../vendor/autoload.php';  // Composer autoload
require_once '../connect/connect.php';

use Dompdf\Dompdf;

// Get roll slip ID from GET
$roll_id = $_GET['id'] ?? 0;

if ($roll_id == 0) {
    die('Invalid Roll Slip ID');
}

// Fetch roll slip data from DB
$sql = "SELECT 
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
        AND a.application_id = ?                    -- Candidate specific
        ORDER BY qp.qp_id ASC             -- Ensures quiz order matches post order

    ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $roll_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) die('Roll slip not found');


    if ($row['passpercentage'] > $row['mpercentage']){$status = "FAIL";} 
    if ($row['passpercentage'] <= $row['mpercentage']) { $status = "PASS"; }
// Convert image to base64 for Dompdf
function getBase64Image($fileName, $default = 'default.png') {
    $path = __DIR__ . '/../uploads/' . $fileName;
    if (!file_exists($path) || empty($fileName)) {
       
        $path = __DIR__ . '/../uploads/' . $default;
    }
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = base64_encode(file_get_contents($path));
    return "data:image/$type;base64,$data";
}

$profileImg = getBase64Image($row['profile_image']);

function getBase64ImageFromPath($filePath) {
    if (!file_exists($filePath)) return '';
    $type = pathinfo($filePath, PATHINFO_EXTENSION);
    $data = base64_encode(file_get_contents($filePath));
    return "data:image/$type;base64,$data";
}

// Use the specific image
$headerImg = getBase64ImageFromPath(__DIR__ . '/../../Picture/2.jpg');

$html = '
<div style="max-width: 800px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; font-family: Times New Romance, serif;">
    <div style="text-align:center; margin-bottom: 15px;">
        <img src="'.$headerImg.'" alt="Profile" style="width:90px; height:90px; object-fit:cover; border-radius: 50%; margin:0 auto 10px auto;">
        <div style="text-align: center; flex-grow: 1;">
            <h2 style="margin-bottom: 0; font-weight: bold;">Online Testing Service (OTS)</h2>
            <h5 style="margin-bottom: 5px;">Sohail Ahmad Company</h5>
            <p style="background-color: #6c757d; color: white; display: inline-block; padding: 4px 8px; margin: 0;">ROLL NUMBER SLIP for COMPUTER BASED TEST</p>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <div><strong>TEST CENTER:</strong> '.$row['otest_center'].'</div>
        <div><strong>REPORTING TIME:</strong> '.$row['oreporting_time'].'</div>
        <div><strong>TEST DATE:</strong> '.$row['otest_date'].'</div>
    </div>
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
        <div><strong>ONLINE TEST MARKS:</strong>' . $row['obtained_marks'] . '/' . $row['total_marks'] . '</div>
        <div><strong>PERCENTAGE:</strong> '.$row['mpercentage'].'</div>
        <div><strong>TEST STATUS:</strong> '.$status.'</div>
    </div>

    <div style="display: flex;">
        
            <table style="width:100%; border-collapse: collapse;">
                <tr>
                    <!-- Left column: details -->
                    <td style="width:70%; vertical-align:top; padding-right:10px;">
                        <table style="width:100%; border-collapse: collapse; border:1px solid #ddd;">
                            <tbody>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>ROLL NUMBER:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['rollno'].'</td></tr>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>TEST FOR:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['qp_title'].'</td></tr>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>CNIC No:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['cnic'].'</td></tr>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>NAME:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['fname'].' '.$row['lname'].'</td></tr>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>FATHER NAME:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['fathername'].'</td></tr>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>DEPARTMENT:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['categery'].'</td></tr>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>GENDER:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['gender'].'</td></tr>
                                <tr><td style="border:1px solid #ddd; padding:8px;"><strong>RELIGION:</strong></td><td style="border:1px solid #ddd; padding:8px;">'.$row['religion'].'</td></tr>
                            </tbody>
                        </table>
                    </td>

                    <!-- Right column: profile image -->
                    <td style="width:30%; vertical-align:top; text-align:center;">
                        <img src="'.$profileImg.'" style="max-width:100%; height:auto; border-radius:15%; border:1px solid #ddd;">
                    </td>
                </tr>
            </table>
       </div>
        
    
</div>
';

// Initialize Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("rollslip_".$row['rollno'].".pdf", ["Attachment" => true]);
exit;
?>
