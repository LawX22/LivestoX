function deleteListing(postId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the listing and its associated image.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the AJAX request to delete the listing
            $.ajax({
                url: '../../Backend/livestock_posts/delete_post.php', // URL to PHP script
                type: 'POST',
                data: { post_id: postId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            confirmButtonText: 'OK',
                        }).then(() => {
                            location.reload(); // Reload the page to reflect changes
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
                }
            });
        }
    });
}
