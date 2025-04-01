<?php
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Username or email already exists!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password, $full_name, $phone]);
                $success = "Registration successful! Please login.";
            }
        } catch(PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Real Estate System</title>
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
            padding: 2rem 0;
        }
        .auth-box {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            max-width: 500px;
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
        .password-requirements {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="auth-box">
                <div class="auth-header">
                    <i class="bi bi-person-plus-fill"></i>
                    <h2>Create Account</h2>
                    <p>Join our real estate community</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>

                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" required>
                        <label for="full_name">Full Name</label>
                    </div>

                    <div class="form-floating">
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                        <label for="phone">Phone Number</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                        <div class="password-requirements">
                            <i class="bi bi-info-circle"></i> Password must be at least 8 characters long
                        </div>
                    </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                        <label for="confirm_password">Confirm Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-auth">
                        <i class="bi bi-person-plus"></i> Register
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 