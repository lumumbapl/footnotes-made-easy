/**
 * MailerLite Subscription Form Handler
 * Handles form submission for email subscription
 */

(function($) {
    'use strict';

    // Wait for DOM to be ready
    $(document).ready(function() {
        
        // Handle form submission
        $('#mailerlite-subscribe-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $button = $form.find('.mailerlite-submit');
            var $message = $form.find('.mailerlite-message');
            var firstName = $('#ml_firstname').val().trim();
            var email = $('#ml_email').val().trim();
            
            // Basic validation
            if (!firstName || !email) {
                showMessage('error', 'Please fill in all fields.');
                return;
            }
            
            // Email validation
            if (!isValidEmail(email)) {
                showMessage('error', 'Please enter a valid email address.');
                return;
            }
            
            // Disable button and show loading state
            $button.prop('disabled', true).text('Subscribing...');
            $message.hide().removeClass('success error');
            
            // Prepare data for AJAX request
            var formData = {
                action: 'mailerlite_subscribe',
                nonce: mailerliteAjax.nonce, // This will be localized from PHP
                first_name: firstName,
                email: email
            };
            
            // Send AJAX request
            $.ajax({
                url: mailerliteAjax.ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.data.message || 'Thank you for subscribing!');
                        $form[0].reset(); // Clear form
                    } else {
                        showMessage('error', response.data.message || 'Something went wrong. Please try again.');
                    }
                },
                error: function() {
                    showMessage('error', 'Network error. Please check your connection and try again.');
                },
                complete: function() {
                    // Re-enable button
                    $button.prop('disabled', false).text('Subscribe Now');
                }
            });
        });
        
        // Helper function to validate email
        function isValidEmail(email) {
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
        
        // Helper function to show messages
        function showMessage(type, text) {
            var $message = $('.mailerlite-message');
            $message
                .removeClass('success error')
                .addClass(type)
                .text(text)
                .slideDown(300);
            
            // Auto-hide success message after 5 seconds
            if (type === 'success') {
                setTimeout(function() {
                    $message.slideUp(300);
                }, 5000);
            }
        }
        
        // Clear error message on input
        $('#ml_firstname, #ml_email').on('input', function() {
            var $message = $('.mailerlite-message');
            if ($message.hasClass('error')) {
                $message.slideUp(300);
            }
        });
        
    });
    
})(jQuery);