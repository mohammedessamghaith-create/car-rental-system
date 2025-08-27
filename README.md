# Car Rental System

A web-based application for managing car rentals, built using PHP and MySQL. The system allows users to register cars, book vehicles, process payments, and search for available cars based on various criteria.

## Features
- **Car Registration**: Add new cars with details such as model, year, plate ID, color, status, price, and office location.
- **Car Booking**: Book a car by providing customer details and rental dates.
- **Payment Processing**: Handle payments via cash or Visa, with validation for card details.
- **Car Search**: Search for cars by status, office, color, and year.
- **User Authentication**: Secure login and signup system with role-based access (User/Admin).
- **Responsive Design**: User-friendly interface with a consistent design across all pages.

## Technologies Used
- **Backend**: PHP, MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL (with tables for cars, bookings, payments, users, and offices)
- **Styling**: Custom CSS with a modern, responsive design
- **Media**: Background video and images for enhanced user experience

## Setup Instructions
1. Install a local server environment like **XAMPP** or **WAMP**.
2. Create a MySQL database named `carsproject`.
3. Import the `carsproject.sql` file (if provided) to set up the database schema.
4. Place the project files in the server's root directory (e.g., `htdocs` for XAMPP).
5. Update the database connection settings in each PHP file (e.g., `$servername`, `$username`, `$password`, `$dbname`) if necessary.
6. Access the application via a web browser (e.g., `http://localhost/car-rental-system`).

## File Structure
- `add_payment.php`: Manages payment processing for bookings.
- `book_car.php`: Handles car booking with customer details and rental dates.
- `login.php`: Provides user authentication with role-based access.
- `register_car.php`: Allows registration of new cars with office assignment.
- `search_car.php`: Enables searching for cars based on status, office, color, and year.
- `signup.php`: Facilitates user registration with role selection.
- `assets/`: Contains media files like `v6.mp4` (background video) and `3.jpeg` (navbar background).

## Database Schema
The system uses a MySQL database (`carsproject`) with the following tables:
- **users**: Stores user information (id, username, password, role).
- **cars**: Stores car details (CarID, Model, Year, PlateID, Color, Status, OfficeID, Price).
- **bookings**: Stores booking information (PlateID, name, email, phone, address, start_date, end_date).
- **Payments**: Stores payment details (ReservationID, Amount, PaymentDate, PaymentMethod, CardNumber).
- **offices**: Stores office information (OfficeID, OfficeName).

## Security Notes
- Passwords are hashed using `password_hash()` for secure storage.
- Avoid uploading sensitive configuration files (e.g., `config.php`) to GitHub. Use `.gitignore` to exclude them.
- Ensure proper input validation and sanitization to prevent SQL injection and XSS attacks.

## Future Improvements
- Add admin dashboard for managing users, cars, and bookings.
- Implement email notifications for booking confirmations.
- Enhance search functionality with more filters (e.g., price range).
- Add support for multiple languages.

## Contributing
Feel free to fork this repository, submit pull requests, or report issues to contribute to the project.

## License
This project is licensed under the MIT License.
