function startCountdown() {
    const countdownElements = document.querySelectorAll('[id^="countdown"]');
    
    countdownElements.forEach(element => {
        const endTime = new Date(element.dataset.endTime).getTime();
        const startTime = new Date(element.dataset.startTime).getTime();
        let countdownInterval; // Declare the interval variable in the proper scope
        
        const updateCountdown = () => {
            const now = new Date().getTime();
            
            // If auction hasn't started yet
            if (now < startTime) {
                const timeToStart = startTime - now;
                const startDays = Math.floor(timeToStart / (1000 * 60 * 60 * 24));
                const startHours = Math.floor((timeToStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const startMinutes = Math.floor((timeToStart % (1000 * 60 * 60)) / (1000 * 60));
                const startSeconds = Math.floor((timeToStart % (1000 * 60)) / 1000);
                
                element.textContent = `Starts in ${startDays}d ${startHours}h ${startMinutes}m ${startSeconds}s`;
                return;
            }
            
            // If auction is ongoing
            if (now < endTime) {
                const timeLeft = endTime - now;
                
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                
                element.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            } 
            // If auction has ended
            else {
                element.textContent = 'Over';
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }
            }
        };
        
        // Initial call to set correct state immediately
        updateCountdown();
        
        // Set up interval to update countdown
        countdownInterval = setInterval(updateCountdown, 1000);
        
        // Clean up interval when the element is removed from the DOM
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.removedNodes.forEach((node) => {
                    if (node === element) {
                        clearInterval(countdownInterval);
                        observer.disconnect();
                    }
                });
            });
        });
        
        observer.observe(element.parentNode, { childList: true });
    });
}

// Run the countdown when the page loads
document.addEventListener('DOMContentLoaded', startCountdown);