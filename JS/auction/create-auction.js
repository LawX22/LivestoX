$(document).ready(function () {
    $('#create-auction-form').on('submit', function (e) {
        e.preventDefault();

        // Validate form inputs
        var isValid = true;
        $(this).find('input, textarea').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).css('border-color', 'red');
            } else {
                $(this).css('border-color', '');
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields.');
            return;
        }

        var submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).text('Submitting...');

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '../../Backend/livestock_auctions/add_auction.php',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (result) {
                if (result.status === 'success') {
                    alert(result.message);
                    location.reload(); // Refresh the page to show the new auction
                } else {
                    alert(result.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', xhr.responseJSON || xhr.responseText);
                alert('An error occurred: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
            },
            complete: function () {
                submitButton.prop('disabled', false).text('Create Auction');
            }
        });
    });
});