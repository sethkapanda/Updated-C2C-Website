<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $is_seller = isset($_POST['is_seller']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO users (email, password, fullname, phone, location, is_seller) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$email, $password, $fullname, $phone, $location, $is_seller]);
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register · UbuntuBazaar</title>
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
        .register-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 40px;
            padding: 48px 40px 40px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(16, 185, 129, 0.15) inset;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .register-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(16, 185, 129, 0.3) inset;
        }

        /* --- Brand --- */
        .register-brand {
            text-align: center;
            margin-bottom: 32px;
        }
        .register-brand h1 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #10b981, #34d399, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        .register-brand p {
            color: rgba(255,255,255,0.4);
            font-size: 0.9rem;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        /* --- Form fields --- */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            margin-bottom: 6px;
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

        /* --- Checkbox (seller toggle) --- */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 8px 0 16px;
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #10b981;
            cursor: pointer;
            border-radius: 6px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s;
        }
        .checkbox-group input[type="checkbox"]:checked {
            background: #10b981;
            border-color: #10b981;
        }
        .checkbox-group label {
            cursor: pointer;
            user-select: none;
        }

        /* --- Button --- */
        .btn-register {
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
        }
        .btn-register:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #34d399, #059669);
        }
        .btn-register:active {
            transform: scale(0.98);
        }

        /* --- Login link --- */
        .login-link {
            text-align: center;
            margin-top: 24px;
            color: rgba(255,255,255,0.35);
            font-size: 0.9rem;
        }
        .login-link a {
            color: #10b981;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            border-bottom: 1px solid transparent;
        }
        .login-link a:hover {
            color: #34d399;
            border-bottom-color: #34d399;
        }

        /* --- Responsive --- */
        @media (max-width: 480px) {
            .register-card {
                padding: 32px 24px 28px;
                border-radius: 28px;
            }
            .register-brand h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="register-brand">
        <h1>UbuntuBazaar</h1>
        <p>join the community · create account</p>
    </div>

    <form method="POST">
        <div class="form-group">
            <label>Full name</label>
            <input type="text" name="fullname" placeholder="e.g. Thabo Mokoena" required>
        </div>
        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" placeholder="071 234 5678">
        </div>
        <div class="form-group">
            <label>Location (town/city)</label>
            <input type="text" name="location" placeholder="e.g. Soweto, Durban" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" name="is_seller" id="is_seller">
            <label for="is_seller">I want to sell products</label>
        </div>

        <button type="submit" class="btn-register">→ Create Account</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Sign in</a>
    </div>
</div>

</body>
</html>
