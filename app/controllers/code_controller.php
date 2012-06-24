<?php

class CodeController extends AppController
{
    public function index()
    {
        $user = $this->start();

        $code_packs = CodePack::getAll($user);

        $this->set(get_defined_vars());
    }

    public function create()
    {
        $user = $this->start();

        $title = Param::get('title', '');
        $description = Param::get('description', '');
        $classes = Param::get('class');
        $codes = Param::get('code');

        $codes_param = array();
        for($i = 0; $i < count($classes); $i++) {
            $codes_param[] = array('class' => $classes[$i], 'code' => $codes[$i]);
        }

        $code_pack = CodePack::create($user, $title, $description, $codes_param);
        $this->redirect('code/show', array('p' => $code_pack->path));
    }

    public function show()
    {
        $user = $this->start();

        $path = Param::get('p');

        $code_packs = CodePack::getAll($user);
        $code_pack = CodePack::get($user, $path);
        $codes = $code_pack->getCodes();

        $this->set(get_defined_vars());
    }

    public function edit()
    {
        $user = $this->start();

        $path = Param::get('p');

        $code_packs = CodePack::getAll($user);
        $code_pack = CodePack::get($user, $path);
        $codes = $code_pack->getCodes();

        $this->set(get_defined_vars());
    }

    public function exec_edit()
    {
        $user = $this->start();

        $path = Param::get('p');
        $title = Param::get('title', '');
        $description = Param::get('description', '');
        $code_ids = Param::get('id');
        $classes = Param::get('class');
        $codes = Param::get('code');

        $codes_param = array();

        for($i = 0; $i < count($classes); $i++) {
            $id = null;
            if (isset($code_ids[$i])) {
                $id = $code_ids[$i];
            }
            $codes_param[] = array('id' => $id, 'class' => $classes[$i], 'code' => $codes[$i]);
        }

        $code_pack = CodePack::get($user, $path);
        $code_pack->updateCodes($codes_param);
        $code_pack->update($title, $description);

        $this->redirect('code/show', array('p' => $path));
    }

    public function delete()
    {
        $user = $this->start();

        $path = Param::get('p');
        $code_pack = CodePack::get($user, $path);

        $code_pack->delete();

        $this->redirect('code/index');
    }
}
