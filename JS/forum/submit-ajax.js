// submit-ajax.js

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
