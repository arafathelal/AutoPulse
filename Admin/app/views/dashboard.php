<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoPulse Admin Panel</title>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Updated CSS Path -->
    <link rel="stylesheet" href="css/admin.css">

</head>

<body>
    <nav class="navbar">
        <a href="#" class="brand" onclick="showSection('dashboard')">
            <i class="fas fa-wrench"></i> AutoPulse
        </a>

        <div class="nav-links" id="navLinks">
            <a href="#" class="nav-link active" onclick="showSection('dashboard', this)">Dashboard</a>
            <a href="#" class="nav-link" onclick="showSection('services', this)">Services</a>
            <a href="#" class="nav-link" onclick="showSection('parts', this)">Parts & Items</a>
            <a href="#" class="nav-link" onclick="showSection('users', this)">Users</a>
            <a href="../../Guest/public/index.php?page=logout" class="nav-link" style="color: var(--danger);">Logout</a>
        </div>

        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="user-profile">
                <span
                    style="font-size: 0.9rem; font-weight: 500;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                <div class="avatar">A</div>
            </div>
            <div class="mobile-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <main>
        <section id="dashboard" class="section-view active">
            <header>
                <h1>Dashboard Overview</h1>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Total Services</h3>
                        <p id="stat-services">0</p>
                    </div>
                    <div class="stat-icon bg-blue"><i class="fas fa-tools"></i></div>
                </div>
                <!-- Added Low Stock Card from original -->
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Low Stock Items</h3>
                        <p id="stat-stock">0</p>
                    </div>
                    <div class="stat-icon bg-orange"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Total Users</h3>
                        <p id="stat-users">0</p>
                    </div>
                    <div class="stat-icon bg-green"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <!-- Hide Recent Activity for now unless we implement activity logging -->
            <!-- <h3>Recent Activity</h3> ... -->
        </section>

        <section id="services" class="section-view">
            <header>
                <h1>Manage Services</h1>
                <button class="btn btn-primary" onclick="openModal('service')"><i class="fas fa-plus"></i> Add
                    Service</button>
            </header>
            <input type="text" placeholder="Search services..." class="search-bar"
                onkeyup="filterTable('services-table-body', this.value)">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Service Name</th>
                            <th>Price ($)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="services-table-body">
                    </tbody>
                </table>
            </div>
        </section>

        <section id="parts" class="section-view">
            <header>
                <h1>Inventory Management</h1>
                <button class="btn btn-primary" onclick="openModal('part')"><i class="fas fa-plus"></i> Add
                    Part</button>
            </header>
            <input type="text" placeholder="Search parts..." class="search-bar"
                onkeyup="filterTable('parts-table-body', this.value)">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Part Name</th>
                            <th>Price ($)</th>
                            <th>Stock Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="parts-table-body">

                    </tbody>
                </table>
            </div>
        </section>

        <section id="users" class="section-view">
            <header>
                <h1>User Management</h1>
                <button class="btn btn-primary" onclick="openModal('user')"><i class="fas fa-user-plus"></i> Add
                    User</button>
            </header>
            <input type="text" placeholder="Search users..." class="search-bar"
                onkeyup="filterTable('users-table-body', this.value)">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">

                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div class="modal-overlay" id="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title">Add Item</h3>
                <i class="fas fa-times close-modal" onclick="closeModal()"></i>
            </div>
            <form id="dynamic-form" onsubmit="handleFormSubmit(event)">
                <input type="hidden" id="edit-id">
                <input type="hidden" id="item-type">
                <div id="form-fields"></div>
                <div style="margin-top: 25px; text-align: right;">
                    <button type="button" class="btn" style="background: #f1f5f9; margin-right: 10px;"
                        onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast-container" id="toast-container"></div>

    <!-- Updated JS Path -->
    <script src="js/admin.js"></script>
</body>

</html>