<?php

class AppController extends Controller
{
    public $default_view_class = 'AppTwigView';

    public function dispatchAction()
    {
        try{
            try {
                parent::dispatchAction();
            } catch(Exception $e) {
                $log = sprintf('Exception:(%s)%s@%s %s', Session::getId(), $e->getFile(), $e->getLine(), $e->getMessage());
                $this->set('user', $this->start());
                error_log($log);
                throw $e;
            }
        } catch (PDOException $e) {
            $this->set('exception', $e);
            $this->render('error/database');
        } catch (RecordNotFoundException $e) {
            $this->render('error/not_found');
        } catch (PermissionDeniedException $e) {
            $this->render('error/permission');
        } catch (Exception $e) {
            $this->set('exception', $e);
            $this->render('error/unexpected');
        }
    }

    /**
     * 指定のURLへリダイレクトする
     * redirect('hoge/foo', array('param' => $value);
     * redirect('http://example.com');
     */
    public function redirect($url, $params = array())
    {
        $query = http_build_query($params);
        if (strlen($query) > 0) {
            $query = '?' . $query;
        }
        if ($url === '') {
            $url = substr($_SERVER['REQUEST_URI'], strlen(APP_BASE_PATH));
            $url = APP_URL . urlencode($url);
        } elseif ($url === '/') {
            $url = APP_URL . $query;
        } elseif (strpos($url, 'http') === 0) {
            $url = $url . $query;
        } else {
            $url = APP_URL . $url . $query;
        }
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * セッションを開始する
     * 戻り値としてUserオブジェクトを取得する
     */
    public function start()
    {
        $id = Session::getId();
        if (!$id) {
            $url = $this->getUrl();
            Session::set('redirect', $url);
            $this->redirect('top/index');
        }
        $user = User::get($id);
        return $user;
    }

    /**
     * 現在のURLを取得する
     */
    public function getUrl()
    {
        $query = preg_replace('/dc_action=.*?&/', '', $_SERVER['QUERY_STRING']);
        $url = APP_URL . $this->name . '/' . $this->action . '?' . $query;
        return $url;
    }
}
