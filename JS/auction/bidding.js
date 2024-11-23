$(document).ready(function() {
    let eventSources = {};

    // Handle bid submission
    $('.bid-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const auctionId = form.data('auction-id');
        const bidAmount = form.find('input[name="bid_amount"]').val();
        const submitButton = form.find('button[type="submit"]');

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
                    form.find('input[name="bid_amount"]').val('');
                    alert(response.message);
                    
                    // Update the current bid display in the auction card
                    const auctionCard = form.closest('.modal').prev('.auction-card');
                    auctionCard.find('.current-bid').text('₱' + parseFloat(bidAmount).toFixed(2));
                    
                    // Update the minimum bid value
                    const newMinBid = parseFloat(bidAmount) + 1;
                    form.find('input[name="bid_amount"]').attr('min', newMinBid);
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

    // Initialize SSE connection for an auction
    function initializeSSE(auctionId) {
        if (eventSources[auctionId]) {
            eventSources[auctionId].close();
        }

        const evtSource = new EventSource(`../../Backend/livestock_auctions/get_bid_updates.php?auction_id=${auctionId}`);
        eventSources[auctionId] = evtSource;

        evtSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            if (data.error) {
                console.error('SSE Error:', data.error);
                return;
            }

            // Update bid history
            const container = $(`#bidders-data-${auctionId}`);
            const existingRows = container.find('.bidders-row').length;
            
            const newRow = `
                <div class="bidders-row" data-bid-id="${data.id}">
                    <div class="bidders-column">${existingRows + 1}</div>
                    <div class="bidders-column">${data.bidder_name}</div>
                    <div class="bidders-column">₱${data.amount}</div>
                    <div class="bidders-column">${data.bid_time}</div>
                </div>
            `;

            if (existingRows === 0) {
                container.html(newRow);
            } else {
                container.prepend(newRow);
                // Update all rank numbers
                container.find('.bidders-row').each(function(index) {
                    $(this).find('.bidders-column').first().text(index + 1);
                });
            }

            // Update current bid display in auction card and modal
            const currentBidElements = $(`.auction-card[data-auction-id="${auctionId}"] .current-bid, #modal${auctionId} .current-bid`);
            currentBidElements.text(`₱${data.amount}`);

            // Update minimum bid amount
            const minBid = parseFloat(data.amount) + 1;
            $(`#bid-amount-${auctionId}`).attr('min', minBid);
        };

        evtSource.onerror = function(error) {
            console.error('SSE Error:', error);
            evtSource.close();
            delete eventSources[auctionId];
        };
    }

    // Load initial bid history and start SSE when viewing bids
    $('.view-bids-btn').on('click', function() {
        const auctionId = $(this).closest('.modal-content')
                                .find('.bid-form')
                                .data('auction-id');
        
        // Load initial bid history
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
                            <div class="bidders-row" data-bid-id="${bid.id}">
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

                // Initialize SSE after loading initial data
                initializeSSE(auctionId);
            },
            error: function() {
                const container = $(`#bidders-data-${auctionId}`);
                container.html('<p class="no-bidders">Error loading bid history.</p>');
            }
        });
    });

    // Clean up SSE connections when closing modals
    $('.close').on('click', function() {
        const modalId = $(this).closest('.modal').attr('id');
        const auctionId = modalId.replace('biddingModal', '');
        
        if (eventSources[auctionId]) {
            eventSources[auctionId].close();
            delete eventSources[auctionId];
        }
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