<?php
$host="localhost";
$user="root";
$password="";
$dbname="jobportal";
$port="3307";

$conn=new mysqli($host,$user,$password,$dbname,$port);
if($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
}
uffk ctab mjki sbed
$first=$_POST['first_name'];
$last=$_POST['last_name'];
$email=$_POST['email'];
$phone=$POST['phone'];
$exp=$_POST['experience'];
$edu=$_POST['education'];
$status="pending";

$targetDir="uploads/";
if(!is_dir($targetDir)) {
    mkdir($targetDir);
}

$resume_name=$FILES["resume"]["name"];
$targetFile = $targetDir . time() . "_" .basename($resume_name);
move_uploaded_file($FILES["resume"]["tmp_name"],$targetFile);

$sql="INSERT INTO applications(First_name,Last_name,Email,Phone,Experience,Education,resume_path,status,created_at,updated_at)
VALUES('?','?','?','?','?','?','?','?',NOW(),NOW())";

$stmt=$conn->prepare($sql);
$stmt->bind_param("ssssssss", $first, $last, $email, $phone, $exp, $edu, $targetFile, $status);
