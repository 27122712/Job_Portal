<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

$host = "localhost";
$user = "root";
$password = "";
$db = "jobportal";
$port = "3307";

$conn = new mysqli($host, $user, $password, $db, $port);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$first = $_POST['first_name'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$exp = $_POST['experience'];
$edu = $_POST['education'];
$status = "pending";

// Handle resume upload
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
  mkdir($targetDir);
}
$resumeName = $_FILES["resume"]["name"];
$targetFile = $targetDir . time() . "_" . basename($resumeName);
move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFile);

// Save to database
$sql = "INSERT INTO applications (first_name, last_name, email, phone, experience, education, resume_path, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $first, $last, $email, $phone, $exp, $edu, $targetFile, $status);

if ($stmt->execute()) {
  // Now send email with PHPMailer
  $mail = new PHPMailer(true);
  try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'khushigoyal127@gmail.com'; 
    $mail->Password = 'uffk ctab mjki sbed'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email content
    $mail->setFrom('khushigoyal127@gmail.com', 'Job Portal');
    $mail->addAddress('khushigoyal127@gmail.com'); // Receiver 

    $mail->Subject = "New Job Application from $first $last";
    $mail->Body = "A new application has been submitted.\n\nName: $first $last\nEmail: $email\nPhone: $phone\nExperience: $exp\nEducation: $edu";

    $mail->addAttachment($targetFile); // Resume file

    $mail->send();
    echo "<script>alert('Application submitted and email sent!');window.history.back();</script>";
  } catch (Exception $e) {
    echo "Application saved, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
} else {
  echo "Error: " . $stmt->error;
}
?>
