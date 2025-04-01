<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } catch(PDOException $e) {
        $error = "Login failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Real Estate System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
        }
        .auth-box {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
            margin: 2rem auto;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-header i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        .auth-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .auth-header p {
            color: #666;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-floating input {
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-floating input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .btn-auth {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 0.8rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
        }
        .btn-auth:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }
        .auth-footer a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="auth-box">
                <div class="auth-header">
                    <i class="bi bi-house-door-fill"></i>
                    <h2>Welcome Back</h2>
                    <p>Please login to your account</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-auth">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 