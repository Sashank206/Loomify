
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
html {
    scroll-behavior: smooth;
  }

.logo-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ffffffaa;
    background-color: white;
}
header {
    background-color: #fdf0e6;
    padding: 4rem 1rem;
    border-bottom: 2px solid #ccc;
    height: 110px;
}


.header-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2.2rem;
}

.header-title h1 {
    font-size: 2.2rem;
    font-weight: 800;
    color: #111;
    text-align: center;
    letter-spacing: 1px;
}


/* Header Profile Dropdown */
.header-profile {
    position: absolute;
    top: 1.5rem;
    right: 2rem;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.profile-btn {
    background: none;
    border: none;
    cursor: pointer;
}

.profile-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    transition: transform 0.2s ease;
}

.profile-btn:hover .profile-icon {
    transform: scale(1.05);
}

/* Dropdown Content */
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: 120%;
    background-color: #ffffff;
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    border-radius: 0.5rem;
    z-index: 1000;
    min-width: 150px;
}

.dropdown-content a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    font-weight: 500;
    transition: background-color 2.5s ease;
}

.dropdown-content a:hover {
    background-color: #f2f2f2;
}

/* Show Dropdown on Click */
.dropdown:hover .dropdown-content {
    display: block;
}


/* Navigation Links */
.header-navigation nav ul {
    list-style: none;
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.header-navigation nav ul li a {
    background-color: #7e7e7e;
    color: white;
    padding: 0.5rem 1.2rem;
    border-radius: 9999px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.header-navigation nav ul li a:hover {
    background-color: #5e5e5e;
}

/* Cart Icon Styling */
.cart-icon {
    position: relative;
}

/* Cart Item Count Badge */
.cart-count {
    position: absolute;
    top: -10px;
    right: -14px;
    background: #ff4d4d;
    color: white;
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: bold;
}
</style>
<script>
    const toggleBtn = document.getElementById("profileToggle");
    const dropdown = document.querySelector(".dropdown");

    toggleBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        dropdown.classList.toggle("show");
    });

    // Close dropdown if clicking outside
    window.addEventListener("click", function () {
        dropdown.classList.remove("show");
    });
</script>
</body>
</html>