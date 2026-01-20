[![Live Preview](https://img.shields.io/badge/Live-Preview-blue?style=for-the-badge)](https://autopulse.page.gd/home.php)




# AutoPulse 🚗💨
**AutoPulse** is a comprehensive web-based vehicle management and service platform designed to connect car owners with automotive services and parts. It features a robust multi-module architecture catering to Guests, Car Owners, and Administrators.
## 🌟 Features
### 👤 Guest Module
*   **Landing Page**: Overview of services and products.
*   **Service Estimator**: Get cost estimates for various vehicle services.
*   **Product Catalog**: Browse available auto parts.
*   **Authentication**: Login and Registration for new users.
### 🚘 Car Owner Module
*   **Dashboard**: Real-time overview of vehicles, active services, and spending stats.
*   **Vehicle Management**: Add, view, and manage personal vehicles.
*   **Service Booking**:
    *   **Home Service**: Schedule mechanics to come to your location.
    *   **Emergency Towing**: Request immediate roadside assistance.
*   **Parts Store (E-commerce)**:
    *   Browse and search for parts.
    *   Add to Cart (AJAX-powered).
    *   Secure Checkout.
    *   Order History & Invoices.
*   **Profile Management**: Update personal details and profile picture.
### 🛠️ Admin Module
*   **Dashboard**: System-wide statistics.
*   **User Management**: View and manage registered users.
*   **Service Management**: Add, edit, or remove available services and pricing.
*   **Inventory/Parts Management**: Manage stock, pricing, and details of auto parts.
## 💻 Technology Stack
*   **Backend**: PHP (Native, Functional MVC Architecture)
*   **Database**: MySQL (PDO with Prepared Statements for security)
*   **Frontend**: HTML5, CSS3, JavaScript (Vanilla ES6+)
*   **Styling**: Custom CSS (Responsive Design)
*   **Communication**: AJAX / JSON for dynamic interactions

## 📂 Project Structure
```text
AutoPulse/
├── Admin/          # Administrative features and dashboard
├── CarOwner/       # Car owner specific features (Dashboard, Booking, Orders)
├── Guest/          # Public facing pages (Home, Landing, Login)
├── assets/         # Shared static assets
└── autopulse.sql # Database import file
```
## 🛡️ Security Features
*   **Input Sanitization**: `htmlspecialchars` to prevent XSS.
*   **SQL Injection Protection**: extensive use of PDO prepared statements.
*   **Password Hashing**: `password_hash` (Bcrypt) for secure credential storage.
*   **Access Control**: Role-based session checks on all protected pages.
