$(document).ready(function () {
    // Handle delete post action
    $('.delete-post-btn').on('click', function () {
        var postId = $(this).data('id');

        if (confirm('Are you sure you want to delete this post?')) {
            $.ajax({
                url: '../../Backend/forum/delete_question.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    post_id: postId
                }),
                success: function (response) {
                    const res = JSON.parse(response);

                    if (res.status === 'success') {
                        // Remove the post element from the DOM
                        $(`#post-${postId}`).remove();
                        alert('Post deleted successfully!');
                    } else {
                        alert('Error: ' + res.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert('Something went wrong! Please try again.');
                }
            });
        }
    });
});

