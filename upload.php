<?php
if (isset($_FILES['profilePic'])) {
    $errors = array();
    $file_name = $_FILES['profilePic']['name'];
    $file_size = $_FILES['profilePic']['size'];
    $file_tmp = $_FILES['profilePic']['tmp_name'];
    $file_type = $_FILES['profilePic']['type'];
    
    // Get the file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Allowed file types (extensions)
    $allowed_ext = array("jpg", "jpeg", "png", "gif");
    
    // Validate the file type
    if (!in_array($file_ext, $allowed_ext)) {
        $errors[] = "Invalid file type. Only JPG, JPEG, PNG, GIF files are allowed.";
    }
    
    // Validate the file size (5MB max)
    if ($file_size > 5242880) {
        $errors[] = "File size exceeds the 5MB limit.";
    }
    
    // If no errors, proceed with uploading
    if (empty($errors)) {
        $upload_dir = "uploads/";
        $file_path = $upload_dir . basename($file_name);
        
        // Move the uploaded file to the "uploads" directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Update localStorage in the frontend (optional, done by JS after upload success)
            echo "File uploaded successfully.";
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Create uploads directory if it doesn't exist
}
if (move_uploaded_file($file_tmp, $file_path)) {
    echo "File uploaded successfully.";
} else {
    echo "Failed to upload the file.";
}

?>
