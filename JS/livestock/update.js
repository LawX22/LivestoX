$(document).ready(function () {
    $('#updateLivestockForm').on('submit', function (e) {
        e.preventDefault(); // Prevent form submission

        let formData = new FormData(this); // Collect form data

        $.ajax({
            url: '../../Backend/livestock_posts/update_post.php', // PHP script URL
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
                // Disable the submit button to prevent multiple submissions
                $('#submitLivestockButton').prop('disabled', true).text('Updating...');
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonText: 'OK',
                    }).then(() => {
                        closeModal('updateLivestockModal'); // Close the modal
                        $('#updateLivestockForm')[0].reset(); // Reset the form
                        location.reload(); // Reload the page
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                        confirmButtonText: 'OK',
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'An error occurred while processing your request.',
                    confirmButtonText: 'OK',
                });
            },
            complete: function () {
                // Re-enable the submit button
                $('#submitLivestockButton').prop('disabled', false).text('Update Livestock');
            },
        });
    });
});
