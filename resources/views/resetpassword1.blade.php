<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;

            /* HOTEL STYLE BACKGROUND */
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        }

        .login-card {
            width: 380px;
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .login-title {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #2c5364;
        }

        .input-group-text {
            background: #fff;
            border-right: none;
        }

        .form-control {
            border-left: none;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .toggle-password {
            cursor: pointer;
        }

        .btn-login {
            background: #2c5364;
            color: #fff;
            border-radius: 10px;
            padding: 10px;
        }

        .btn-login:hover {
            background: #203a43;
            color: white;
            font-weight: bolder;
        }

        /* RESPONSIVE */
        @media(max-width: 500px) {
            .login-card {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    
    <h4 class="login-title text-center">
        Hotel Reservation <br> Management System
    </h4>

    <p class="text-center text-muted mb-3" style="font-size: 14px;">
        Create New password to login
    </p>

    <form action="{{ route('password.update') }}" method="post">
       @csrf

     
      <input type="hidden" name="token" value="{{ $token }}">
       <input type="hidden" name="email" value="{{ $email }}">
       <div class="mb-2 input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password" name="password" id="password" class="form-control" placeholder="Create New password" required>
            <span class="input-group-text toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </span>
        </div>

        <div class="mb-2 input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password" name="password_confirmation" id="password" class="form-control" placeholder="Confirm password" required>
            <span class="input-group-text toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </span>
        </div>
        
        <!-- Button -->
        <button type="submit" class="btn btn-login w-100">
            Change password
        </button>
        
        @if(session("error"))
            <span style="color: red">{{ session("error") }}</span>
        @endif

    </form>

</div>
<script>
function togglePassword() {
    let password = document.getElementById("password");
    let icon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}
</script>


</body>
</html>