<?php

class GoogleAuthController extends AppController
{
    /**
     * Googleアカウントの認証画面へリダイレクトする
     */
    public function index()
    {
        $url = GoogleAuth::getAuthUrl();
        $this->redirect($url);
    }

    /**
     * 認証画面からのコールバックを処理する
     */
    public function callback()
    {
        $code = Param::get('code');
        try {
            $result = GoogleAuth::verify($code);
        } catch (AuthDeniedException $e) {
            $this->redirect('/');
            return;
        } catch (PermissionDeniedException $e) {
            $this->render('error/permission');
            return;
        }

        $user = User::create($result['identity'], $result['token']);
        Session::setId($user->id);

        $url = Session::get('redirect', '/');
        Session::delete('redirect');
        $this->redirect($url);
    }
}
