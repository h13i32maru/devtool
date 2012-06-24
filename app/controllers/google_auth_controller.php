<?php

/**
 * info
 * https://developers.google.com/accounts/docs/OAuth2
 * http://code.google.com/p/google-api-php-client/wiki/OAuth2
 */
require_once CONFIG_DIR . 'google.php';

class GoogleAuthController extends AppController
{
    public function index()
    {
        $client = createGoogleClient();
        $oauth2 = new apiOauth2Service($client);
        $authUrl = $client->createAuthUrl();
        $this->redirect($authUrl);
    }

    public function callback()
    {
        $code = Param::get('code');
        if (!$code) {
            $this->redirect('/');
            return;
        }

        $client = createGoogleClient();
        $oauth2 = new apiOauth2Service($client);

        $client->authenticate();
        $token = $client->getAccessToken();

        $user = $oauth2->userinfo->get();
        $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);

        $tmp = explode('@', $email);
        $domain = array_pop($tmp);
        if ($domain !== 'klab.jp') {
            $this->render('error/permission');
            return;
        }

        $user = User::create($email, $token);
        Session::setId($user->id);

        $this->redirect('/');
    }
}
