# ToolTitan - User Management System

A complete, responsive web application built with PHP, MySQL, JavaScript, and Bootstrap CSS for managing users with different roles (Admin, Supplier, Customer).

## Features

- **Responsive Design**: Built with Bootstrap 5 for mobile-first responsive design
- **Role-Based Access Control**: Three user roles with different permissions
- **User Management**: Complete CRUD operations for user accounts
- **Secure Authentication**: Session-based login system
- **Modern UI**: Clean, professional interface with Bootstrap components
- **Database Integration**: MySQL database with PDO for secure data handling

## User Roles

### Admin
- Manage all users (create, read, update, delete)
- View system statistics
- Access to all administrative functions

### Supplier
- Manage products and inventory
- Add new products
- Update stock levels
- View supplier-specific data

### Customer
- Browse products
- Place orders
- View order history
- Customer-specific features

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP/WAMP (for local development)

### Setup Steps

1. **Clone/Download the project**
   ```
   Place all files in your web server directory (e.g., htdocs for XAMPP)
   ```

2. **Database Setup**
   - Create a MySQL database named `tooltitan`
   - Import the `setup_database.sql` file to create tables and sample data
   - Or run the SQL commands manually in your MySQL client

3. **Configure Database Connection**
   - Edit `config/db.php` if needed
   - Update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'tooltitan');
     ```

4. **Start Your Web Server**
   - For XAMPP: Start Apache and MySQL services
   - Access the application at `http://localhost/tooltitan.github.io/`

## Default Login Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | 123 |
| Supplier | supplier | 123 |
| Customer | customer | 123 |

## File Structure

```
tooltitan.github.io/
├── admin/
│   ├── manage_user.php      # User management (Admin only)
│   ├── manage_products.php  # Product management
│   └── view_orders.php      # Order management
├── config/
│   └── db.php              # Database configuration
├── customer/
│   ├── view_products.php   # Product catalog
│   ├── place_order.php     # Order placement
│   └── my_orders.php       # Order history
├── includes/
│   ├── auth.php            # Authentication functions
│   ├── header.php          # Common header with navigation
│   └── footer.php          # Common footer
├── supplier/
│   ├── add_product.php     # Add new products
│   ├── my_products.php     # Manage products
│   └── update_stock.php    # Stock management
├── assets/
│   └── images/
│       └── LOGO.png        # Application logo
├── index.php               # Main dashboard
├── loginpage.php           # Login form
├── logout.php              # Logout handler
├── admin.php               # Admin dashboard
├── supplier.php            # Supplier dashboard
├── customer.php            # Customer dashboard
├── setup_database.sql      # Database setup script
└── README.md               # This file
```

## Technologies Used

- **Backend**: PHP 7.4+ with PDO for database operations
- **Database**: MySQL with InnoDB engine
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5.3.2 for responsive design
- **Icons**: Bootstrap Icons
- **Security**: Session-based authentication, prepared statements

## Security Features

- **SQL Injection Protection**: All database queries use prepared statements
- **Session Management**: Secure session handling for user authentication
- **Role-Based Access**: Users can only access features appropriate to their role
- **Input Validation**: Client-side and server-side form validation
- **CSRF Protection**: Forms include proper validation (can be enhanced)

## Browser Compatibility

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Development Notes

### Password Security
- Current implementation uses plain text passwords for demo purposes
- **Production**: Replace with `password_hash()` and `password_verify()`

### Database Optimization
- Indexes are properly set on foreign keys
- Consider adding composite indexes for frequently queried columns

### Future Enhancements
- Email verification for new accounts
- Password reset functionality
- Advanced product search and filtering
- Order tracking system
- Inventory alerts
- Reporting dashboard

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check MySQL service is running
   - Verify database credentials in `config/db.php`
   - Ensure database `tooltitan` exists

2. **Login Issues**
   - Verify user exists in database
   - Check username/password combination
   - Ensure sessions are working (check PHP session configuration)

3. **Permission Errors**
   - Check file permissions on web server
   - Ensure PHP has write access to session directory

4. **Responsive Issues**
   - Clear browser cache
   - Check Bootstrap CSS is loading properly
   - Verify viewport meta tag is present

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Verify all setup steps were completed
3. Check browser console for JavaScript errors
4. Review PHP error logs

## License

This project is for educational and demonstration purposes.