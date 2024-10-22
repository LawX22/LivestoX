// forum-modal.js

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

// Handle form submission (Add your form submission logic here)
document.getElementById("questionForm").onsubmit = function(event) {
    event.preventDefault();
    console.log("Form submitted");
    modal.style.display = "none"; // Close the modal after submission
}


// <!-- New script to handle the submit button state -->
$(document).ready(function() {
    // Disable the submit buttons on page load
    $('.submit-btn').attr('disabled', true);

    // Function to check if inputs are filled
    function checkInputs(formType) {
        var title, description;
        
        if (formType === 'question') {
            title = $('#questionTitle').val().trim();
            description = $('#questionDescription').val().trim();
        } else if (formType === 'edit') {
            title = $('#editTitle').val().trim();
            description = $('#editDescription').val().trim();
        }

        // Enable button if both title and description have input
        if (title.length > 0 && description.length > 0) {
            $('.submit-btn').attr('disabled', false);
        } else {
            $('.submit-btn').attr('disabled', true);
        }
    }

    // Monitor changes in both the title and description fields for the new post form
    $('#questionTitle, #questionDescription').on('input', function() {
        checkInputs('question');
    });

    // Monitor changes in both the title and description fields for the edit post form
    $('#editTitle, #editDescription').on('input', function() {
        checkInputs('edit');
    });
});
