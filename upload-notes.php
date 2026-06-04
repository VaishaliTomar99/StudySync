<?php

session_start();

include 'includes/db.php';

$message = "";

if(isset($_POST['upload'])){

    $file = $_FILES['note'];

    $fileName = time() . "_" . $file['name'];

    $tmpName = $file['tmp_name'];

    $fileSize = $file['size'];

    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['pdf','jpg','png','jpeg'];

    if(in_array($fileType, $allowed)){

        if($fileSize < 5000000){

            move_uploaded_file($tmpName,
            "uploads/" . $fileName);

            $message = "File Uploaded Successfully!";

        }else{

            $message = "File size too large!";

        }

    }else{

        $message = "Invalid file type!";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<title>Upload Notes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="tasks-section">

                <h2 class="mb-4">
                    📂 Upload Study Notes
                </h2>

                <?php if($message != ""){ ?>

                    <div class="alert alert-info">

                        <?php echo $message; ?>

                    </div>

                <?php } ?>

                <form method="POST"
                      enctype="multipart/form-data">

                    <input type="file"
                           name="note"
                           class="form-control mb-3"
                           required>

                    <button type="submit"
                            name="upload"
                            class="add-btn border-0">

                        Upload File

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>