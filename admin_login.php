<?php
session_start();
include('db.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $admin = $result->fetch_assoc();

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $admin['username'];
    header("Location: admin.php");
    exit;
  } else {
    $error = "Invalid credentials";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login to your account</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body, html {
      margin: 0;
      padding: 0;
      height: 100vh;
      width: 100vw;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f0f0f0;
    }

    .main-container {
      position: relative;
      width: 100%;
      max-width: 1050px;
      height: 700px;
      background: url('assets/index_imgs/SimplyTaste.png') no-repeat center center;
      background-size: cover;
      border: 5px solid yellow;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding: 40px 100px 40px 40px; 
      box-shadow: 0 0 30px rgba(0,0,0,0.2);
    }

    .login-container {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 12px;
    width: 100%;
    max-width: 350px;
    box-shadow: 0 0 20px rgba(39, 38, 38, 0.15);
    transform: translateX(1px); /* Optional: adjust this to fine-tune position */
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .login-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      background-color:#43632f;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .login-container button:hover {
      background-color:#ffbd59;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 15px;
    }

    @media (max-width: 768px) {
      .main-container {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: auto;
        padding: 20px;
      }

      .login-container {
        margin-top: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="main-container">
    <div class="login-container">
      <h2>Login to your account</h2>

      <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>

      <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
