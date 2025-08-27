<?php
// إعداد الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carsproject";

$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['reservation_id']; //rename reservation id to booking_id
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'] ?? null; // رقم البطاقة (اختياري)
    $card_password = $_POST['card_password'] ?? null; // باسورد البطاقة (اختياري)
    $payment_date = date('Y-m-d H:i:s'); // تاريخ الدفع الحالي

    // التحقق من وجود الحقول المطلوبة
    if (empty($booking_id) || empty($amount) || empty($payment_method)) {
        $message = "All fields are required!";
        $message_type = "error";
    } else {
        // التحقق من وجود الحجز في قاعدة البيانات (Corrected Table name here)
        // $stmt = $conn->prepare("SELECT * FROM bookings WHERE bookingID = ?");
        // $stmt->bind_param("i", $booking_id);
        // $stmt->execute();
        // $result = $stmt->get_result();

        // if ($result->num_rows > 0) {

            // التحقق من الدفع باستخدام الفيزا
            if ($payment_method === 'visa' && (empty($card_number) || empty($card_password))) {
                $message = "Card number and password are required for Visa payments!";
                $message_type = "error";
            } else {
                // إضافة الدفع إلى جدول Payments
                $insert_payment_stmt = $conn->prepare("INSERT INTO Payments (ReservationID, Amount, PaymentDate, PaymentMethod, CardNumber) VALUES (?, ?, ?, ?, ?)");
                $insert_payment_stmt->bind_param("iisss", $booking_id, $amount, $payment_date, $payment_method, $card_number);

                if ($insert_payment_stmt->execute()) {
                    $message = "Payment added successfully!";
                    $message_type = "success";
                } else {
                    $message = "Error: Could not add payment.";
                    $message_type = "error";
                }
            }
        // } else {
        //     $message = "Reservation not found!";
        //     $message_type = "error";
        // }
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <style>
          *{
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
        <h1>Add Payment</h1>
        <?php if ($message): ?>
            <div class="<?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="add_payment.php" method="POST">
            <label for="reservation_id">Reservation ID:</label>
            <input type="text" id="reservation_id" name="reservation_id" placeholder="Enter booking ID" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" placeholder="Enter payment amount" required>

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required onchange="toggleVisaFields(this.value)">
                <option value="" disabled selected>Select Payment Method</option>
                <option value="cash">Cash</option>
                <option value="visa">Visa</option>
            </select>

            <!-- حقول الدفع بالفيزا -->
            <div id="visa_fields" style="display: none;">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" placeholder="Enter card number">

                <label for="card_password">Card Password:</label>
                <input type="password" id="card_password" name="card_password" placeholder="Enter card password">
            </div>

            <button type="submit">Add Payment</button>
        </form>
    </section>

    <script>
        // إظهار أو إخفاء حقول الفيزا بناءً على اختيار المستخدم
        function toggleVisaFields(paymentMethod) {
            const visaFields = document.getElementById('visa_fields');
            if (paymentMethod === 'visa') {
                visaFields.style.display = 'block';
            } else {
                visaFields.style.display = 'none';
            }
        }
    </script>
</body>

</html>