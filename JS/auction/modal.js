function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

function openBiddingModal(biddingModalId) {
    closeAllModals(); // Close all open modals
    document.getElementById(biddingModalId).style.display = "flex";
}

function goBackToDetails(modalId) {
    closeAllModals(); // Close all open modals
    document.getElementById(modalId).style.display = "flex"; // Show the details modal
}

function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => modal.style.display = 'none');
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeAllModals();
    }
}

document.querySelectorAll('.close').forEach(span => {
    span.onclick = function() {
        const modal = span.closest('.modal');
        modal.style.display = "none";
    }
});