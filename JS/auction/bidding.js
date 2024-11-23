$(document).ready(function() {
    let eventSources = {};

    // Handle bid submission
    $('.bid-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const auctionId = form.data('auction-id');
        
        if (!auctionId) {
            console.error('No auction ID found for bid form');
            alert('Error: Could not identify auction');
            return;
        }

        const bidAmount = parseFloat(form.find('input[name="bid_amount"]').val());
        const submitButton = form.find('button[type="submit"]');

        // Don't disable submit button to allow consecutive bids
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    });
                    
                    // Update the current bid display with proper formatting
                    $(`.auction-card[data-auction-id="${auctionId}"] .current-bid, #modal${auctionId} .current-bid`)
                        .text('₱' + bidAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    
                    // Update the minimum bid value
                    const newMinBid = bidAmount + 1;
                    form.find('input[name="bid_amount"]').attr('min', newMinBid);
                    
                    // Refresh bid history
                    loadBidHistory(auctionId);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error placing bid'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error placing bid: ' + error
                });
            }
        });
    });

    // Function to load bid history with proper number formatting
    function loadBidHistory(auctionId) {
        if (!auctionId) {
            console.error('No auction ID provided for bid history');
            return;
        }

        const container = $(`#bidders-data-${auctionId}`);
        
        $.ajax({
            type: 'GET',
            url: '../../Backend/livestock_auctions/get_bids.php',
            data: { auction_id: auctionId },
            dataType: 'json',
            success: function(response) {
                container.empty();

                if (response.status === 'success' && response.bids && response.bids.length > 0) {
                    response.bids.forEach((bid, index) => {
                        const formattedAmount = parseFloat(bid.amount).toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        
                        container.append(`
                            <div class="bidders-row" data-bid-id="${bid.id}">
                                <div class="bidders-column">${index + 1}</div>
                                <div class="bidders-column">${$('<div>').text(bid.bidder_name).html()}</div>
                                <div class="bidders-column">₱${formattedAmount}</div>
                                <div class="bidders-column">${bid.bid_time}</div>
                            </div>
                        `);
                    });
                } else {
                    container.html('<p class="no-bidders">No bids yet for this auction.</p>');
                }
            },
            error: function(xhr, status, error) {
                container.html(`<p class="no-bidders">Error loading bid history: ${error}</p>`);
                console.error('Error loading bid history:', error);
            }
        });
    }

    // Function to update bid display with proper number formatting
    function updateBidDisplay(auctionId, data) {
        const container = $(`#bidders-data-${auctionId}`);
        const existingRows = container.find('.bidders-row').length;
        
        // Format the bid amount properly
        const formattedAmount = parseFloat(data.amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        // Create new bid row with escaped HTML and properly formatted amount
        const newRow = `
            <div class="bidders-row" data-bid-id="${data.id}">
                <div class="bidders-column">1</div>
                <div class="bidders-column">${$('<div>').text(data.bidder_name).html()}</div>
                <div class="bidders-column">₱${formattedAmount}</div>
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

        // Update current bid display in auction card and modal with proper formatting
        $(`.auction-card[data-auction-id="${auctionId}"] .current-bid, #modal${auctionId} .current-bid`)
            .text(`₱${formattedAmount}`);

        // Update minimum bid amount
        const minBid = parseFloat(data.amount) + 1;
        $(`#bid-amount-${auctionId}`).attr('min', minBid);
    }

    // Initialize SSE connection for an auction
    function initializeSSE(auctionId) {
        if (!auctionId) {
            console.error('No auction ID provided for SSE initialization');
            return;
        }

        console.log('Initializing SSE for auction:', auctionId);

        // Close existing connection if any
        if (eventSources[auctionId]) {
            console.log('Closing existing SSE connection for auction:', auctionId);
            eventSources[auctionId].close();
            delete eventSources[auctionId];
        }

        try {
            const evtSource = new EventSource(`../../Backend/livestock_auctions/get_bid_updates.php?auction_id=${auctionId}`);
            eventSources[auctionId] = evtSource;

            evtSource.onopen = function() {
                console.log('SSE connection opened for auction:', auctionId);
            };

            evtSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    if (data.error) {
                        console.error('SSE Data Error:', data.error);
                        return;
                    }

                    updateBidDisplay(auctionId, data);
                } catch (e) {
                    console.error('Error processing SSE message:', e);
                }
            };

            evtSource.onerror = function(error) {
                console.error('SSE Connection Error for auction', auctionId, ':', error);
                evtSource.close();
                delete eventSources[auctionId];
                
                // Attempt to reconnect after 5 seconds
                setTimeout(() => {
                    console.log('Attempting to reconnect SSE for auction:', auctionId);
                    initializeSSE(auctionId);
                }, 5000);
            };
        } catch (e) {
            console.error('Error creating SSE connection:', e);
        }
    }

    // Handle saving auctions with improved error handling and feedback
    $('.save-button').on('click', function() {
        const button = $(this);
        const auctionId = button.closest('.auction-card').data('auction-id');
        
        if (!auctionId) {
            console.error('No auction ID found for save button');
            return;
        }

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
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error saving auction'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error saving auction: ' + error
                });
            }
        });
    });

    // Check saved auctions on page load
    function checkSavedAuctions() {
        $.ajax({
            type: 'GET',
            url: '../../Backend/livestock_auctions/get_saved_auctions.php',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.saved_auctions) {
                    response.saved_auctions.forEach(auctionId => {
                        const button = $(`.auction-card[data-auction-id="${auctionId}"] .save-button`);
                        button.addClass('saved');
                        button.find('i').addClass('fas').removeClass('far');
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking saved auctions:', error);
            }
        });
    }

    // Validate bid amount on input
    $('input[name="bid_amount"]').on('input', function() {
        const input = $(this);
        const minBid = parseFloat(input.attr('min'));
        const currentValue = parseFloat(input.val());

        if (isNaN(currentValue)) {
            input[0].setCustomValidity('Please enter a valid number');
        } else if (currentValue < minBid) {
            input[0].setCustomValidity(`Bid must be at least ₱${minBid.toFixed(2)}`);
        } else {
            input[0].setCustomValidity('');
        }
    });

    // Load initial bid history and start SSE when viewing bids
    $('.view-bids-btn').on('click', function() {
        const modalContent = $(this).closest('.modal-content');
        const auctionId = modalContent.find('.bid-form').data('auction-id') || 
                         modalContent.closest('.modal').attr('id').replace('modal', '');
        
        if (!auctionId) {
            console.error('Could not determine auction ID');
            return;
        }

        loadBidHistory(auctionId);
        initializeSSE(auctionId);
    });

    // Clean up SSE connections when closing modals
    $('.close').on('click', function() {
        const modalId = $(this).closest('.modal').attr('id');
        if (!modalId) return;

        const auctionId = modalId.replace('biddingModal', '').replace('modal', '');
        
        if (eventSources[auctionId]) {
            console.log('Closing SSE connection for auction:', auctionId);
            eventSources[auctionId].close();
            delete eventSources[auctionId];
        }
    });

    // Initialize saved auctions check on page load
    checkSavedAuctions();

    // Clean up all SSE connections before page unload
    $(window).on('beforeunload', function() {
        Object.values(eventSources).forEach(source => source.close());
        eventSources = {};
    });
});