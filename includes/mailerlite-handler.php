<?php
/**
 * MailerLite Integration Handler
 * 
 * This file handles the MailerLite API integration for email subscriptions
 * 
 * @package footnotes-made-easy
 * @since 4.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles for MailerLite form
 */
function footnotes_mailerlite_enqueue_scripts($hook) {
    // Only load on the plugin's settings page
    if ($hook !== 'settings_page_footnotes-options-page') {
        return;
    }
    
    // Enqueue the JavaScript file
    wp_enqueue_script(
        'mailerlite-form',
        plugin_dir_url(dirname(__FILE__)) . 'assets/js/mailerlite-form.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Localize script with AJAX URL and nonce
    wp_localize_script('mailerlite-form', 'mailerliteAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mailerlite_subscribe_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'footnotes_mailerlite_enqueue_scripts');

/**
 * Handle AJAX subscription request
 */
function footnotes_mailerlite_subscribe() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mailerlite_subscribe_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.'
        ));
        return;
    }
    
    // Sanitize inputs
    $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    
    // Validate inputs
    if (empty($first_name) || empty($email)) {
        wp_send_json_error(array(
            'message' => 'Please provide both name and email.'
        ));
        return;
    }
    
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'Please provide a valid email address.'
        ));
        return;
    }
    
    // MailerLite API Configuration - UPDATED WITH YOUR CREDENTIALS
    $api_key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiMzYyNjM0NGEwN2Y5MTBhM2ZlMmVkZTAxMTIxOTUwMDcxNjQwNGE4NmVlMDY4N2U1OTk2YTU0NDAzZjAxN2M3Y2Y0ZWMyM2M0MmVjNmQ5MGYiLCJpYXQiOjE3NjEyNTI0MjUuODMyMDYyLCJuYmYiOjE3NjEyNTI0MjUuODMyMDY1LCJleHAiOjQ5MTY5MjYwMjUuODI3MzY1LCJzdWIiOiIxODY2MzY5Iiwic2NvcGVzIjpbXX0.kwZzXfmoGD6lfIyUULbLjc6Le2ivxqvfAGaH_hw6_K7BhFhzccswRK7YJE3KTlkuOCLx2nY6rm4Axo-vChoAyMMoRwZJ3JS0uwFGbHdXXz3XaNw0igrpm5snvhdtGtSb4-eULPAGy4pZhmg3Eek-8NAIhRuw2v7MkkAzTat6tVo7tAPXLBfSaylGuMSTq6ubeWYeyvOuHsGgg8rFM8HmBbUQ1UZlGElXzERhi7Z5UWfdtVpWA6Mh_8kIWa0TjKxzRk5QpAD9fE_ZL3lC2_uLnbz3uF0CFBgqirq-uesTDCIBtsvYiKLv3VC1rVWmSXVE71AW-MLtMi3r_ONO-8LPGCjDyoFbqwjr8ZhcPiIiEL-JKgLl-VmVy_9D80lTw0Yr885N62lXVRcrrVwrDRyYSGpOD8wS-aigfLO9m-XBhyGVojvwDDo3wXkGjwYvcMnJuPAyfXXkmE9ljmNAab2wGHVG29wggp0IMBeW6AfFA2308VRsadJ8v0flO23T2j4P7gRpIeFDCFsNhDELpIHUIzkgzJx4Zl5didHSJaUc_xVbqQiduOpN6YrnajmO0ouR-jVI7iiDnIT8rIgAmTaZEeb5JC9EGO9xQakIgh9dZhSH8NbqDMxIty_8nKA0XkgcTxbFo_EUOfYB4tTN2lQp2QV9F1McTpjC-Xz_SB13T3U';
    $group_id = '168073379120678542';
    
    // Prepare subscriber data
    $subscriber_data = array(
        'email' => $email,
        'fields' => array(
            'name' => $first_name
        ),
        'groups' => array($group_id)
    );
    
    // Make API request to MailerLite
    $response = wp_remote_post('https://connect.mailerlite.com/api/subscribers', array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
            'Accept' => 'application/json'
        ),
        'body' => json_encode($subscriber_data),
        'timeout' => 30,
        'sslverify' => true
    ));
    
    // Check for errors
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => 'Connection error. Please try again later.'
        ));
        return;
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    
    // Handle response
    if ($response_code === 200 || $response_code === 201) {
        wp_send_json_success(array(
            'message' => 'Thank you for subscribing! Please check your email to confirm.'
        ));
    } elseif ($response_code === 422 && isset($response_body['message'])) {
        // Subscriber might already exist or validation error
        if (stripos($response_body['message'], 'already') !== false || 
            (isset($response_body['errors']) && isset($response_body['errors']['email']))) {
            wp_send_json_error(array(
                'message' => 'This email is already subscribed.'
            ));
        } else {
            wp_send_json_error(array(
                'message' => isset($response_body['message']) ? $response_body['message'] : 'Subscription failed. Please try again.'
            ));
        }
    } elseif ($response_code === 400 && isset($response_body['message'])) {
        wp_send_json_error(array(
            'message' => $response_body['message']
        ));
    } else {
        // Log error for debugging (optional)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('MailerLite API Error: ' . print_r($response_body, true));
        }
        
        wp_send_json_error(array(
            'message' => 'Subscription failed. Please try again or contact support.'
        ));
    }
}
add_action('wp_ajax_mailerlite_subscribe', 'footnotes_mailerlite_subscribe');

/**
 * Alternative: Using MailerLite Embedded Form
 * 
 * If you prefer to use MailerLite's embedded form instead of the custom form,
 * uncomment the function below and add your account ID:
 */
/*
function footnotes_mailerlite_embed_script() {
    // Only load on plugin settings page
    $screen = get_current_screen();
    if ($screen && $screen->id !== 'settings_page_footnotes-options-page') {
        return;
    }
    ?>
    <script>
        (function(w,d,e,u,f,l,n){w[f]=w[f]||function(){(w[f].q=w[f].q||[])
        .push(arguments);},l=d.createElement(e),l.async=1,l.src=u,
        n=d.getElementsByTagName(e)[0],n.parentNode.insertBefore(l,n);})
        (window,document,'script','https://assets.mailerlite.com/js/universal.js','ml');
        ml('account', 'YOUR_ACCOUNT_ID'); // Replace with your MailerLite account ID
    </script>
    <?php
}
add_action('admin_footer', 'footnotes_mailerlite_embed_script');
*/

/**
 * Get MailerLite Groups (Helper function for admin)
 * 
 * This function can be used to fetch available groups from MailerLite
 * Useful for displaying group options in settings
 */
function footnotes_get_mailerlite_groups() {
    $api_key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiMzYyNjM0NGEwN2Y5MTBhM2ZlMmVkZTAxMTIxOTUwMDcxNjQwNGE4NmVlMDY4N2U1OTk2YTU0NDAzZjAxN2M3Y2Y0ZWMyM2M0MmVjNmQ5MGYiLCJpYXQiOjE3NjEyNTI0MjUuODMyMDYyLCJuYmYiOjE3NjEyNTI0MjUuODMyMDY1LCJleHAiOjQ5MTY5MjYwMjUuODI3MzY1LCJzdWIiOiIxODY2MzY5Iiwic2NvcGVzIjpbXX0.kwZzXfmoGD6lfIyUULbLjc6Le2ivxqvfAGaH_hw6_K7BhFhzccswRK7YJE3KTlkuOCLx2nY6rm4Axo-vChoAyMMoRwZJ3JS0uwFGbHdXXz3XaNw0igrpm5snvhdtGtSb4-eULPAGy4pZhmg3Eek-8NAIhRuw2v7MkkAzTat6tVo7tAPXLBfSaylGuMSTq6ubeWYeyvOuHsGgg8rFM8HmBbUQ1UZlGElXzERhi7Z5UWfdtVpWA6Mh_8kIWa0TjKxzRk5QpAD9fE_ZL3lC2_uLnbz3uF0CFBgqirq-uesTDCIBtsvYiKLv3VC1rVWmSXVE71AW-MLtMi3r_ONO-8LPGCjDyoFbqwjr8ZhcPiIiEL-JKgLl-VmVy_9D80lTw0Yr885N62lXVRcrrVwrDRyYSGpOD8wS-aigfLO9m-XBhyGVojvwDDo3wXkGjwYvcMnJuPAyfXXkmE9ljmNAab2wGHVG29wggp0IMBeW6AfFA2308VRsadJ8v0flO23T2j4P7gRpIeFDCFsNhDELpIHUIzkgzJx4Zl5didHSJaUc_xVbqQiduOpN6YrnajmO0ouR-jVI7iiDnIT8rIgAmTaZEeb5JC9EGO9xQakIgh9dZhSH8NbqDMxIty_8nKA0XkgcTxbFo_EUOfYB4tTN2lQp2QV9F1McTpjC-Xz_SB13T3U';
    
    $response = wp_remote_get('https://connect.mailerlite.com/api/groups', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ),
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        return array();
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    
    if ($response_code === 200) {
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return isset($body['data']) ? $body['data'] : array();
    }
    
    return array();
}