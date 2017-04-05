<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 14/02/17 09:54
 */

namespace Modules\User\Controllers;


use Modules\User\Forms\LoginForm;
use Modules\User\Forms\RegisterForm;
use Phact\Controller\Controller;
use Phact\Main\Phact;

class AuthController extends Controller
{
    public function login()
    {
        $form = new LoginForm();
        if ($this->request->getIsAjax() && $this->request->getIsPost()) {
            $data = [
                'state' => 'success'
            ];
            if ($form->fill($_POST) && $form->valid) {
                $form->login();
                $data['redirect'] = Phact::app()->router->url('personal:index');
            } else {
                $data = [
                    'state' => 'error',
                    'errors' => [
                        $form->classNameShort() => $form->getErrors()
                    ]
                ];
            }
            $this->jsonResponse($data);
            Phact::app()->end();
        }
        if ($this->request->getIsPost() && $form->fill($_POST) && $form->valid) {
            $form->login();
            $this->redirect("personal:index");
        }
        echo $this->render('user/login.tpl', [
            'login_form' => $form
        ]);
    }

    public function register()
    {
        $form = new RegisterForm();
        if ($this->request->getIsAjax() && $this->request->getIsPost()) {
            $data = [
                'state' => 'success'
            ];
            if ($form->fill($_POST) && $form->valid) {
                $form->save();
                $form->login();
                $data['redirect'] = Phact::app()->router->url('user:personal');
            } else {
                $data = [
                    'state' => 'error',
                    'errors' => [
                        $form->classNameShort() => $form->getErrors()
                    ]
                ];
            }
            $this->jsonResponse($data);
            Phact::app()->end();
        }
        if ($this->request->getIsPost() && $form->fill($_POST) && $form->valid) {
            $form->login();
            $this->redirect("user:register");
        }
        echo $this->render('user/register.tpl', [
            'register_form' => $form
        ]);
    }

    public function logout()
    {
        Phact::app()->auth->logout();
        $this->redirect('main:index');
    }
}