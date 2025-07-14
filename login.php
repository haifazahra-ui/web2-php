<?php
require_once 'config.php';

if(isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    $error = 'Please fill in both fields.';
  } else {
    $conn = getConnection();

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();

      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit();
      } else {
        $error = 'Invalid username or password.';
      }
    } else {
      $error = 'Invalid username or password.';
    }

    $stmt->close();
    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Summer Time</title>
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
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .login-container h2 {
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
        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #ffd903;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: #e6c500;
        }
        .text-center {
            text-align: center;
            margin-top: 15px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome Back to Summer</h2>
        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif;?>
        <form action="#" method="post">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-login">Login & Chill</button>
        </form>
        <div class="text-center">
            Don’t have an account? <a href="register.php" style="color:#ffd903; text-decoration:none;">Register here</a>
        </div>
    </div>
</body>
</html>
