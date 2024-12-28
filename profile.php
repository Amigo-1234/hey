<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_profiles";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Profile Fetch
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = intval($_GET['id']);
    $sql = "SELECT name, email, username, profile_pic FROM profiles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
    exit;
}

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['id']);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Handle image upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . time() . "_" . $file_name;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
    } else {
        $target_file = null;
    }

    $sql = "UPDATE profiles SET name = ?, email = ?, username = ?";
    if ($target_file) {
        $sql .= ", profile_pic = ?";
    }
    $sql .= " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);

    if ($target_file) {
        $stmt->bind_param("ssssi", $name, $email, $username, $target_file, $user_id);
    } else {
        $stmt->bind_param("sssi", $name, $email, $username, $user_id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Update failed"]);
    }
    exit;
}
?>
