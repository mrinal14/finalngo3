<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>

<h2>Upload Text or Image</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <div>
        <label for="text_content">Text Content:</label><br>
        <textarea id="text_content" name="text_content" rows="4" cols="50"></textarea>
    </div>
    <div>
        <label for="fileToUpload">Select File to Upload:</label><br>
        <input type="file" name="fileToUpload" id="fileToUpload">
    </div>
    <div>
        <input type="submit" value="Upload" name="submit">
    </div>
</form>

<h2>Delete Uploaded File</h2>
<form action="upload.php" method="post">
    <div>
        <label for="filenameToDelete">Enter Filename to Delete:</label><br>
        <input type="text" name="filenameToDelete">
    </div>
    <div>
        <input type="submit" value="Delete" name="delete">
    </div>
</form>

<?php
// Handling file upload
if(isset($_POST["submit"])) {
    $text_content = $_POST['text_content'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "txt" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";

            // Append the content to blog.html
            $blog_content = "<div class='post'><p>" . htmlspecialchars($text_content) . "</p>";
            $blog_content .= "<img src='" . htmlspecialchars($target_file) . "' alt='Uploaded Image'></div>";

            file_put_contents('blog.html', $blog_content, FILE_APPEND | LOCK_EX);

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Handling file deletion
if(isset($_POST["delete"])) {
    $filenameToDelete = $_POST["filenameToDelete"];
    $filepath = "uploads/" . $filenameToDelete;
    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            echo "File " . $filenameToDelete . " has been deleted.";
        } else {
            echo "Error deleting " . $filenameToDelete;
        }
    } else {
        echo "File " . $filenameToDelete . " does not exist.";
    }
}
?>

</body>
</html>
