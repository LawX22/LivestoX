 // Ensure the modal is hidden when the page loads
 document.addEventListener("DOMContentLoaded", function() {
    var modal = document.getElementById("questionModal");
    modal.style.display = "none";
});

// Get modal elements
var modal = document.getElementById("questionModal");
var btn = document.querySelector(".ask-question-btn");
var span = document.querySelector(".close-btn");

// Open the modal when the button is clicked
btn.onclick = function() {
    modal.style.display = "flex"; // Show the modal (with flex to center it)
}

// Close the modal when the "x" is clicked
span.onclick = function() {
    modal.style.display = "none"; // Hide the modal
}

// Close the modal if the user clicks outside the modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Handle form submission (You can add your form submission logic here)
document.getElementById("questionForm").onsubmit = function(event) {
    event.preventDefault();
    // Perform form validation and submission here
    console.log("Form submitted");
    modal.style.display = "none"; // Close the modal after submission
}






document.getElementById('questionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var form = this;
    var submitButton = form.querySelector('.submit-btn');
    if (submitButton.disabled) return; // Prevent multiple submissions

    var formData = new FormData(form);
    submitButton.disabled = true; // Disable the submit button

    fetch('http://localhost/LivestoX/Backend/forum/submit_question.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        submitButton.disabled = false; // Re-enable the submit button
        if (data.status === 'success') {
            alert(data.message);

            // Reload the page to show the new post
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        submitButton.disabled = false; // Re-enable the submit button on error
        console.error('Error:', error);
    });
});



document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-post').forEach(function(deleteButton) {
        deleteButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Get the post ID from the data attribute
            var postId = this.getAttribute('data-post-id');

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

