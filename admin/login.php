<?php

session_start();

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$admin_user = "admin";
$admin_pass = "admin123";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $admin_user && $password === $admin_pass) {

        session_regenerate_id(true);

        // Secure session
        $_SESSION['admin'] = true;
        $_SESSION['admin_name'] = $username;
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_time'] = time();

        header("Location: index.php");
        exit;
    }

    $error = "Invalid Username or Password!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Rajeshwari Beauty Store</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #fff7f9;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 42px 38px;
            border: 1px solid #f3dfe5;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(255, 63, 108, 0.10);
        }

        .logo {
            width: 72px;
            height: 72px;
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff3f6c;
        }

        .login-title {
            text-align: center;
            color: #282c3f;
            font-size: 27px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .login-subtitle {
            text-align: center;
            color: #8a8a8a;
            font-size: 13px;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            margin-bottom: 7px;
            color: #444;
            font-size: 13px;
            font-weight: 600;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 13px 44px 13px 14px;
            border: 1px solid #dddddd;
            border-radius: 9px;
            outline: none;
            background: #fff;
            color: #282c3f;
            font-size: 14px;
            transition: 0.25s;
        }

        .input-wrapper input:focus {
            border-color: #ff3f6c;
            box-shadow: 0 0 0 3px rgba(255, 63, 108, 0.08);
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #ff3f6c;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
        }

        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 4px 0 22px;
            font-size: 12px;
            color: #777;
        }

        .login-options label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .login-options input {
            accent-color: #ff3f6c;
        }

        .login-button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 9px;
            background: #ff3f6c;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s;
            box-shadow: 0 6px 15px rgba(255, 63, 108, 0.18);
        }

        .login-button:hover {
            background: #e7335c;
            transform: translateY(-1px);
        }

        .error {
            margin-top: 16px;
            padding: 10px 12px;
            background: #fff1f1;
            border: 1px solid #ffd7d7;
            border-radius: 8px;
            color: #d9534f;
            text-align: center;
            font-size: 13px;
        }

        .login-footer {
            margin-top: 24px;
            text-align: center;
            color: #aaa;
            font-size: 11px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 35px 22px;
                border-radius: 14px;
            }

            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="login-card">

    <img
        src="../logo3.jpg"
        alt="Rajeshwari Beauty Store"
        class="logo"
    >

    <h1 class="login-title">Admin Login</h1>

    <p class="login-subtitle">
        Sign in to manage your store
    </p>

    <form method="POST" autocomplete="off">

        <div class="input-group">
            <label for="username">Username</label>

            <div class="input-wrapper">
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    autocomplete="off"
                    required
                >
            </div>
        </div>

        <div class="input-group">
            <label for="password">Password</label>

            <div class="input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="new-password"
                    required
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="togglePassword()"
                >
                    Show
                </button>
            </div>
        </div>

        <div class="login-options">
            <label>
                <input type="checkbox">
                Remember me
            </label>
        </div>

        <button
            type="submit"
            name="login"
            class="login-button"
        >
            Sign In
        </button>

    </form>

    <?php if (isset($error)): ?>
        <p class="error">
            <?php echo $error; ?>
        </p>
    <?php endif; ?>

    <p class="login-footer">
        Rajeshwari Beauty Store • Admin Panel
    </p>

</div>

<script>
    function togglePassword() {
        const password = document.getElementById("password");
        const button = document.querySelector(".password-toggle");

        if (password.type === "password") {
            password.type = "text";
            button.textContent = "Hide";
        } else {
            password.type = "password";
            button.textContent = "Show";
        }
    }
</script>

</body>
</html>