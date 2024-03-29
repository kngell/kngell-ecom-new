<?php

declare(strict_types=1);

class LoginUserWithAjaxController extends AjaxController
{
    private VisitorsFromCache $visitors;
    private ValidatorFactory $vFactory;

    public function __construct(VisitorsFromCache $visitors, ValidatorFactory $vFactory)
    {
        $this->visitors = $visitors;
        $this->vFactory = $vFactory;
    }

    public function index()
    {
        $userData = $this->isValidRequest();
        $this->vFactory->create($this, $userData)->validate();
        /** @var AuthenticateUserManager */
        $model = $this->model(AuthenticateUserManager::class)->assign($userData);
        if (list($user, $number) = $model->authenticate()) {
            $this->isLoginAttempsValid($number);
            $this->isPasswordValid($user);
            $resp = $user->login($this->isRememberingLogin(), [
                'password' => $model->getEntity()->getPassword(),
            ]);
            if ($resp !== null) {
                $this->dispatcher->dispatch(new LoginEvent($resp->getEntity()));
                dd($_SESSION['user']);
                $this->jsonResponse(['result' => 'success', 'msg' => 'success']);
            }
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning text-warning', 'Something goes wrong! plase contact the administrator!')]);
        } else {
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning text-center', 'Your user account does not exist! Please register')]);
        }
    }

    public function rememberMeCheck(array $args = [])
    {
        /** @var AuthenticateUserManager */
        $model = $this->model(AuthenticateUserManager::class)->assign($this->isValidRequest());
        $resp = $model->rememberMeCheck($this->visitors);
        if (! empty($resp)) {
            $this->jsonResponse(['result' => 'success', 'msg' => $resp]);
        } else {
            $this->jsonResponse(['result' => 'error', 'msg' => '']);
        }
    }

    private function isRememberingLogin() : bool|string
    {
        $remember_me = $this->request->get('remember_me');
        if ($remember_me) {
            return $remember_me;
        }
        return false;
    }

    private function isLoginAttempsValid(int $nbLoginAttempt)
    {
        if ($nbLoginAttempt > MAX_LOGIN_ATTEMPTS_PER_HOUR) {
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('danger text-center', 'Too many login attempts in this hour')]);
        }
    }

    private function isPasswordValid(Object $user)
    {
        /** @var UserEntity */
        $en = $user->getEntity(); // en from end user
        /** @var UserEntity */
        $enFrmDb = current($user->get_results());
        if (! password_verify($en->getPassword(), $enFrmDb->getPassword())) {
            try {
                $this->model(LoginAttemptsManager::class)->assign([
                    'userId' => $enFrmDb->getUserId(),
                    'timestamp' => time(),
                    'ip' => $this->request->getServerVar('REMOTE_ADDR'),
                ])->save();
                $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('danger text-center', 'Your password is incorrect, Please try again!')]);
            } catch (\Throwable $th) {
                throw new DatabaseConnexionExceptions($th->getMessage(), $th->getCode());
            }
        }
    }
}