<?php

class TopController extends AppController
{
    /**
     * トップ画面を表示する。
     * セッションの有無で画面の出しわけを行う
     */
    public function index()
    {
        $id = Session::getId();
        if (!$id) {
            $this->render('top/auth');
            return;
        }

        $user = $this->start();

        $this->redirect('code/index');

        $this->set(get_defined_vars());
    }

    /**
     * セッションを削除してサインアウトさせる
     */
    public function signout()
    {
        Session::unsetId();

        $this->redirect('top/index');
    }
}
