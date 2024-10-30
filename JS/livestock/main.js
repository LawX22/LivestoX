$(document).ready(function () {
    $('#add-post-form').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

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
                    // Optionally reset the form here
                    $('#add-post-form')[0].reset();
                } else {
                    alert(result.message); // Error message
                }
            },
            error: function () {
                alert('An error occurred while adding the post.'); // Handle errors
            }
        });
    });
});