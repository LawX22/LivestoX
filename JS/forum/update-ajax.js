$(document).ready(function () {
    // Open edit modal and populate with post data
    $('.edit-post-btn').on('click', function () {
        const postId = $(this).data('id');
        const postTitle = $(this).data('title');
        const postDescription = $(this).data('description');
        const postImage = $(this).data('image');

        $('#editPostId').val(postId);
        $('#editTitle').val(postTitle);
        $('#editDescription').val(postDescription);
        if (postImage) {
            $('#currentImage').attr('src', postImage).show();
        } else {
            $('#currentImage').hide();
        }

        $('#editModal').show(); // Open modal
    });

    // Handle form submission for updating post
    $('#editPostForm').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        
        $.ajax({
            url: '../../Backend/forum/update_question.php',
            type: 'POST',
            data: formData,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false for file uploads
            success: function (response) {
                const res = JSON.parse(response);

                if (res.status === 'success') {
                    // Update the post dynamically in the forum without page refresh
                    const updatedPost = res.post;
                    const postElement = $(`#post-${updatedPost.id}`);

                    postElement.find('.post-title').text(updatedPost.title);
                    postElement.find('.post-description').html(updatedPost.description.replace(/\n/g, '<br>'));
                    
                    if (updatedPost.image) {
                        postElement.find('.post-image img').attr('src', '../../uploads/forum_posts/' + updatedPost.image).show();
                    } else {
                        postElement.find('.post-image img').hide();
                    }

                    alert('Post updated successfully!');
                    $('#editModal').hide(); // Close the modal
                } else {
                    alert('Error updating post: ' + res.message);
                }
            },
            error: function (xhr, status, error) {
                alert('Something went wrong! Please try again.');
            }
        });
    });

    // Close edit modal
    $('.close-edit-modal').on('click', function () {
        $('#editModal').hide();
    });
});