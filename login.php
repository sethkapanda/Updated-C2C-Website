<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_seller'] = $user['is_seller'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login · UbuntuBazaar</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* --- Reset body to full viewport center --- */
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at 20% 30%, #0b0f1a, #05080f);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 20px;
        }

        /* --- Glass-morphism card --- */
        .login-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 40px;
            padding: 48px 40px 40px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(16, 185, 129, 0.15) inset;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(16, 185, 129, 0.3) inset;
        }

        /* --- Logo / brand --- */
        .login-brand {
            text-align: center;
            margin-bottom: 36px;
        }
        .login-brand h1 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #10b981, #34d399, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        .login-brand p {
            color: rgba(255,255,255,0.4);
            font-size: 0.9rem;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        /* --- Form fields --- */
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
            box-sizing: border-box;
        }
        .form-group input:focus {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.06);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15), 0 0 30px rgba(16, 185, 129, 0.05);
        }
        .form-group input::placeholder {
            color: rgba(255,255,255,0.2);
        }

        /* --- Error message --- */
        .error-msg {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            padding: 12px 16px;
            color: #fca5a5;
            font-size: 0.9rem;
            margin-bottom: 24px;
            text-align: center;
        }

        /* --- Button --- */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            border-radius: 40px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #34d399, #059669);
        }
        .btn-login:active {
            transform: scale(0.98);
        }

        /* --- Register link --- */
        .register-link {
            text-align: center;
            margin-top: 28px;
            color: rgba(255,255,255,0.35);
            font-size: 0.9rem;
        }
        .register-link a {
            color: #10b981;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            border-bottom: 1px solid transparent;
        }
        .register-link a:hover {
            color: #34d399;
            border-bottom-color: #34d399;
        }

        /* --- Responsive --- */
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px 28px;
                border-radius: 28px;
            }
            .login-brand h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-brand">
        <h1>UbuntuBazaar</h1>
        <p>welcome back · sign in</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login">→ Sign In</button>
    </form>

    <div class="register-link">
        Don’t have an account? <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>
