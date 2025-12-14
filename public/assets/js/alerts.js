/**
 * Auto-hide alerts after 10 seconds
 */
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Select all alert messages
        const alerts = document.querySelectorAll('.alert-dismissible');
        
        if (alerts.length > 0) {
            alerts.forEach(function(alert) {
                // Auto-hide after 10 seconds (10000 milliseconds)
                setTimeout(function() {
                    // Use Bootstrap's fade out effect
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    
                    // Remove from DOM after animation
                    setTimeout(function() {
                        alert.remove();
                    }, 150);
                }, 10000);
            });
        }
    });
})();
