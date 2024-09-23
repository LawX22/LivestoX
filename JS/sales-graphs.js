const salesData = {
    labels: ['Cow','Cattle', 'Sheep', 'Goat', 'Pig', 'Chicken'],
    datasets: [{
        label: 'Sales ($)',
        data: [15000, 4000, 2500, 1500, 6000, 4500], // Replace with dynamic data if needed
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
    labels: ['Cow','Cattle', 'Sheep', 'Goat', 'Pig', 'Chicken'],
    datasets: [{
        label: 'Livestock Posted',
        data: [20, 15, 10, 10, 5, 12], // Example data for livestock posted
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