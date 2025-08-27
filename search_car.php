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

// جلب قائمة الفروع
$offices = [];
$office_query = "SELECT OfficeID, OfficeName FROM Offices";
$result = $conn->query($office_query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $offices[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $office = isset($_POST['office']) ? (int)$_POST['office'] : 0;
    $color = isset($_POST['color']) ? trim($_POST['color']) : "";
    $year = isset($_POST['year']) ? (int)$_POST['year'] : 0;

    // استعلام البحث عن السيارات بناءً على الحالة والفرع واللون والسنة
    $sql = "SELECT cars.model, cars.year, cars.PlateID, cars.color, cars.status, offices.OfficeName 
            FROM cars 
            LEFT JOIN offices ON cars.OfficeID = offices.OfficeID 
            WHERE cars.status = '$status'";
    if ($office > 0) {
        $sql .= " AND cars.OfficeID = $office";
    }
    if (!empty($color)) {
        $sql .= " AND cars.color = '$color'";
    }
    if ($year > 0) {
        $sql .= " AND cars.year = $year";
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // النتائج جاهزة للعرض
        $message = "Cars found successfully!";
        $message_type = "success";
    } else {
        $message = "No cars found with the selected criteria.";
        $message_type = "error";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Cars</title>
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
        table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    table-layout: auto; /* لتحديد عرض الأعمدة تلقائيًا بناءً على المحتوى */
    word-wrap: break-word; /* لتمكين التفاف النصوص الطويلة */
}
table td {
    background-color: #111; /* لون خلفية أبيض للخلايا */
}

table th, table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
    overflow: hidden; /* لإخفاء النصوص الزائدة داخل الخلية */
    text-overflow: ellipsis; /* إضافة "..." إذا كان النص طويلًا جدًا */
    white-space: nowrap; /* منع التفاف النصوص داخل الخلايا */
    color: #fff; /* تغيير لون النصوص إلى الأسود */


}

table th {
    background-color: #333;
    color: #fff;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* إضافة شريط تمرير أفقي عند الحاجة */
section {
    overflow-x: auto;
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
        <h1>Search for Cars</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="search_car.php" method="POST">
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="">-- Select Status --</option>
                <option value="Active">Available</option>
                <option value="Rented">Rented</option>
                <option value="Out of Service">Out of Service</option>
            </select>

            <label for="office">Office:</label>
            <select id="office" name="office">
                <option value="">-- All Offices --</option>
                <?php foreach ($offices as $office): ?>
                    <option value="<?php echo $office['OfficeID']; ?>"><?php echo $office['OfficeName']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="color">Color (Optional):</label>
            <input type="text" id="color" name="color" placeholder="Enter car color">

            <label for="year">Year (Optional):</label>
            <input type="number" id="year" name="year" placeholder="Enter car manufacturing year">

            <button type="submit">Search</button>
        </form>
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Plate ID</th>
                        <th>Color</th>
                        <th>Status</th>
                        <th>Office</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['model']; ?></td>
                            <td><?php echo $row['year']; ?></td>
                            <td><?php echo $row['PlateID']; ?></td>
                            <td><?php echo $row['color']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['OfficeName']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</body>

</html>