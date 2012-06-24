<?php

class DummyImageController extends AppController
{
    public function index()
    {
        $user = $this->start();
        $this->set(get_defined_vars());
        $this->render('error/sorry');
    }
}
