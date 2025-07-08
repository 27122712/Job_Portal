<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "jobportal";
$port = "3307"; 

$conn = new mysqli($host, $user, $password, $db, $port);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Insert into DB
$sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
  echo "<script>alert('Message sent and saved!'); window.location.href='contact.html';</script>";
} else {
  echo "Error: " . $stmt->error;
}
?>
