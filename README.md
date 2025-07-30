# Vehicle Rental System

A comprehensive web-based vehicle rental platform built with PHP, MySQL, and modern web technologies. This system facilitates vehicle rentals between hosts (vehicle owners) and customers, with role-based access control and advanced booking management features.

## 🚗 Features

### For Customers
- **User Registration & Authentication**: Secure login/registration system with role-based access
- **Vehicle Browsing**: Browse available vehicles with detailed information
- **Advanced Search & Filtering**: Filter vehicles by:
  - Company/Model name
  - Category (SUV, Sedan, Truck, etc.)
  - Number of passengers
  - Maximum price per day
  - Location (city)
  - Available dates
- **Smart Date Picker**: Interactive date selection with real-time availability checking
- **Booking Management**: 
  - Create new bookings with automatic price calculation
  - View all personal bookings with status tracking
  - Edit existing bookings
  - Cancel bookings
- **Real-time Price Calculation**: Automatic total price calculation based on rental duration
- **Booking History**: Complete history of all bookings with status updates

### For Hosts/Admins
- **Vehicle Management**:
  - Add new vehicles with detailed specifications
  - Upload vehicle images
  - Edit vehicle information
  - Delete vehicles
  - View all listings
- **Booking Management**:
  - View all incoming booking requests
  - Approve or reject bookings
  - Track booking status
  - View booking details
- **Dashboard Analytics**:
  - Monthly booking statistics
  - Revenue tracking
  - Pending booking counts
- **Notification System**: Real-time notifications for new booking requests

### System Features
- **Role-Based Access Control**: Separate interfaces for customers and hosts
- **Session Management**: Secure user sessions with proper authentication
- **Database Security**: SQL injection prevention using prepared statements
- **Responsive Design**: Modern UI with Bootstrap framework
- **File Upload System**: Secure image upload for vehicle photos
- **Location Management**: Vehicle location tracking with city and address details

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Bootstrap 5.3.3
- **Icons**: Bootstrap Icons
- **Date Picker**: Flatpickr.js
- **Server**: XAMPP (Apache + MySQL)

## 📁 Project Structure

```
vehicle-rental/
├── config/
│   └── connection.php          # Database connection configuration
├── css/
│   ├── booking.css            # Booking page styles
│   ├── dashboard.css          # Dashboard styles
│   ├── form.css              # Form styling
│   ├── login-register.css    # Authentication page styles
│   └── receipt.css           # Receipt styling
├── images/
│   └── profile.png           # Default profile image
├── includes/
│   └── footer.php            # Common footer component
├── js/
│   └── datePicker.js         # Date picker functionality
├── uploads/                  # Vehicle image uploads
├── views/                    # View templates
│   ├── add_vehicle_form.php  # Add vehicle form
│   ├── admin-dashboard.php   # Host dashboard
│   ├── all_booking.php       # Customer booking history
│   ├── all_listings.php      # Host vehicle listings
│   ├── booking_form.php      # Booking creation form
│   ├── customer-dashboard.php # Customer dashboard
│   ├── edit_booking_form.php # Edit booking form
│   ├── edit_vehicle_form.php # Edit vehicle form
│   ├── host_booking_details.php # Booking details for hosts
│   ├── login-form.php        # Login form
│   ├── receipt.php           # Booking receipt
│   └── register-form.php     # Registration form
├── add_vehicle.php           # Vehicle addition processing
├── approve_booking.php       # Booking approval processing
├── cancel_booking.php        # Booking cancellation
├── delete_vehicle.php        # Vehicle deletion
├── edit_booking.php          # Booking editing
├── edit_vehicle.php          # Vehicle editing
├── index.php                 # Main entry point
├── login.php                 # Login processing
├── logout.php                # Logout functionality
├── process_booking.php       # Booking processing
├── reject_booking.php        # Booking rejection
├── register.php              # Registration processing
└── README.md                 # Project documentation
```

## 🗄️ Database Schema

The system uses a MySQL database with the following main tables:

- **users**: User accounts with role-based access (Customer/Admin)
- **vehicle**: Vehicle information including specifications, pricing, and images
- **booking**: Booking records with status tracking
- **location**: Vehicle location information
- **notifications**: System notifications for users

## 🚀 Installation & Setup

### Prerequisites
- XAMPP (or similar local server stack)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Installation Steps

1. **Clone/Download the Project**
   ```bash
   # Place the project in your XAMPP htdocs directory
   /Applications/XAMPP/xamppfiles/htdocs/vehicle-rental/
   ```

2. **Database Setup**
   - Start XAMPP and ensure Apache and MySQL are running
   - Create a new MySQL database named `vehicle-rental`
   - Import the database schema (SQL file should be provided)

3. **Configuration**
   - Update database connection settings in `config/connection.php` if needed
   - Ensure the `uploads/` directory has write permissions for image uploads

4. **Access the Application**
   - Open your browser and navigate to `http://localhost/vehicle-rental/`
   - Register as either a Customer or Admin user
   - Start using the system!

## 🔐 Security Features

- **Password Hashing**: All passwords are hashed using PHP's `password_hash()` function
- **SQL Injection Prevention**: Prepared statements used throughout the application
- **Session Security**: Proper session management with authentication checks
- **Input Validation**: Server-side validation for all user inputs
- **File Upload Security**: Secure image upload with validation

## 🎨 User Interface

The application features a modern, responsive design with:
- Clean and intuitive navigation
- Card-based layouts for vehicle listings
- Interactive date pickers with availability checking
- Real-time price calculations
- Status indicators for bookings
- Notification system with badge counters

## 📱 Key Functionalities

### Smart Date Selection
The system includes an advanced date picker that:
- Prevents booking conflicts by checking existing reservations
- Automatically calculates rental duration and total price
- Disables unavailable dates in real-time
- Ensures minimum rental periods

### Booking Workflow
1. Customer searches for available vehicles
2. Selects desired dates (automatically checked for availability)
3. System calculates total price
4. Customer submits booking request
5. Host receives notification and can approve/reject
6. Booking status updates automatically

### Vehicle Management
Hosts can:
- Add vehicles with comprehensive details
- Upload high-quality images
- Set competitive pricing
- Manage availability through the booking system
- Track rental performance

## 🔧 Customization

The system is designed to be easily customizable:
- CSS files are organized by functionality
- PHP includes are used for common components
- Modular structure allows for easy feature additions
- Database schema supports additional vehicle attributes

## 📊 Performance Features

- Efficient database queries with proper indexing
- Optimized image handling for vehicle photos
- Responsive design for various screen sizes
- Fast search and filtering capabilities

## 🤝 Contributing

This is a complete vehicle rental system ready for deployment. The codebase follows PHP best practices and can be extended with additional features as needed.

## 📄 License

This project is developed as a complete vehicle rental solution and is ready for production use.

---

**Note**: This system is designed to run on a local server environment (XAMPP) and can be deployed to a production server with appropriate configuration changes.
