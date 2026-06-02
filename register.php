<?php

include 'includes/db.php';

$message = "";

if(isset($_POST['register'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($result) > 0){

        $message = "Email already exists!";

    }else{

        $insertQuery = "INSERT INTO users(name,email,password)
                        VALUES('$name','$email','$hashedPassword')";

        if(mysqli_query($conn, $insertQuery)){
            $message = "Registration Successful!";
        }else{
            $message = "Something went wrong!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container">

    <div class="row justify-content-center align-items-center vh-100">

        <div class="col-md-5">

            <div class="card p-4">

                <h2 class="text-center mb-4">
                    Create Account
                </h2>

                <?php if($message != ""){ ?>

                    <div class="alert alert-info">
                        <?php echo $message; ?>
                    </div>

                <?php } ?>

                <form method="POST">

                    <input type="text"
                           name="name"
                           class="form-control mb-3"
                           placeholder="Enter Name"
                           required>

                    <input type="email"
                           name="email"
                           class="form-control mb-3"
                           placeholder="Enter Email"
                           required>

                    <input type="password"
                           name="password"
                           class="form-control mb-3"
                           placeholder="Enter Password"
                           required>

                    <button type="submit"
                            name="register"
                            class="btn btn-custom w-100">

                        Register

                    </button>

                </form>

                <p class="mt-3 text-center">

                    Already have account?

                    <a href="login.php">
                        Login
                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

</body>
</html>