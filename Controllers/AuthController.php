<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 14/02/17 09:54
 */

namespace Modules\User\Controllers;


use Modules\User\Components\AuthUserInterface;
use Modules\User\Forms\LoginForm;
use Modules\User\Forms\RegisterForm;
use Phact\Controller\Controller;
use Phact\Interfaces\AuthInterface;
use Phact\Request\HttpRequestInterface;
use Phact\Router\RouterInterface;
use Phact\Template\RendererInterface;

class AuthController extends Controller
{
    /**
     * @var AuthUserInterface
     */
    protected $_auth;

    /**
     * @var RouterInterface
     */
    protected $_router;

    public function __construct(HttpRequestInterface $request, AuthUserInterface $auth, RouterInterface $router, RendererInterface $renderer = null)
    {
        $this->_auth = $auth;
        $this->_router = $router;
        parent::__construct($request, $renderer);
    }

    public function login()
    {
        $form = new LoginForm($this->_auth, []);
        if ($this->request->getIsAjax() && $this->request->getIsPost()) {
            $data = [
                'state' => 'success'
            ];
            if ($form->fill($_POST) && $form->valid) {
                $form->login();
                $data['redirect'] = $this->_router->url('personal:index');
            } else {
                $data = [
                    'state' => 'error',
                    'errors' => [
                        $form->classNameShort() => $form->getErrors()
                    ]
                ];
            }
            $this->jsonResponse($data);
            exit();
        }
        if ($this->request->getIsPost() && $form->fill($_POST) && $form->valid) {
            $form->login();
            $this->redirect($this->_auth->getAfterLoginRoute());
        }
        echo $this->render('user/login.tpl', [
            'login_form' => $form
        ]);
    }

    public function register()
    {
        $form = new RegisterForm($this->_auth, []);
        if ($this->request->getIsAjax() && $this->request->getIsPost()) {
            $data = [
                'state' => 'success'
            ];
            if ($form->fill($_POST) && $form->valid) {
                $form->save();
                $form->login();
                $data['redirect'] = $this->_router->url('user:personal');
            } else {
                $data = [
                    'state' => 'error',
                    'errors' => [
                        $form->classNameShort() => $form->getErrors()
                    ]
                ];
            }
            $this->jsonResponse($data);
            exit();
        }
        if ($this->request->getIsPost() && $form->fill($_POST) && $form->valid) {
            $form->save();
            $form->login();
            $this->redirect("user:register");
        }
        echo $this->render('user/register.tpl', [
            'register_form' => $form
        ]);
    }

    public function logout()
    {
        $this->_auth->logout();
        $this->redirect($this->_auth->getAfterLogoutRoute());
    }
}