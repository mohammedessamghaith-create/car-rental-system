<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carsproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$message_type = "";

// Fetch offices for the dropdown
$sql = "SELECT OfficeID, OfficeName FROM offices";
$result = $conn->query($sql);
$offices = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $offices[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $model = trim($_POST["model"]);
    $year = trim($_POST["year"]);
    $plate = trim($_POST["plate"]);
    $color = trim($_POST["color"]);
    $status = trim($_POST["status"]);
    $office = trim($_POST["office"]);
     $carID = trim($_POST["CarID"]);
    $price = trim($_POST["Price"]);

    if (empty($model) || empty($year) || empty($plate) || empty($color) || empty($status) || empty($office) || empty($carID)||empty($price)) {
        $message = "Please fill all fields.";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO cars (CarID, Model, Year, PlateID, Color, Status, OfficeID, Price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssd", $carID, $model, $year, $plate, $color, $status, $office,$price);

        if ($stmt->execute()) {
            $message = "Car registered successfully!";
            $message_type = "success";
        } else {
            $message = "Error: " . $stmt->error;
            $message_type = "error";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Car</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f9fc;
            color: #2c3e50;
            line-height: 1.6;
            padding: 0 20px;
        }
        #background-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1; /* Place behind other content */
            object-fit: cover;
        }

        .navbar {
            background-color: #34495e;
            background-image: url(3.jpeg);          
            background-size: cover; 
            background-repeat: no-repeat;  color: #2c3e50;
            color: #ecf0f1;
            padding: 15px 0;
            display: flex; /* Enable flexbox */
            justify-content: space-between; /* Distribute space between elements */
            align-items: center; /* Center items vertically */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 70px;
            padding: 15px 30px; /* Add padding on both sides */
        }
        .navbar .logo-container {
            display: flex; /* جعل الشعار والعنوان بجانب بعضهما */
            align-items: center; /* توسيط العناصر عموديًا */

        }    

        .logo {
            height: 75px; 
            margin-right: 20px; 
        }
         
        .navbar h2 {
            margin: 0;
            font-size: 30px;
        }

        .navbar nav {
            display: flex;
        }

        .navbar nav a {
            color: #ecf0f1;
            font-weight: 600;
            margin: 0 15px;
            text-decoration: none;
            font-size: 19px;
        }

        .navbar nav a:hover {
            color: #1abc9c;
        }

        section {
            margin: 30px auto;
            max-width: 500px;
            border-style:solid;
            background-color: #ffffff;
            background-image: url(3.jpeg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 35px;
            text-align: center;
            margin-bottom: 20px;
            color:rgb(255, 255, 255);
        }

        form {
            display: flex;
            flex-direction: column;
            color:rgb(48, 83, 92);
            gap: 15px;
        }

        input, select {
            padding: 12px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 14px;
            color: #34495e;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #1abc9c;
            outline: none;
        }

        button {
            background-color: #1abc9c;
            color: #ffffff;
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 21px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #16a085;
        }

        .message {
            text-align: center;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error {
            background-color: #e74c3c;
            color: #ffffff;
        }

        .success {
            background-color: #2ecc71;
            color: #ffffff;
        }
    </style>
</head>
<body>
<video id="background-video" autoplay loop muted playsinline>
        <source src="v6.mp4" type="video/mp4">
    </video>
    <div class="navbar">
    <div class="logo-container">
        <img src="4.png" alt="Logo" class="logo">
        <h2>Car Rental System</h2>
        </div>
        <nav>
        <a href="register_car.php">Register Car</a>
         <a href="add_payment.php">Payment</a>
         <a href="book_car.php">Book Car</a>
         <a href="search_car.php">Search Car</a>
        </nav>
    </div>

    <section>
        <h1>Register New Car</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="register_car.php" method="POST">
            <label for="CarID">Car ID:</label>
            <input type="number" id="CarID" name="CarID" placeholder="Enter carid" required>
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" placeholder="Enter car model" required>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" placeholder="Enter manufacturing year" required>

            <label for="PlateID">Plate ID:</label>
            <input type="text" id="plate" name="plate" placeholder="Enter plate ID" required>

            <label for="color">Color:</label>
            <input type="text" id="color" name="color" placeholder="Enter car color" required> 

            <label for="Price">Price:</label>
            <input type="number" id="Price" name="Price" placeholder="Enter car price" required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Active">Available</option>
                <option value="Rented">Rented</option>
                <option value="Out of Service">Out of Service</option>
            </select>

            <label for="office">Office:</label>
            <select id="office" name="office" required>
                <option value="">Select Office</option>
                <?php foreach ($offices as $office): ?>
                    <option value="<?php echo $office['OfficeID']; ?>"><?php echo $office['OfficeName']; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Register</button>
        </form>
    </section>
</body>
</html>