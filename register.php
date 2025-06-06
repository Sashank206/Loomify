<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup page</title>
</head>
<body>
    <style>
        *{
    margin: 0;
    padding: 0;
    font-family: sans-serif;
    box-sizing: border-box;
}
body{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color:rgb(248, 211, 183);
}
.login{
    background-color: aliceblue;
    padding: 30px;
    border-radius: 30px;
    text-align: center;
    width:350px;
}
.logo{
    width:150px;
    margin-bottom: 10px;
    border:3px solid black;
    border-radius: 20px;
}
h2{
    text-align: center;
    margin-bottom: 30px;
    font-weight: bold;
}

label{
    color:black;
    font-weight: bold;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    margin-top: 10px;

}
form{
    display: flex;
    flex-direction: column;
    text-align: left;
}
input{
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 20px;
    background: white;
    color:black;
    border: 1px solid black;
    font-size: 16px;

}
.submit{
    padding: 10px;
    border-radius: 40px;
    width:150px;
    border:2px solid black;
    margin-top: 10px;
    margin-left: 75px;
    background-color: grey;
    font-size: 18px;
    color:black;
    font-weight: bold;
    cursor: pointer;

}
.submit:hover{
    background-color: aquamarine;
}
.last{
    margin-top: 10px;
    font-size: 10px;
    color:black;
    text-align: right;
}
.error{
    color: red;
    font-size: 15px;  
    
    }

.role-selector {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.role-btn {
    padding: 10px 30px;
    margin: 0 10px;
    border: 2px solid black;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    background-color: white;
    color: black;
    transition: all 0.3s ease;
}

.role-btn.active {
    background-color: black;
    color: white;
}

</style>
    <div class="login" method="POST">
    <!-- logo pic -->
    <img src="src/logo.png" alt="logo png" class="logo"> 
    <!-- n -->
    <h2>SIGN UP</h2> 


    <!-- add home page here -->
    <form action="" id="myform" method="POST">
    <div class="role-selector">
        <!-- Displayed radio buttons will now be handled by labels -->
        <input type="radio" id="buyer" name="role" value="buyer" checked hidden>
        <input type="radio" id="seller" name="role" value="seller" hidden>

        <label for="buyer" class="role-btn active" id="buyerBtn">Buyer</label>
        <label for="seller" class="role-btn" id="sellerBtn">Seller</label>
    </div>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name">
    <span class="error" id="nameerror"></span>

    <label for="phone_number">Phone Number:</label>
    <input type="number" id="phone_number" name="phone_number">
    <span class="error" id="phone_numbererror"></span>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email">
    <span class="error" id="emailerror"></span>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <span class="error" id="passworderror"></span>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address">

    <button class="submit" type="submit">SIGN UP</button>
    
    <div class="last">
        <h4>By signing up, you agree to our <a href="term.html">Terms and Conditions</a>.</h4><br>
        <button type="reset">Reset</button>
    </div>
</form>

<script>
    // JavaScript to toggle the active class and select the corresponding radio button
    const buyerBtn = document.getElementById('buyerBtn');
    const sellerBtn = document.getElementById('sellerBtn');
    const buyerRadio = document.getElementById('buyer');
    const sellerRadio = document.getElementById('seller');

    buyerBtn.addEventListener('click', () => {
        buyerBtn.classList.add('active');
        sellerBtn.classList.remove('active');
        buyerRadio.checked = true;
    });

    sellerBtn.addEventListener('click', () => {
        sellerBtn.classList.add('active');
        buyerBtn.classList.remove('active');
        sellerRadio.checked = true;
    });

    // Ensure the form submits the correct role value
    document.getElementById('myform').addEventListener('submit', (event) => {
        // Check if a role is selected
        if (!buyerRadio.checked && !sellerRadio.checked) {
            event.preventDefault();
            alert('Please select a role (Buyer or Seller)');
        }
    });
</script>

    </div>



    <!-- java script  -->
    <script>
    document.getElementById("myform").addEventListener("submit", function(event) {
        let isValid = true;

        // Validate Name
        const name = document.getElementById("name").value.trim();
        if (name === "") {
        document.getElementById("nameerror").textContent = "Name is required.";
        isValid = false;
        } else if (name.length < 3 || name.length > 10) {
        document.getElementById("nameerror").textContent = "Name must be between 3 and 10 characters.";
        isValid = false;
        } else {
        document.getElementById("nameerror").textContent = "";
        }

        // Validate Phone Number
        const phoneNumber = document.getElementById("phone_number").value.trim();
        if (phoneNumber === "") {
        document.getElementById("phone_numbererror").textContent = "Phone number is required.";
        isValid = false;
        } else if (!/^\d{10}$/.test(phoneNumber)) {
        document.getElementById("phone_numbererror").textContent = "Phone number must be 10 digits.";
        isValid = false;
        } else {
        document.getElementById("phone_numbererror").textContent = "";
        }

        // Validate Email
        const email = document.getElementById("email").value.trim();
        if (email === "") {
        document.getElementById("emailerror").textContent = "Email is required.";
        isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById("emailerror").textContent = "Invalid email format.";
        isValid = false;
        } else {
        document.getElementById("emailerror").textContent = "";
        }

        // Validate Password
        const password = document.getElementById("password").value.trim();
        if (password === "") {
        document.getElementById("passworderror").textContent = "Password is required.";
        isValid = false;
        } else if (password.length < 6 || !/[A-Z]/.test(password) || !/[!@#$%^&*]/.test(password)) {
        document.getElementById("passworderror").textContent = "Password must be at least 6 characters long, contain an uppercase letter, and a special character (!@#$%^&*).";
        isValid = false;
        } else {
        document.getElementById("passworderror").textContent = "";
        }

        // Prevent form submission if validation fails
        if (!isValid) {
        event.preventDefault();
        }
    });
</script>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = trim($_POST['address']);
    $role = isset($_POST['role']) && $_POST['role'] === 'seller' ? 'seller' : 'buyer';

    $sql = "INSERT INTO users (username, email, phone_number, password, address, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $username, $email, $phone_number, $password, $address, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Signup Successful!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Number or Email Already Exists.');</script>";
    }
    $stmt->close();
}

?>

</body>
</html>