document.addEventListener('DOMContentLoaded', function() {
    const logoutButton = document.querySelector('.logout-button');
    
    logoutButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default action
        if (confirm("Are you sure you want to log out?")) {
            document.getElementById('logout-link').click(); // Proceed with logout
        }
    });
});
