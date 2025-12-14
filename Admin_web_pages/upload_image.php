<?php require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

// Directory to store uploaded images
$targetDir = "../images_frontpage/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $imageFile = $_FILES['image'];
    $fileName = basename($imageFile['name']);
    $targetFilePath = $targetDir . $fileName;

    // Validate file type
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($fileType, $allowedTypes)) {
        // Move uploaded file to target directory
        if (move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {
            // Prepare an SQL statement to save the file name to the database
            $insertSQL = "Insert INTO images (filename) VALUES ('" . $fileName . "')";
            $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());
            if($Result1)
            {
                echo "Image uploaded and filename saved successfully!";
            } else {
                echo "Database error: " . $stmt->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
} else {
    echo "No image file uploaded.";
}
?>