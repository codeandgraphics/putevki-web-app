<?php

namespace Backend\Controllers;

use Backend\Models\Users;

class UsersController extends ControllerBase
{
    private function _registerSession($user)
    {
        $this->session->set('auth', [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'company' => $user->company,
            'email' => $user->email,
            'imageUrl' => $user->imageUrl
        ]);
    }

    public function indexAction()
    {
    }

    public function loginAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = Users::findFirst('email="' . $email . '"');

            if ($user) {
                if ($this->security->checkHash($password, $user->password)) {
                    $this->_registerSession($user);
                    $this->flashSession->success('Вы успешно вошли в систему');
                    return $this->response->redirect(
                        $this->backendUrl->get('')
                    );
                }
                $this->flashSession->error('Неправильный пароль');
            } else {
                $this->flashSession->error('Нет такого пользователя');
            }
        }
    }

    public function logoutAction()
    {
        $this->session->destroy();
        $this->flashSession->success('Вы вышли из системы');

        return $this->response->redirect($this->backendUrl->get(''));
    }

    public function registerAction()
    {
        if ($this->request->isPost()) {
            $user = new Users();

            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user->name = $name;
            $user->email = $email;
            $user->password = $this->security->hash($password);
            $user->company = 'Путёвки.ру';

            $success = $user->save();

            if ($success) {
                $this->flashSession->success('Вы успешно зарегистрировались!');
            } else {
                foreach ($user->getMessages() as $message) {
                    $this->flashSession->error($message->getMessage());
                }
            }
        }
    }
}
