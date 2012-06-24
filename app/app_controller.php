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
    
    public function start()
    {
        $id = Session::getId();
        if (!$id) {
            $this->redirect('top/auth');
        }
        $user = User::get($id);
        return $user;
    }
}
