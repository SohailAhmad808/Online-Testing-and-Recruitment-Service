<?php
require_once '../connect.php';
$search_value = isset($_POST["search"]) ? $conn->real_escape_string($_POST["search"]) : "";
$output = '';
$sql = "
            SELECT 
                tr.*, 
                u.fname,
                u.id,
                u.cnic,
                u.lname As last_name
            FROM quiz_results tr
            JOIN candidate u ON u.id = tr.id
            WHERE cnic LIKE ? OR fname LIKE ? OR qp_title LIKE ?
            ORDER BY tr.attempt_date DESC
        ";
    $stmt = $conn->prepare($sql);
    $like = "%$search_value%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result_data = $stmt->get_result();
    $number_of_rows=$result_data->num_rows;
     if($number_of_rows > 0){
        while ($row = $result_data->fetch_assoc()){
            $output .= '<tr>
            <td>' . htmlspecialchars($row['fname']) . '</td>
            <td>' . htmlspecialchars($row['qp_id']) . '</td>
            <td>' . htmlspecialchars($row['qp_title']) . '</td>
            <td>' . htmlspecialchars($row['obtained_marks']) . ' / ' . htmlspecialchars($row['total_marks']) . '</td>
            <td>' . htmlspecialchars($row['mpercentage']) . '%</td>
            <td>' . htmlspecialchars($row['attempt_date']) . '</td>
            <td>
                <div class="btn-group" role="group">
                    <a href="?view_profile=' . $row['id'] . '" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <form method="POST" action="ManageResult.php" 
                          onsubmit="return confirm(\'Are you sure you want to delete this result?\');">
                        <input type="hidden" name="result_id" value="' . $row['result_id'] . '">
                        <button type="submit" name="delete_test" class="btn btn-outline-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>';
        }
     }else{
        $output = '<tr><td colspan="7" class="text-center">No records found</td></tr>';
     }
     // The output is given by 
    echo $output;
?>