// Open Modal
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

// Close Modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}



// Open specific Post Modal (Livestock or Auction)
function openPostModal(postModalId) {
    closeModal('createListingModal'); // Close the initial modal
    openModal(postModalId); // Open the respective post modal
}
