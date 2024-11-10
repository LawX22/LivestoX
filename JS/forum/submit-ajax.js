document.getElementById('questionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var form = this;
    var submitButton = form.querySelector('.submit-btn');
    if (submitButton.disabled) return;

    var formData = new FormData(form);
    submitButton.disabled = true;

    fetch('http://localhost/LivestoX/Backend/forum/submit_question.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        submitButton.disabled = false;

        toastr.options = {
            "progressBar": true,
            "closeButton": false,
            "positionClass": "toast-top-right", // Customize position
            "timeOut": "2000"
        };

        if (data.status === 'success') {
            toastr.success(data.message, 'Success');

            setTimeout(function() {
                window.location.reload();
            }, 2000);
            
        } else {
            toastr.error(data.message, 'Error');
        }
    })
    .catch(error => {
        submitButton.disabled = false;
        console.error('Error:', error);
        
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };
        toastr.error('An error occurred. Please try again.', 'Error');
    });
});
