<?php

session_start();

include '../includes/db.php';

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users
              WHERE email='$email'
              AND role='admin'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $admin = mysqli_fetch_assoc($result);

        if(password_verify($password,
           $admin['password'])){

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: admin-dashboard.php");

        }else{

            $message = "Invalid Password";

        }

    }else{

        $message = "Admin not found";

    }

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:linear-gradient(135deg,#667eea,#764ba2);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Poppins;
}

.login-card{
    background:white;
    padding:40px;
    border-radius:20px;
    width:400px;
}

</style>

</head>

<body>

<div class="login-card">

<h2 class="text-center mb-4">
Admin Login
</h2>

<?php if($message!=""){ ?>

<div class="alert alert-danger">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST">

<input type="email"
       name="email"
       class="form-control mb-3"
       placeholder="Admin Email"
       required>

<input type="password"
       name="password"
       class="form-control mb-3"
       placeholder="Password"
       required>

<button type="submit"
        name="login"
        class="btn btn-primary w-100">

Login

</button>

</form>

</div>

</body>
</html>