$(document).ready(function () {
    $('#logout-button').click(function (event) {
        event.preventDefault(); // Prevent default button behavior

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../../Backend/login/logout.php',
                    type: 'POST',
                    success: function (response) {
                        let result = JSON.parse(response);

                        if (result.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Logged Out',
                                text: 'Redirecting to login page...',
                                timer: 2000,
                                showConfirmButton: false,
                            }).then(() => {
                                window.location.href = result.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to log out. Please try again.',
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.',
                        });
                    },
                });
            }
        });
    });
});