<?php
/**
 * info
 * https://developers.google.com/accounts/docs/OAuth2
 * http://code.google.com/p/google-api-php-client/wiki/OAuth2
 */

require_once VENDOR_DIR.'/google/apiclient/src/Google/Client.php';
require_once VENDOR_DIR.'/google/apiclient/src/Google/Service/Analytics.php';
require_once VENDOR_DIR.'/google/apiclient/src/Google/Service/Oauth2.php';

class GoogleAuth extends AppModel
{

    /**
     * GoogleのAPIクライアントを取得する
     */
    public static function getClient()
    {
        $client = new Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->setDeveloperKey(GOOGLE_DEVELOPER_KEY);
        $client->setApprovalPrompt('auto');
        $client->setScopes(array('email','profile','openid'));

        return $client;
    }

    /**
     * 認証するためのGoogleのURLを取得する
     * このURLにリダイレクトすることでGoogleアカウントの認証画面を表示する
     */
    public static function getAuthUrl()
    {
        $client = self::getClient();
        $oauth2 = new Google_Service_Oauth2($client);
        return $client->createAuthUrl();
    }

    /**
     * Googleアカウントの認証結果を確認する
     * ユーザが認証を拒否した場合はAuthDeniedExceptionを発生させる
     * 認証は成功しても指定されたドメイン|メールアドレスではない場合PermissionDeniedExceptionを発生させる
     */
    public static function verify($code)
    {
        if (!$code) {
            throw new AuthDeniedException();
        }

        $client = self::getClient();
        $oauth2 = new Google_Service_Oauth2($client);

        $client->authenticate($code);
        $token = $client->getAccessToken();

        $user = $oauth2->userinfo->get();

        if (!self::isAllowed($user)) {
            throw new PermissionDeniedException();
        }

        $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
        return array('identity' => $email, 'token' => $token);
    }

    /**
     * 指定されたドメイン|メールアドレスであるかを確認する
     */
    public static function isAllowed($user)
    {
        $email  = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
        $tmp    = explode('@', $email);
        $domain = array_pop($tmp);

        $allow_domains = explode(',', GOOGLE_ALLOW_DOMAINS);
        foreach ($allow_domains as $v) {
            if ($domain === trim($v)) {
                return true;
            }
        }

        $allow_emails = explode(',', GOOGLE_ALLOW_EMAILS);
        foreach ($allow_emails as $v) {
            if ($email === trim($v)) {
                return true;
            }
        }

        return false;
    }
}
