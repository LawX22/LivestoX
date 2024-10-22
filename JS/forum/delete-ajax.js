// delete-ajax.js

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-post').forEach(function(deleteButton) {
        deleteButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Get the post ID from the data attribute
            var postId = this.getAttribute('data-post-id-delete');

            // Confirm the deletion
            var confirmation = confirm('Are you sure you want to delete this post?');
            if (confirmation) {
                // Send the AJAX request to delete the post
                fetch('http://localhost/LivestoX/Backend/forum/delete_question.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ post_id: postId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Post was successfully deleted, remove it from the DOM
                        this.closest('.forum-post').remove();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the post.');
                });
            }
        });
    });
});
