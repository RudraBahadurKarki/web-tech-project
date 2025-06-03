<?php
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    try {
        $stmt->execute();
        $message = "Registered successfully. <a href='login.php'>Login here</a>";
    } catch (mysqli_sql_exception $e) {
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            $message = "This email is already registered. <a href='login.php'>Login instead</a>.";
        } else {
            $message = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
<h2>Register</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Submit</button>
    <button type="reset">Reset</button>
</form>
<p class="message"><?= $message ?></p>
<a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>