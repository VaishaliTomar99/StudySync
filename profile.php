<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";

// UPDATE PROFILE
if(isset($_POST['update'])){

    $name =
    mysqli_real_escape_string($conn,
    $_POST['name']);

    // UPDATE NAME
    mysqli_query($conn,
    "UPDATE users
     SET name='$name'
     WHERE id='$user_id'");

    // CHECK IMAGE
    if(isset($_FILES['avatar']) &&
       $_FILES['avatar']['name'] != ""){

        $fileName =
        $_FILES['avatar']['name'];

        $fileTmp =
        $_FILES['avatar']['tmp_name'];

        $fileExtension =
        strtolower(pathinfo($fileName,
        PATHINFO_EXTENSION));

        // ALLOWED TYPES
        $allowed =
        ['jpg','jpeg','png'];

        if(in_array($fileExtension,
           $allowed)){

            // CLEAN FILE NAME
            $newFileName =
            time() . "." .
            $fileExtension;

            // ABSOLUTE PATH
            $uploadDir =
            $_SERVER['DOCUMENT_ROOT'] .
            "/studyplanner/uploads/avatars/";

            // CREATE FOLDER IF NOT EXISTS
            if(!is_dir($uploadDir)){
                mkdir($uploadDir,
                0777, true);
            }

            $uploadPath =
            $uploadDir .
            $newFileName;

            // MOVE FILE
            if(move_uploaded_file(
               $fileTmp,
               $uploadPath)){

                $avatarPath = "uploads/avatars/" . $newFileName;
                mysqli_query($conn,
                "UPDATE users
                SET avatar='$avatarPath'
                WHERE id='$user_id'");

                $message =
                "Profile Updated Successfully!";
                $_SESSION['avatar'] = $avatarPath;
                $_SESSION['user_name'] = $name;

            }else{

                $message =
                "Upload Failed: " .
                error_get_last()['message'];

            }

        }else{

            $message =
            "Only JPG, JPEG, PNG allowed.";

        }

    }else{

        $message =
        "Profile Updated Successfully!";

    }

}

// FETCH USER DATA
$user =
mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM users
 WHERE id='$user_id'")
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<title>Profile Settings</title>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,
initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{
    background:
    linear-gradient(
    135deg,
    #0f172a,
    #1e3a5f);

    min-height:100vh;
    padding:40px 0;
}

.profile-card{
    background:white;
    border-radius:25px;
    padding:40px;
    box-shadow:
    0 15px 40px
    rgba(0,0,0,0.2);
}

.profile-title{
    font-size:40px;
    font-weight:700;
    color:#1f2937;
}

.avatar-image{
    width:160px;
    height:160px;
    border-radius:50%;
    object-fit:cover;
    border:6px solid #6a5af9;
    box-shadow:
    0 10px 30px
    rgba(0,0,0,0.2);
}

.update-btn{
    background:
    linear-gradient(
    135deg,
    #6a5af9,
    #8b5cf6);

    color:white;
    border:none;
    padding:12px 30px;
    border-radius:12px;
    font-weight:600;
}

.update-btn:hover{
    opacity:0.9;
}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="profile-card">

<h2 class="profile-title mb-4">
👤 Profile Settings
</h2>

<?php if($message != ""){ ?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php } ?>

<div class="text-center mb-4">

<img src="<?php echo !empty($user['avatar']) ? $user['avatar'] : 'uploads/avatars/default.png'; ?>"
class="avatar-image">

</div>

<form method="POST"
enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">
Full Name
</label>

<input type="text"
name="name"
value="<?php echo $user['name']; ?>"
class="form-control">

</div>

<div class="mb-4">

<label class="form-label">
Upload Avatar
</label>

<input type="file"
name="avatar"
class="form-control">

</div>

<button type="submit"
name="update"
class="update-btn">

Update Profile

</button>

<a href="dashboard.php"
class="btn btn-dark ms-2">

Back Dashboard

</a>

</form>

</div>

</div>

</div>

</div>

</body>
</html>