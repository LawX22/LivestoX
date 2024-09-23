<?php
session_start();
include('../../Backend/db/db_connect.php'); 

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'farmer') {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['id'];
$query = "SELECT first_name, last_name FROM tbl_users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_name, $last_name);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Sell History Page</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/sellhistory.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <?php 
            $page = 'sellhistory';
            include('../../sidebar/sidebar-farmer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="logo">LivestoX</div>
            </header>

            <div class="header-text">
                <h1>Sell History</h1>
                <p>Your livestock sales, simplified and easy to track.</p>
            </div>

            <section class="main-section">
                <div class="sell-history-section">
                    <div class="history-header">
                        <h2>Overview of Sales</h2>
                        <div class="history-filters">
                            <input type="text" id="search" placeholder="Search ">
                            <select id="filter-type">
                                <option value="">Livestock Type</option>
                                <option value="cattle">Cattle</option>
                                <option value="sheep">Sheep</option>
                                <option value="goat">Goat</option>
                            </select>
                            <select id="filter-date">
                                <option value="">Date</option>
                                <option value="last-7-days">Last 7 Days</option>
                                <option value="last-30-days">Last 30 Days</option>
                                <option value="last-year">Last Year</option>
                            </select>
                        </div>
                    </div>

                    <div class="sell-history-grid">
                        <div class="history-card">
                            <div class="card-header">
                                <h3>Cattle Sale</h3>
                                <span class="sale-date">15 Sept 2023</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Quantity:</strong> 10</p>
                                <p><strong>Price:</strong> $15,000</p>
                                <p><strong>Buyer:</strong> John Doe Farms</p>
                            </div>
                            <div class="card-footer">
                                <span class="status-badge completed">Completed</span>
                            </div>
                        </div>
                        <div class="history-card">
                            <div class="card-header">
                                <h3>Cattle Sale</h3>
                                <span class="sale-date">15 Sept 2023</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Quantity:</strong> 10</p>
                                <p><strong>Price:</strong> $15,000</p>
                                <p><strong>Buyer:</strong> John Doe Farms</p>
                            </div>
                            <div class="card-footer">
                                <span class="status-badge pending">Pending</span>
                            </div>
                        </div>
                        <div class="history-card">
                            <div class="card-header">
                                <h3>Cattle Sale</h3>
                                <span class="sale-date">15 Sept 2023</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Quantity:</strong> 10</p>
                                <p><strong>Price:</strong> $15,000</p>
                                <p><strong>Buyer:</strong> John Doe Farms</p>
                            </div>
                            <div class="card-footer">
                                <span class="status-badge completed">Completed</span>
                            </div>
                        </div><div class="history-card">
                            <div class="card-header">
                                <h3>Cattle Sale</h3>
                                <span class="sale-date">15 Sept 2023</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Quantity:</strong> 10</p>
                                <p><strong>Price:</strong> $15,000</p>
                                <p><strong>Buyer:</strong> John Doe Farms</p>
                            </div>
                            <div class="card-footer">
                                <span class="status-badge completed">Completed</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="graph-section">
                    <h2>Sales Chart</h2>
                    <p>Monitor your livestock sales and postings to track performance</p>

                    <div class="graphs">
                        <h3>Total Sales by Type</h3>
                        <canvas id="salesChart" style="max-width: 800px; margin: auto;"></canvas>

                        <h3>Livestock Posted for Sale</h3>
                        <canvas id="postedChart" style="max-width: 800px; margin: auto;"></canvas>
                    </div>
                </div>

            </section>
        </div>
    </div>

    <script src="../../js/logout-confirmation.js"></script>
    <script>
        const salesData = {
    labels: ['Cattle', 'Sheep', 'Goat'],
    datasets: [{
        label: 'Sales ($)',
        data: [15000, 4000, 2500], // Replace with dynamic data if needed
        backgroundColor: [
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)'
        ],
        borderColor: [
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
    }]
};

const postedData = {
    labels: ['Cattle', 'Sheep', 'Goat'],
    datasets: [{
        label: 'Livestock Posted',
        data: [20, 15, 10], // Example data for livestock posted
        backgroundColor: [
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)'
        ],
        borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)'
        ],
        borderWidth: 1
    }]
};

const salesConfig = {
    type: 'bar',
    data: salesData,
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

const postedConfig = {
    type: 'bar',
    data: postedData,
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

const salesChart = new Chart(
    document.getElementById('salesChart'),
    salesConfig
);

const postedChart = new Chart(
    document.getElementById('postedChart'),
    postedConfig
);

    </script>
</body>
</html>
