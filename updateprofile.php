<?php
session_start();
$conn = new mysqli("localhost", "root", "", "loomify");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  $sql = "UPDATE users SET email = ?, phone_number = ?, Address = ? WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
  $stmt->bind_param("sssi", $email, $phone, $address, $user_id);
  $stmt->execute();

  header("Location: profile.php");
  exit();
}

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-6" style="background-color: rgb(248, 211, 183);">

  <form method="POST" class="bg-white/90 shadow-2xl rounded-3xl p-10 w-full max-w-xl space-y-6">
    <h2 class="text-3xl font-bold text-center text-[#4e6cf0]">Edit Profile</h2>

    <div>
      <label class="block text-sm font-semibold mb-1">Username (read-only)</label>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full px-4 py-2 rounded-md bg-gray-100 cursor-not-allowed" readonly>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-4 py-2 rounded-md border border-gray-300">
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1">Phone Number</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($user['phone_number']) ?>" class="w-full px-4 py-2 rounded-md border border-gray-300">
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1">Address</label>
      <textarea name="address" rows="3" class="w-full px-4 py-2 rounded-md border border-gray-300"><?= htmlspecialchars($user['Address']) ?></textarea>
    </div>

    <div class="text-center">
      <button type="submit" class="bg-[#4e6cf0] hover:bg-[#3d59d9] text-white px-6 py-2 rounded-full font-semibold shadow-md transition">
        Save Changes
      </button>
    </div>
    <div class="mt-4 text-center">
    <a href="index.php" class="text-[#4e6cf0] hover:underline font-semibold">
        <-Back to Home
    </a>
  </form>

</body>
</html>
