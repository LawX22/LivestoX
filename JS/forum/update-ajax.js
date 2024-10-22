// update-ajax.js

document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    var editModal = document.getElementById("editModal");
    var closeBtn = document.querySelector("#editModal .close-btn");

    // Function to open the edit modal
    function openEditModal(postId, title, description) {
        document.getElementById('editPostId').value = postId;
        document.getElementById('editTitle').value = title;
        document.getElementById('editDescription').value = description;
        editModal.style.display = "flex";
    }

    // Close the modal when the "x" is clicked
    closeBtn.onclick = function() {
        editModal.style.display = "none";
    }

    // Close the modal if the user clicks outside the modal
    window.onclick = function(event) {
        if (event.target == editModal) {
            editModal.style.display = "none";
        }
    }

    // Add event listeners to the "Edit" links in the dropdown menus
    document.querySelectorAll('.dropdown-item[data-post-id]').forEach(function(editLink) {
        editLink.addEventListener('click', function(event) {
            event.preventDefault();
            var postId = this.getAttribute('data-post-id');
            var postTitle = this.closest('.forum-post').querySelector('h3').textContent.trim();
            var postDescription = this.closest('.forum-post').querySelector('p').textContent.trim();
            openEditModal(postId, postTitle, postDescription);
        });
    });

    // Form submission event listener for the "Save Changes" button
    var editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', function(event) {
        // Show confirmation dialog before saving changes
        var saveConfirm = confirm("Are you sure you want to save these changes?");

        if (saveConfirm) {
            alert("Changes saved successfully!");
        } else {
            event.preventDefault();
            alert("Changes not saved.");
        }
    });
});
