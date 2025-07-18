<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit();
}

$error = '';
$success = '';

if ($_POST) {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($username) || empty($email) || empty($password)) {
    $error = 'Please fill in all fields.';
  } elseif (strlen($username) < 3) {
    $error = 'Username must be at least 3 characters long.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Invalid email format.';
  } elseif (strlen($password) < 6) {
    $error = 'Password must be at least 6 characters long.';
  } else {
    $conn = getConnection();

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0){
      $error = 'Username or email already exist.';
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashed_password);

      if ($stmt->execute()) {
        $success = 'Registration succesfull !, you can login.';
      } else {
        $error = 'Registration failed, please try again.';
      }
    }
    $stmt->close();
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Summer Time</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <style>
        body {
            background: url('assets/img/bg-masthead.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Nunito', sans-serif;
        }
        .register-container {
            max-width: 400px;
            margin: 100px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffd903;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .btn-register {
            width: 100%;
            padding: 10px;
            background-color: #ffd903;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-register:hover {
            background-color: #ffd903;
        }
        .text-center {
            text-align: center;
            margin-top: 15px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Create Your Summer Account</h2>
        <?php if ($error): ?>
          <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
          <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form action="#" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-register">Join the Vibes</button>
        </form>
        <div class="text-center">
            Already have an account? <a href="login.php" style="color:#ffd903; text-decoration:none;">Login here</a>
        </div>
    </div>
</body>
</html>