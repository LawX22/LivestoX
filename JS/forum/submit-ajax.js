$(document).ready(function() {
    $('#submitPostForm').on('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the traditional way

        var formData = new FormData(this); // Create a FormData object

        $.ajax({
            url: 'submit_question.php', // URL to your PHP file that handles the form submission
            type: 'POST',
            data: formData, // The data to send (from the form)
            contentType: false, // Tell jQuery not to set a content type
            processData: false, // Tell jQuery not to process the data
            success: function(response) {
                var res = response; // Assuming response is already a parsed object

                if (res.status === 'success') {
                    alert(res.message); // Show success message

                    // Optionally, append the new post to the post container without reloading
                    var newPostHTML = `
                        <div class="forum-post card">
                            <div class="post-header">
                                <div class="profile-info">
                                    <div class="profile-image">
                                        <img src="${res.post.profile_image}" alt="Profile Image">
                                    </div>
                                    <div class="name">${res.post.first_name} ${res.post.last_name}</div>
                                    <div class="user-type" style="background-color: #52B788;">${res.post.user_type}</div>
                                    <div class="date-time-container">
                                        <div class="date">${new Date(res.post.created_at).toLocaleString()}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3>${res.post.title}</h3>
                                <p>${res.post.description}</p>
                                ${res.post.image ? `<div class="post-image"><img src="${res.post.image}" alt="Post Image"></div>` : ''}
                            </div>
                            <div class="post-actions">
                                <div class="likes"><i class="fas fa-thumbs-up"></i> 11k</div>
                                <div class="dislikes"><i class="fas fa-thumbs-down"></i> 500</div>
                                <div class="comments"><i class="fas fa-comments"></i> 100</div>
                            </div>
                        </div>
                    `;

                    $('#postContainer').prepend(newPostHTML); // Prepend the new post to the top of the posts container
                } else {
                    alert(res.message); // Show error message if post submission failed
                }
            },
            error: function() {
                alert("An error occurred while submitting your post. Please try again.");
            }
        });
    });
});
