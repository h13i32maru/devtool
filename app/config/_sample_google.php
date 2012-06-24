<?php
require_once VENDOR_DIR . 'google-api-php-client/src/apiClient.php';
require_once VENDOR_DIR . 'google-api-php-client/src/contrib/apiOauth2Service.php';

define('GOOGLE_CLIENT_ID', 'your_google_client_id');
define('GOOGLE_CLIENT_SECRET', 'your_google_client_secret');
define('GOOGLE_REDIRECT_URI', APP_URL . 'callback_path');
define('GOOGLE_DEVELOPER_KEY', 'your_google_account');

function createGoogleClient()
{
    $client = new apiClient();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->setDeveloperKey(GOOGLE_DEVELOPER_KEY);

    return $client;
}
