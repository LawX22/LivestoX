// Open Modal
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

// Close Modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Fetch post data and open update modal
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

// Open specific Post Modal (Livestock or Auction)
function openPostModal(postModalId) {
    closeModal('createListingModal'); // Close the initial modal
    openModal(postModalId); // Open the respective post modal
}
