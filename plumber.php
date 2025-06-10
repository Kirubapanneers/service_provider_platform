<?php
session_start();

require_once 'config.php'; // Ensure this file is added to .gitignore

if ($conn->connect_error) {
    die("Connection error. Please try again later.");
}

// Default query
$sql = "SELECT * FROM suppliers WHERE services = ?";
$params = ["plumber"];
$types = "s";

// Filter inputs
$filterAvailability = $_POST['availability'] ?? '';
$searchTerm = $_POST['search'] ?? '';

if (!empty($filterAvailability)) {
    $sql .= " AND availability = ?";
    $params[] = $filterAvailability;
    $types .= "s";
}

if (!empty($searchTerm)) {
    $sql .= " AND (company_name LIKE ? OR services_offered LIKE ?)";
    $searchWildcard = "%" . $searchTerm . "%";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "ss";
}

// Prepare and execute statement
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Query preparation failed.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plumber Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        footer { background-color: #243d45; color: white; padding: 10px; text-align: center; }
header{
          
    background-color: #243d45;
           
    font-weight: bold;
    text-align: center;
   
    padding: 6px 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    flex-wrap: wrap;
    height: 70px;
    width: 100%;
           
          
          
}
header h1{
    color: #FFFFFF;
}

   /*search*/
       /* Main container */
       
      .services-container{
        margin:60px;
        padding:30px;
        border:1px solid #ccc; 
        background: #ebf3f4;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        background: url('loginback.jpg') no-repeat;
     background-size: cover;
     background-position-x: center;
      } 

.service-list {
    margin-top: 20px;
}

.service-item {
    padding: 10px;
    border: 1px solid #243d45 ;
    border-radius: 4px;
    margin-bottom: 10px;
    background-color: #f1f1f1;
}



        .service-list { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 50px; 
        }
        
        .service-block { 
            background-color: #fff; 
            border: 1px solid #243d45 ;
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
            padding: 15px; 
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s; 
          
        }

        .service-block:hover { 
            transform: scale(1.05); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
        }

        .service-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 40%;
            margin-bottom: 10px;
            border: 1px solid #243d45 ;
        }

        h3 { 
            color:#243d45; 
            font-size: 1.1em; 
            text-align: center;
            font-weight: bold;
            font-size: 1.4rem;
        }

        /* Side Slider Overlay Styles */
        .slider-overlay {
            position: fixed;
            right: -100%;
            top: 0;
            width: 80%;
            max-width: 500px;
            height: 100%;
            background-color: #ebf3f4;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            padding: 20px;
            transition: right 0.4s ease;
            z-index: 100;
        }

        .slider-overlay.show {
            right: 0;
        }

        .slider-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-btn {
            font-size: 1.5em;
            cursor: pointer;
            color: #0073e6;
        }

        .slider-content img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 10px 0;
        }

        .book-now-btn {
            display: inline-block;
            background-color: #335966;
            color: white;
            text-align: center;
            padding: 8px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 12px;
            transition: background-color 0.3s ease;
        }

        .book-now-btn:hover { background-color:#243d45 ; }

        /*photo slider */
        
/* Main container for layout */
/* Main container styling */
.server-container {
    width: 100%;
  
  margin:auto;
    padding: 20px;
    background: #ebf3f4;
    height: 430px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Header styling */
h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Search bar styling */
.search-bar-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    border-radius: 50%;
    border:none;
   
}

/* Search input styling */
.search-bar-container input[type="text"] {
    width: 50%;
    padding: 10px 15px;
    font-size: 16px;
    border:1px solid #ccc;
    border-radius: 100px;
   
}
.search-bar-container input[type="text"]:hover{
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
}

/* Buttons styling */
.search-bar-container button {
    padding: 10px 15px;
    margin-left: 10px;
    background-color:#228896 ;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}



.search-bar-container button:hover {
    background-color:#335966;
}

   
/* Main container for text and carousel */
.main-container {
    display: flex;
    justify-content: space-around;
    align-items: center;
    gap: 0px;
}

/* Left side text block */
.plumber-text {
    flex: 2;
    margin-top: 0;
    color: black;
}
.plumber-text h1{
    color: #464646;
    font-size: 26px;
    font-weight: bold;
}
.plumber-text p{
    color: #464646;
    text-align: center;
}

/* Right side carousel */
.carousel-container {
    flex: 2;
    max-width: 500px;
}

.carousel-inner img {
    width: 80%;
    height: 260px;
    border-radius: 15px;
   
}

/*footer*/
.footer {
    background-color: #2c2c2c; /* Dark background for contrast */
    color: #ffffff; /* White text for readability */
    padding: 40px 20px 20px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    width: 100%;
    flex-shrink: 0;

}


.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    gap: 20px;
}


.footer-section {
    flex: 1 1 200px;
    min-width: 200px;
}

.footer-section h3 {
    font-size: 1.5em;
    margin-bottom: 15px;
    color: #ffffff;
}

.footer-section p,
.footer-section ul,
.footer-section li {
    font-size: 0.95em;
    line-height: 1.6;
    color: #dddddd;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    color: #dddddd;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-section ul li a:hover {
    color: #1e90ff; /* Accent color on hover */
}

.social-icons {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.social-icons a {
    color: #dddddd;
    font-size: 1.2em;
    transition: color 0.3s;
}

.social-icons a:hover {
    color: #243d45; /* Accent color on hover */
}

/* Footer Bottom */
.footer-bottom {
    text-align: center;
    border-top: 1px solid #444444;
    padding-top: 20px;
    margin-top: 20px;
    font-size: 0.9em;
    color: #aaaaaa;
}

/* Back to Top Button */
#back-to-top {
    position: fixed;
    bottom: 40px;
    right: 40px;
    width: 50px;
    height: 50px;
    background-color: #243d45;
    color: #ffffff;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2em;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
    z-index: 1000;
}

#back-to-top.show {
    opacity: 1;
    visibility: visible;
}

#back-to-top:hover {
    background-color: #243d45;
}








/* Newsletter Subscription */
.footer-section.newsletter form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 15px;
}

.footer-section.newsletter input[type="email"] {
    padding: 10px;
    border: none;
    border-radius: 4px;
    font-size: 1em;
}

.footer-section.newsletter button {
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #243d45;
    color: #ffffff;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s;
}

.footer-section.newsletter button:hover {
    background-color: #2c5f70;
}

/* Adjustments for Responsive Design */
@media (max-width: 768px) {
    .footer-section.newsletter form {
        flex-direction: column;
        align-items: center;
    }

    .footer-section.newsletter input[type="email"],
    .footer-section.newsletter button {
        width: 100%;
    }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .steps-container, .why-use-container, .footer-container {
        flex-direction: column;
        align-items: center;
    }
}



    </style>
  
</head>
<body>
    <header>
        <h1>e-Fixx</h1>
    </header>

    
    <div class="server-container">
    <!-- Title -->
    <h1>Search for Services</h1>

    <!-- Search bar -->
  <!-- Search bar -->
<div class="search-bar-container">

    <input type="text" id="search-input" placeholder="&#xf002; 
 Search by Company Name, Location, or Services" />
 
    <button id="search-button">Search</button>
    <button id="clear-button">Clear</button>
</div>

    <div class="main-container">
        <!-- Left side text block -->
        <div class="plumber-text">
            <h1>Best Plumbing Services</h1>
            <h1> in Tamilnadu</h1>
            <p>
            ✔ 6.2M bookings near you
| ★

4.9 (45k reviews)</p>

        </div>

        <!-- Right side photo slider -->
        <div class="carousel-container">
            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="10000">
                        <img src="plum.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <img src="pluu.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="plu.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>

    
   
   
  

    <div class="services-container">
   
        <div class="service-list" id="service-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div id='data-" . htmlspecialchars($row['id']) . "' class='service-block' onclick='toggleSlider(" . htmlspecialchars($row['id']) . ")' ";
                    echo "data-company-name='" . htmlspecialchars($row['company_name']) . "' ";
                    echo "data-address='" . htmlspecialchars($row['address']) . "' ";
                    echo "data-availability='" . htmlspecialchars($row['availability']) . "' ";
                    echo "data-pricing='" . htmlspecialchars($row['pricing']) . "' ";
                    echo "data-services='" . htmlspecialchars($row['services_offered']) . "' ";
                    echo "data-warranty='" . htmlspecialchars($row['warranty']) . "' ";
                    echo "data-warranty-includes='" . htmlspecialchars($row['warranty_includes']) . "' ";
                    echo "data-warranty-excludes='" . htmlspecialchars($row['warranty_excludes']) . "' ";
                    echo "data-contact-number='" . htmlspecialchars($row['contact_number']) . "' ";
                    echo "data-profile-photo='" . htmlspecialchars($row['profile_photo']) . "'>";
                    
                    echo "<img src='" . htmlspecialchars($row['profile_photo']) . "' alt='Profile Photo' class='service-photo'>";
                    echo "<h3>" . htmlspecialchars($row['company_name']) . "</h3>";
                    echo "<p><strong>Location:</strong> " . htmlspecialchars($row['address']) . "</p>";
                    echo "<p><strong>Availability:</strong> " . htmlspecialchars($row['availability']) . "</p>";
                    echo "<p><strong>Pricing:</strong> ₹" . htmlspecialchars($row['pricing']) . "/hour</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No plumber services available currently.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Side Slider -->
    <div id="slider" class="slider-overlay">
        <div class="slider-header">
            <h3 id="slider-company-name"></h3>
            <span class="close-btn" onclick="closeSlider()">&times;</span>
        </div>
        <div class="slider-content">
            <img id="slider-photo" alt="Profile Photo">
            <p><strong>Location:</strong> <span id="slider-location"></span></p>
            <p><strong>Availability:</strong> <span id="slider-availability"></span></p>
            <p><strong>Pricing:</strong> <span id="slider-pricing"></span></p>
            <p><strong>Services Offered:</strong> <span id="slider-services"></span></p>
            <p><strong>Warranty:</strong> <span id="slider-warranty"></span></p>
            <p><strong>Warranty Includes:</strong> <span id="slider-includes"></span></p>
            <p><strong>Warranty Excludes:</strong> <span id="slider-excludes"></span></p>
            <p><strong>Contact Number:</strong> <span id="slider-contact"></span></p>
            <a href="booknow.html" class="book-now-btn" id="bookNowButton">Book Now</a>
        </div>
    </div>
    
  <!--<footer>
        <p>Contact us at <a href="mailto:support@example.com">support@example.com</a></p>
    </footer>---> 



    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <!-- About Us -->
            <div class="footer-section about">
                <h3>About Us</h3>
                <p>
                    We are a premier platform dedicated to connecting clients with top-tier service providers. Our mission is to ensure quality, reliability, and customer satisfaction in every service we offer.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#booking-steps">How to Book</a></li>
                    <li><a href="#why-use">Why Choose Us</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <!-- Contact Information -->
            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p><strong>Address:</strong> 1234 Main Street, Anytown, USA</p>
                <p><strong>Phone:</strong> +1 (234) 567-8901</p>
                <p><strong>Email:</strong> support@yourplatform.com</p>
            </div>
            
            <!-- Social Media -->
            <div class="footer-section social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <!-- Newsletter Subscription -->
<div class="footer-section newsletter">
    <h3>Subscribe to Our Newsletter</h3>
    <form action="#" method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Subscribe</button>
    </form>
</div>

        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>&copy; 2024 YourPlatform. All Rights Reserved.</p>
        </div>
    </footer>


    <!-- Back to Top Button (Optional) -->
    <button id="back-to-top" title="Back to Top"><i class="fas fa-arrow-up"></i></button>
    <script>
        function toggleSlider(id) {
            var slider = document.getElementById("slider");
            var data = document.getElementById("data-" + id).dataset;
            
            document.getElementById("slider-company-name").innerText = data.companyName;
            document.getElementById("slider-location").innerText = data.address;
            document.getElementById("slider-availability").innerText = data.availability;
            document.getElementById("slider-pricing").innerText = "₹" + data.pricing + "/hour";
            document.getElementById("slider-services").innerText = data.services;
            document.getElementById("slider-warranty").innerText = data.warranty;
            document.getElementById("slider-includes").innerText = data.warrantyIncludes;
            document.getElementById("slider-excludes").innerText = data.warrantyExcludes;
            document.getElementById("slider-contact").innerText = data.contactNumber;
            document.getElementById("slider-photo").src = data.profilePhoto;

            slider.classList.add("show");
        }

        function closeSlider() {
            document.getElementById("slider").classList.remove("show");
        }
        

  
        document.getElementById('search-button').addEventListener('click', function() {
    const query = document.getElementById('search-input').value.toLowerCase();

    const serviceBlocks = document.querySelectorAll('.service-block');
    serviceBlocks.forEach(block => {
        const companyData = block.dataset.companyName.toLowerCase();
        const locationData = block.dataset.address.toLowerCase();
        const servicesData = block.dataset.services.toLowerCase();

        // Check if the service block matches the search query in any of the data fields
        const matchesCompany = companyData.includes(query);
        const matchesLocation = locationData.includes(query);
        const matchesServices = servicesData.includes(query);

        // Show the block if it matches the query in any of the fields
        if (matchesCompany || matchesLocation || matchesServices) {
            block.style.display = 'block'; // Show matching block
        } else {
            block.style.display = 'none'; // Hide non-matching block
        }
    });
});

// Clear search functionality
document.getElementById('clear-button').addEventListener('click', function() {
    document.getElementById('search-input').value = ''; // Clear the input field

    const serviceBlocks = document.querySelectorAll('.service-block');
    serviceBlocks.forEach(block => {
        block.style.display = 'block'; // Show all blocks
    });
});

//automatic label writing
// Array of placeholder texts to cycle through
const placeholders = ["Company Name", "Location", "Services"];
let placeholderIndex = 0;
let charIndex = 0;
let isDeleting = false;
const typingSpeed = 100; // Speed of typing effect (in ms)
const pauseDuration = 1500; // Pause before starting the next word

function typeEffect() {
    const searchInput = document.getElementById('search-input');

    // Get the current placeholder text
    const currentText = placeholders[placeholderIndex];
    
    // Adjust placeholder text based on typing or deleting
    if (isDeleting) {
        searchInput.setAttribute("placeholder", "Search by " + currentText.substring(0, charIndex--));
    } else {
        searchInput.setAttribute("placeholder", "Search by " + currentText.substring(0, charIndex++));
    }

    // Determine if we need to start deleting or move to the next placeholder
    if (!isDeleting && charIndex === currentText.length) {
        // Pause at the end of the word
        setTimeout(() => isDeleting = true, pauseDuration);
    } else if (isDeleting && charIndex === 0) {
        // Move to the next placeholder after deleting
        isDeleting = false;
        placeholderIndex = (placeholderIndex + 1) % placeholders.length;
    }

    // Schedule the next typing action
    const currentSpeed = isDeleting ? typingSpeed / 2 : typingSpeed; // Faster delete speed
    setTimeout(typeEffect, currentSpeed);
}

// Start the typing effect when the page loads
window.onload = typeEffect;


function toggleSlider(id) {
    var slider = document.getElementById("slider");
    var data = document.getElementById("data-" + id).dataset;
    
    document.getElementById("slider-company-name").innerText = data.companyName;
    document.getElementById("slider-location").innerText = data.address;
    document.getElementById("slider-availability").innerText = data.availability;
    document.getElementById("slider-pricing").innerText = "₹" + data.pricing + "/hour";
    document.getElementById("slider-services").innerText = data.services;
    document.getElementById("slider-warranty").innerText = data.warranty;
    document.getElementById("slider-includes").innerText = data.warrantyIncludes;
    document.getElementById("slider-excludes").innerText = data.warrantyExcludes;
    document.getElementById("slider-contact").innerText = data.contactNumber;
    document.getElementById("slider-photo").src = data.profilePhoto;

    slider.classList.add("show");
}

function closeSlider() {
    document.getElementById("slider").classList.remove("show");
}

document.getElementById("bookNowButton").addEventListener("click", function() {
        // Assuming you have a PHP session variable to track login status
        <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { ?>
            alert("Please login or register to proceed with booking.");
            window.location.href = "login.html"; // Redirect to login page
        <?php } else { ?>
            window.location.href = "transaction.html"; // Redirect to booking page if logged in
        <?php } ?>
    });
        
    </script>
    
<script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
