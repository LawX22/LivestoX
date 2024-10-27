$(document).ready(function() {
    $('#addPostForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Create FormData object from the form

        $.ajax({
            type: "POST",
            url: "http://localhost/LivestoX/Backend/livestock_posts/add_post.php", // Adjust the URL if needed
            data: formData,
            contentType: false, // Important for file uploads
            processData: false, // Important for file uploads
            success: function(response) {
                const res = JSON.parse(response);
                alert(res.message); // Show response message

                if (res.status === 'success') {
                    // Redirect to browse_livestock.php
                    window.location.href = "http://localhost/LivestoX/Frontend/Farmer/browse_livestock.php"; // Adjust the path if needed
                } else {
                    $('#responseMessage').text(res.message); // Display error message
                }
            },
            error: function() {
                alert('Error adding post');
            }
        });
    });
});
