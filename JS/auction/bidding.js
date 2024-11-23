$(document).ready(function() {
    // Handle bid submission
    $('.bid-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const auctionId = form.data('auction-id');
        const bidAmount = form.find('input[name="bid_amount"]').val();
        const submitButton = form.find('button[type="submit"]');

        // Disable submit button
        submitButton.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '../../Backend/livestock_auctions/place_bid.php',
            data: {
                auction_id: auctionId,
                bid_amount: bidAmount
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload(); // Refresh to show updated bid
                } else {
                    alert(response.message || 'Error placing bid');
                }
            },
            error: function(xhr, status, error) {
                alert('Error placing bid: ' + error);
            },
            complete: function() {
                submitButton.prop('disabled', false);
            }
        });
    });

    // Search functionality
    $('#searchAuctions').on('input', function() {
        const searchText = $(this).val().toLowerCase();
        $('.auction-card').each(function() {
            const title = $(this).find('.auction-title').text().toLowerCase();
            const description = $(this).find('.auction-description').text().toLowerCase();
            const farmerName = $(this).find('.farmer-name').text().toLowerCase();
            
            if (title.includes(searchText) || 
                description.includes(searchText) || 
                farmerName.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Load bid history when viewing bids
    function loadBidHistory(auctionId) {
        $.ajax({
            type: 'GET',
            url: '../../Backend/livestock_auctions/get_bids.php',
            data: { auction_id: auctionId },
            dataType: 'json',
            success: function(response) {
                const container = $(`#bidders-data-${auctionId}`);
                container.empty();

                if (response.status === 'success' && response.bids.length > 0) {
                    response.bids.forEach((bid, index) => {
                        container.append(`
                            <div class="bidders-row">
                                <div class="bidders-column">${index + 1}</div>
                                <div class="bidders-column">${bid.bidder_name}</div>
                                <div class="bidders-column">₱${parseFloat(bid.amount).toFixed(2)}</div>
                                <div class="bidders-column">${bid.bid_time}</div>
                            </div>
                        `);
                    });
                } else {
                    container.html('<p class="no-bidders">No bids yet for this auction.</p>');
                }
            },
            error: function() {
                const container = $(`#bidders-data-${auctionId}`);
                container.html('<p class="no-bidders">Error loading bid history.</p>');
            }
        });
    }

    // Load bid history when bid modal is opened
    $('.view-bids-btn').on('click', function() {
        const auctionId = $(this).closest('.modal-content')
                                .find('.bid-form')
                                .data('auction-id');
        loadBidHistory(auctionId);
    });

    // Handle saving auctions
    $('.save-button').on('click', function() {
        const button = $(this);
        const auctionId = button.data('auction-id');
        
        $.ajax({
            type: 'POST',
            url: '../../Backend/livestock_auctions/toggle_save_auction.php',
            data: { auction_id: auctionId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    button.toggleClass('saved');
                    const icon = button.find('i');
                    if (button.hasClass('saved')) {
                        icon.addClass('fas').removeClass('far');
                    } else {
                        icon.addClass('far').removeClass('fas');
                    }
                    alert(response.message);
                } else {
                    alert(response.message || 'Error saving auction');
                }
            },
            error: function() {
                alert('Error saving auction');
            }
        });
    });

    // Check saved status on page load
    function checkSavedAuctions() {
        $.ajax({
            type: 'GET',
            url: '../../Backend/livestock_auctions/get_saved_auctions.php',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    response.saved_auctions.forEach(auctionId => {
                        const button = $(`.save-button[data-auction-id="${auctionId}"]`);
                        button.addClass('saved');
                        button.find('i').addClass('fas').removeClass('far');
                    });
                }
            }
        });
    }

    // Initialize saved auctions on page load
    checkSavedAuctions();

    // Validate bid amount on input
    $('input[name="bid_amount"]').on('input', function() {
        const input = $(this);
        const minBid = parseFloat(input.attr('min'));
        const currentValue = parseFloat(input.val());

        if (currentValue < minBid) {
            input[0].setCustomValidity(`Bid must be at least ₱${minBid}`);
        } else {
            input[0].setCustomValidity('');
        }
    });
});