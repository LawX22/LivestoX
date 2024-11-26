$(document).ready(function() {
    $('#login-form').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = $(this).serialize(); // Serialize form data
        console.log(formData); // Log form data to check if it's correct

        // Send the data via AJAX to the backend login.php script
        $.ajax({
            url: '../Backend/login/login.php', // The URL of the backend script
            type: 'POST',
            data: formData,
            dataType: 'json', // Expecting JSON response
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message and redirect
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Redirecting...',
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        // Redirect to the correct page based on user type
                        window.location.href = response.redirect_url; // Redirect
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again later.',
                });
            }
        });
    });
});
