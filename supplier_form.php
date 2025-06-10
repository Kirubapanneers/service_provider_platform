<?php
session_start();
require_once 'config.php';

// Ensure DB connection is successful
if ($conn->connect_error) {
    die("Connection failed. Please try again later.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and assign form inputs
    $companyName      = trim($_POST['companyName'] ?? '');
    $address          = trim($_POST['address'] ?? '');
    $services         = trim($_POST['services'] ?? '');
    $servicesOffered  = trim($_POST['servicesOffered'] ?? '');
    $availability     = trim($_POST['availability'] ?? '');
    $pricing          = floatval($_POST['pricing'] ?? 0);
    $warranty         = trim($_POST['warranty'] ?? '');
    $warrantyIncludes = trim($_POST['warrantyIncludes'] ?? '');
    $warrantyExcludes = trim($_POST['warrantyExcludes'] ?? '');
    $contactNumber    = trim($_POST['contactNumber'] ?? '');

    // Handle profile photo upload securely
    $uploadDir = 'uploads/';
    $profilePhoto = '';
    if (!empty($_FILES['profilePhoto']['name']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
        $safeName = uniqid('profile_', true) . '_' . basename($_FILES['profilePhoto']['name']);
        $targetFile = $uploadDir . $safeName;
        if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $targetFile)) {
            $profilePhoto = $targetFile;
        }
    }

    // Insert data using prepared statement
    $sql = "INSERT INTO suppliers (
                company_name, address, services, services_offered,
                availability, pricing, warranty, warranty_includes,
                warranty_excludes, contact_number, profile_photo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "sssssdsssss",
            $companyName, $address, $services, $servicesOffered,
            $availability, $pricing, $warranty,
            $warrantyIncludes, $warrantyExcludes,
            $contactNumber, $profilePhoto
        );

        if ($stmt->execute()) {
            // Store relevant info in session (safe for later display)
            $_SESSION['companyName']      = htmlspecialchars($companyName);
            $_SESSION['address']          = htmlspecialchars($address);
            $_SESSION['services']         = htmlspecialchars($services);
            $_SESSION['servicesOffered']  = htmlspecialchars($servicesOffered);
            $_SESSION['availability']     = htmlspecialchars($availability);
            $_SESSION['pricing']          = $pricing;
            $_SESSION['warranty']         = htmlspecialchars($warranty);
            $_SESSION['warrantyIncludes'] = htmlspecialchars($warrantyIncludes);
            $_SESSION['warrantyExcludes'] = htmlspecialchars($warrantyExcludes);
            $_SESSION['contactNumber']    = htmlspecialchars($contactNumber);
            $_SESSION['profilePhoto']     = htmlspecialchars($profilePhoto);

            header("Location: confirmation.php");
            exit();
        } else {
            error_log("DB Insert Error: " . $stmt->error);
            echo "<script>alert('Something went wrong. Please try again.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        error_log("Statement Prepare Error: " . $conn->error);
        echo "<script>alert('Unable to process the request.'); window.history.back();</script>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background: url('homm.jpg') no-repeat;
     background-size: cover;
     background-position-y: center;
        }
        header {
            background-color:#243d45;
           margin-bottom:30px;
            padding: 15px;
            text-align: center;
        }
       header h1 {
            color: white;
        }
        .container {
      
            width: 50%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="number"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    color: #333;
    appearance: none; /* Removes default arrow */
    cursor: pointer;
    transition: border-color 0.3s ease;
}

/* Hover effect for select */


/* Focus effect for select */


/* Style for the placeholder (first option) */
select option:first-child {
    color: #999;
}
        button {
            width: 100%;
            padding: 10px;
            background-color:#243d45;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #335966;
        }
    </style>
</head>
<body>
<header>
        <h1>e-Fixx</h1>
    </header>
    <div class="container">
        <h1>Supplier Registration Form</h1>
        <form action="supplier_form.php" method="POST" enctype="multipart/form-data">
            <label for="companyName">Company Name</label>
            <input type="text" name="companyName" required>

            <label for="address">Address</label>
            <textarea name="address" rows="3" required></textarea>
            
            
            <label for="serviceType">Service Type</label>
            <select id="serviceType" name="services" required>
                <option value="Plumber">Plumber</option>
                <option value="Electrician">Electrician</option>
                <option value="Carpenter">Carpenter</option>
            </select>

            <label for="servicesOffered">Specific Services Offered</label>
            <textarea name="servicesOffered" rows="3" required></textarea>

            <label for="availability">Availability</label>
            <input type="text" name="availability" required>

            <label for="pricing">Pricing (₹ per hour)</label>
            <input type="number" name="pricing" required>

            <label for="warranty">Warranty (in years)</label>
            <input type="number" name="warranty" required>

            <label for="warrantyIncludes">Warranty Includes</label>
            <textarea name="warrantyIncludes" rows="3" required></textarea>

            <label for="warrantyExcludes">Warranty Excludes</label>
            <textarea name="warrantyExcludes" rows="3" required></textarea>

            <label for="contactNumber">Contact Number</label>
            <input type="text" name="contactNumber" required>

            <label for="profilePhoto">Upload Profile Photo</label>
            <input type="file" name="profilePhoto" accept="image/*" required>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
