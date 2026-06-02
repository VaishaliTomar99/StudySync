<?php

session_start();

include 'includes/db.php';

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: dashboard.php");

        }else{
            $message = "Invalid Password!";
        }

    }else{
        $message = "User not found!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container">

    <div class="row justify-content-center align-items-center vh-100">

        <div class="col-md-5">

            <div class="card p-4">

                <h2 class="text-center mb-4">
                    Login
                </h2>

                <?php if($message != ""){ ?>

                    <div class="alert alert-danger">
                        <?php echo $message; ?>
                    </div>

                <?php } ?>

                <form method="POST">

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
                            name="login"
                            class="btn btn-custom w-100">

                        Login

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>