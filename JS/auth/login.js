$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        // Fetch input values
        let username = $('input[name="username"]').val();
        let password = $('input[name="password"]').val();

        // Validation for empty fields
        if (username === "" || password === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Both fields are required!',
                confirmButtonText: 'Okay',
                confirmButtonColor: '#ffcc00',
            });
            return;
        }

        // Send login data via AJAX
        $.ajax({
            url: '../../Backend/login/login.php',
            method: 'POST',
            data: {
                username: username,
                password: password,
                submit: true,
            },
            dataType: 'json', // Expecting JSON response from the server
            success: function (response) {
                // Handle the response based on its status
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Redirecting to your dashboard...',
                        imageUrl: '../../Assets/success-icon.png', // Example image
                        imageWidth: 100,
                        imageHeight: 100,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        // Redirect to the returned URL
                        window.location.href = response.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                        footer: '<a href="forgot-password.php">Forgot your password?</a>',
                        confirmButtonText: 'Retry',
                    });
                }
            },
            error: function () {
                // Handle AJAX errors
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'An error occurred while processing your request. Please try again later.',
                    confirmButtonText: 'Retry',
                    confirmButtonColor: '#d33',
                    background: '#f8d7da',
                });
            },
        });
    });
});
