<?php

class TopController extends AppController
{
    public function index()
    {
        $user = $this->start();

        $this->redirect('code/index');

        $this->set(get_defined_vars());
    }

    public function auth()
    {
    }

    public function signout()
    {
        Session::unsetId();

        $this->redirect('top/auth');
    }
}
