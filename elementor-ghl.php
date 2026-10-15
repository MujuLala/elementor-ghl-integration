<?php
/**
 * Plugin Name: Elementor to GHL Integration
 * Description: Sends Elementor form submissions to GoHighLevel.
 * Version: 1.0.0
 * Requires PHP: 7.4
 */
add_action('elementor_pro/forms/new_record', function ($record, $handler) {

    if ($record->get_form_settings('form_name') !== 'New Form') {
        return;
    }

    $fields = $record->get('fields');

    $email = sanitize_email($fields['email']['value'] ?? '');
    $full_name = sanitize_text_field($fields['fullname']['value'] ?? '');
    $phone = sanitize_text_field($fields['phone']['value'] ?? '');

    if (!is_email($email)) {
        error_log('[GHL] Invalid email submitted.');
        return;
    }

    if (!defined('GHL_PRIVATE_TOKEN') || !defined('GHL_LOCATION_ID')) {
        error_log('[GHL] Missing API credentials.');
        return;
    }

    $parts = preg_split('/\s+/', trim($full_name), 2);

    $body = [
        'locationId' => GHL_LOCATION_ID,
        'email'      => $email,
        'phone'      => $phone,
        'firstName'  => $parts[0] ?? '',
        'lastName'   => $parts[1] ?? '',
        'name'       => $full_name,
        'source'     => 'WordPress Elementor',
    ];

    $response = wp_remote_post(
        'https://services.leadconnectorhq.com/contacts/',
        [
            'timeout' => 20,
            'headers' => [
                'Authorization' => 'Bearer ' . GHL_PRIVATE_TOKEN,
                'Version'       => 'v3',
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'body' => wp_json_encode($body),
        ]
    );

    if (is_wp_error($response)) {
        error_log('[GHL] ' . $response->get_error_message());
        return;
    }

    $status = wp_remote_retrieve_response_code($response);

    if ($status >= 200 && $status < 300) {
        error_log('[GHL] Contact request successful.');
    } else {
        error_log(
            '[GHL] HTTP ' . $status . ': ' .
            wp_remote_retrieve_body($response)
        );
    }

}, 10, 2);
