$(document).ready(function () {
    $('#add-post-form').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        // Disable the submit button to prevent multiple clicks
        var submitButton = $('#add-post-form button[type="submit"]');
        submitButton.prop('disabled', true).text('Submitting...');

        var formData = new FormData(this); // Create a FormData object

        $.ajax({
            type: 'POST',
            url: 'http://localhost/LivestoX/Backend/livestock_posts/add_post.php', // Path to the backend PHP file
            data: formData,
            contentType: false, // Set to false for FormData
            processData: false, // Prevent jQuery from processing the data
            success: function (response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message); // Success message
                    $('#add-post-form')[0].reset(); // Reset the form
                } else {
                    alert(result.message); // Error message
                }
            },
            error: function () {
                alert('An error occurred while adding the post.'); // Handle errors
            },
            complete: function () {
                // Re-enable the button after the AJAX call is complete
                submitButton.prop('disabled', false).text('Submit');
            }
        });
    });
});



function deleteListing(postId) {
    if (confirm("Are you sure you want to delete this listing?")) {
        $.ajax({
            url: "http://localhost/LivestoX/Backend/livestock_posts/delete_post.php",
            type: "POST",
            data: { post_id: postId },
            success: function(response) {
                alert(response);
                location.reload(); // Refresh the page after deletion
            }
        });
    }
}


$(document).ready(function () {
    $('#update-post-form').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // Create a FormData object

        $.ajax({
            type: 'POST',
            url: 'http://localhost/LivestoX/Backend/livestock_posts/update_post.php', // Path to the backend PHP file
            data: formData,
            contentType: false, // Set to false for FormData
            processData: false, // Prevent jQuery from processing the data
            success: function (response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message); // Success message
                    // Optionally, redirect or update the UI here
                    location.reload(); // Refresh the page to show updated data
                } else {
                    alert(result.message); // Error message
                }
            },
            error: function () {
                alert('An error occurred while updating the post.'); // Handle errors
            }
        });
    });
});

