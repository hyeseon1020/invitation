<?php
include_once("../config/config.php");

$parent_code = $_GET['parent_code'] ?? '';

if(!$parent_code) exit;

$stmt = $conn->prepare("
    SELECT code_id, code_name 
    FROM tb_code 
    WHERE parent_code = ?
    AND is_active = 'Y'
    ORDER BY order_no ASC
");
$stmt->bind_param("s", $parent_code);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    echo '<option value="'.$row['code_id'].'">'.$row['code_name'].'</option>';
}
?>
