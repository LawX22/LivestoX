document.addEventListener('DOMContentLoaded', function() {
    // Function to handle post deletion from the DOM
    function removePost(postElement) {
        postElement.remove();
    }

    // Add event listeners to delete buttons
    document.querySelectorAll('.delete-post').forEach(function(deleteButton) {
        deleteButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Get the post ID from the data attribute
            var postId = this.getAttribute('data-post-id-delete');

            // Confirm the deletion using SweetAlert
            Swal.fire({
                title: 'Are you sure?',
                text: 'Once deleted, you cannot recover this post!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the AJAX request to delete the post
                    fetch('../../Backend/forum/delete_question.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ post_id: postId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // SweetAlert for successful deletion
                            Swal.fire(
                                'Deleted!',
                                'Your post has been deleted.',
                                'success'
                            ).then(() => {
                                // Auto reload the page after a successful deletion
                                location.reload();
                            });
                        } else {
                            // SweetAlert for errors
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting your post.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the post.',
                            'error'
                        );
                    });
                }
            });
        });
    });
});
