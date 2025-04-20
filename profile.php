<?php
session_start();
$conn = new mysqli("localhost", "root", "", "loomify");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error: " . $conn->error);
  }
  
$stmt->bind_param("i", $user_id);

$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-6" style="background-color: rgb(248, 211, 183);">

  <div class="bg-white/90 shadow-2xl rounded-3xl p-10 w-full max-w-xl">
    <h2 class="text-3xl font-bold text-center text-[#4e6cf0] mb-6">Welcome, <?= htmlspecialchars($user['username']) ?></h2>

    <div class="space-y-4 text-gray-800">
      <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($user['email']) ?></p>
      <p><span class="font-semibold">Phone:</span> <?= htmlspecialchars($user['phone_number'] ?? 'Not Provided') ?></p>
      <p><span class="font-semibold">Address:</span> <?= htmlspecialchars($user['Address'] ?? 'Not Provided') ?></p>
      <p><span class="font-semibold">Joined:</span> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
    </div>

    <div class="mt-8 text-center">
      <a href="updateprofile.php" class="bg-[#4e6cf0] hover:bg-[#3d59d9] text-white px-6 py-2 rounded-full font-semibold shadow-md transition">
        Edit Profile
      </a>
    </div>
    <div class="mt-4 text-center">
    <a href="index.php" class="text-[#4e6cf0] hover:underline font-semibold">
        <-Back to Home
    </a>
</div>

  </div>
</body>
</html>
