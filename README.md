# Elementor to GoHighLevel (GHL) Integration

This integration sends Elementor Pro form submissions directly to GoHighLevel (GHL) using the Contacts API.

## Features

- Sends contact information from WordPress to GHL.
- Supports first name, last name, full name, email, and phone number.
- Validates email addresses before sending requests.
- Logs API errors for troubleshooting.
- Keeps API credentials outside the integration code.

## Requirements

- WordPress website
- Elementor Pro
- A form named `New Form`
- GHL Private Integration API token
- GHL Location ID
- PHP 7.4 or later

## Installation

### Step 1: Configure Elementor Form

Open your Elementor form and configure the following field IDs:

| Field | Field ID |
|---|---|
| Full Name | `fullname` |
| Email | `email` |
| Phone | `phone` |

Set the form name to exactly:

`New Form`

The field IDs must match the IDs used in the PHP code.

### Step 2: Configure GHL Credentials

Open your WordPress `wp-config.php` file.

Add the following lines before the WordPress stop-editing comment:

```php
define('GHL_PRIVATE_TOKEN', 'YOUR_PRIVATE_INTEGRATION_TOKEN');
define('GHL_LOCATION_ID', 'YOUR_GHL_LOCATION_ID');
```

Replace both placeholder values with your actual credentials.

**Security:** Never commit your API token or private credentials to GitHub.

### Step 3: Add the PHP Code

Copy the code from `elementor-ghl.php`.

You can add it to your WordPress site using either:

- A custom WordPress plugin (recommended for portability).
- The Code Snippets plugin, configured to run on the front end and backend as appropriate.

If using Code Snippets, omit the opening `<?php` tag when the editor requires PHP code without it.

### Step 4: Submit a Test Form

1. Open the live WordPress page containing the Elementor form.
2. Submit a test contact with a valid email address and phone number.
3. Open GoHighLevel.
4. Navigate to Contacts.
5. Search for the submitted email address.
6. Check the WordPress PHP error log if the contact is not created.

## Troubleshooting

### Contact not appearing in GHL

- Confirm the form name is `New Form`.
- Verify the Elementor field IDs.
- Confirm the GHL token and Location ID.
- Check that the token has the required contact permissions.
- Review the WordPress PHP error log for `[GHL]` messages.

### Invalid email

Submit a valid email address. The integration rejects invalid email addresses before calling the API.

### Missing API credentials

Ensure both `GHL_PRIVATE_TOKEN` and `GHL_LOCATION_ID` are defined in `wp-config.php`.

### Duplicate contacts

The current code does not explicitly prevent duplicate submissions. Test the integration with an existing email before using it in production.

## Security Notes

- Never store API tokens in public repositories.
- Do not commit `wp-config.php` to GitHub.
- Use a private repository if the code contains client-specific information.
- Restrict access to WordPress logs because they may contain API error details.

## How It Works

1. A visitor submits the Elementor form.
2. WordPress reads the submitted form fields.
3. The integration validates the email address.
4. WordPress sends the contact data to the GHL Contacts API.
5. The integration logs whether the request succeeded or failed.

## Maintainer

WordPress + GoHighLevel Integration

Version: 1.0.0
