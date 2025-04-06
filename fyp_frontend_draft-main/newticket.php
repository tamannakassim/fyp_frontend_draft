<?php
session_start();

// Include your configuration file
@include 'config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    
    $categoryname = $conn->real_escape_string($_POST['CategoryName']);
    $comments = $conn->real_escape_string($_POST['Summary']);
    
    if (isset($_SESSION['user_id'])) {
      $userID = $_SESSION['user_id'];  // Assuming the UserID is stored in the session
  } else {
      // If no session user ID is found, redirect to login or show an error
      die("User not logged in.");
  }

  // Fetch CategoryID from ticketcategories table based on selected category name
// Fetch CategoryID from ticketcategories table based on selected category name
$categoryQuery = "SELECT CategoryID FROM ticketcategories WHERE CategoryName = ?";
$stmt = $conn->prepare($categoryQuery);
$stmt->bind_param('s', $categoryname); // Bind the selected category name

// Debugging: Check if category name is being passed correctly
echo "Category Name: " . $categoryname . "<br>"; // This will show the selected category on the page

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch CategoryID from the result
    $categoryData = $result->fetch_assoc();
    $categoryID = $categoryData['CategoryID'];
    echo "Category ID: " . $categoryID . "<br>"; // Debugging: Show the CategoryID
} else {
    // If no matching category is found, display an error
    echo "No matching category found in the database.<br>";  // Debugging: Message when no category is found
    die("Invalid category selected.");
}


    // File upload handling
    $attachmentPath = NULL;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $fileTmpPath = $_FILES['attachment']['tmp_name'];
        $fileName = $_FILES['attachment']['name'];
        $fileSize = $_FILES['attachment']['size'];
        $fileType = $_FILES['attachment']['type'];
        
        // Specify the directory where the file will be saved
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . basename($fileName);
        
        // Move the file to the upload directory
        if (move_uploaded_file($fileTmpPath, $filePath)) {
            $attachmentPath = $filePath; // Store the file path
        } else {
            echo "There was an error uploading the file.";
        }
    }
    
    // Insert ticket data into the database
    $sql = "INSERT INTO tickets (UserID, CategoryID, Summary, AttachmentPath) 
            VALUES ('$userID', '$categoryID', '$comments', '$attachmentPath')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Ticket submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ticket Submission</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    *{
      padding: 0;
      margin: 0;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="border border-dark p-4 rounded shadow bg-white" style="max-width: 500px; width: 100%;">
      <h2 class="text-center mb-4">Submit a Ticket</h2>
      <form action="newticket.php" method="POST" enctype="multipart/form-data">
      <form>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" class="form-control input" required>
          </div>
  
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" class="form-control input" required>
          </div>
        <div class="mb-3">
          <label for="CategoryName" class="form-label">Ticket Category</label>

          <select name="CategoryName" id="category" class="form-select input" required>
            <option value="">Select a category</option>
            <option value="Wifi Issues">Wifi Issues</option>
            <option value="Srudent Hub Request">Student Hub Request</option>
            <option value="Reset Student Email">Reset Student Email</option>
            <option value="LMS Moodle Issue">LMS Moodle Issue</option>
            <option value="Other IT Related Issues">Other IT Related Issues</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="Summary" class="form-label">Comments</label>
          <textarea name="Summary" id="comments" class="form-control input" rows="4" required></textarea>
        </div>

        <div class="mb-3">
          <label for="attachment" class="form-label">Attach File (Optional)</label>
          <input type="file" id="attachment" name="attachment" class="form-control input">
        </div>


        <input type="submit" class="btn btn-primary" value="Submit">
        
      </form>
    </div>
  </div>

  <!-- Bootstrap JS (optional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
