// Toggle the dropdown menu when the kebab icon is clicked
function toggleDropdown(postId) {
    var dropdown = document.getElementById('dropdown-' + postId);
    
    // Toggle the display of the dropdown
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none"; // Hide the dropdown
    } else {
        dropdown.style.display = "block"; // Show the dropdown
    }
}

// Open Modal for editing the post
function openUpdateModal(postId) {
    // Fetch existing post data
    fetch(`../../Backend/livestock_posts/fetch_post.php?post_id=${postId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Populate the modal fields with existing data
                document.getElementById('update-post-id').value = postId;
                document.getElementById('update-livestock-title').value = data.data.title;
                document.getElementById('update-livestock-description').value = data.data.description;
                document.getElementById('update-livestock-type').value = data.data.livestock_type;
                document.getElementById('update-livestock-breed').value = data.data.breed;
                document.getElementById('update-livestock-age').value = data.data.age;
                document.getElementById('update-livestock-weight').value = data.data.weight;
                document.getElementById('update-livestock-health-status').value = data.data.health_status;
                document.getElementById('update-livestock-location').value = data.data.location;
                document.getElementById('update-livestock-price').value = data.data.price;
                document.getElementById('update-livestock-quantity').value = data.data.quantity;

                // Open the update modal
                openModal('updateLivestockModal');
            } else {
                alert(data.message); // Handle the error
            }
        })
        .catch(error => console.error('Error fetching post data:', error));
}

// Open a generic modal
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

// Close a generic modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

function toggleDropdown(postId) {
    const dropdown = document.getElementById(`dropdown-${postId}`);
    dropdown.classList.toggle('show');
}
