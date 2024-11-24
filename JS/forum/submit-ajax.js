document.getElementById('questionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var form = this;
    var submitButton = form.querySelector('.submit-btn');
    if (submitButton.disabled) return;

    var formData = new FormData(form);
    submitButton.disabled = true;

    fetch('../../Backend/forum/submit_question.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        submitButton.disabled = false;

        // Use SweetAlert for success or error
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                confirmButtonText: 'OK',
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'OK',
            }).then(() => {
                // Auto-reload after 2 seconds
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            });
        }
    })
    .catch(error => {
        submitButton.disabled = false;
        console.error('Error:', error);

        // Use SweetAlert for catch block error
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'An error occurred. Please try again.',
            confirmButtonText: 'OK',
        }).then(() => {
            // Auto-reload after 2 seconds in case of an error
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        });
    });
});
