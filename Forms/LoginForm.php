<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 07/08/16 16:17
 */

namespace Modules\User\Forms;

use Phact\Form\Fields\EmailField;
use Phact\Form\Fields\PasswordField;
use Phact\Form\Form;
use Phact\Interfaces\AuthInterface;
use Phact\Translate\Translator;

class LoginForm extends Form
{
    use Translator;

    /**
     * @var AuthInterface
     */
    protected $_auth;

    public function __construct(array $config = [], AuthInterface $auth)
    {
        parent::__construct($config);
        $this->_auth = $auth;
    }

    public function getFields()
    {
        return [
            'email' => [
                'class' => EmailField::class,
                'label' => self::t('User.main', 'E-mail'),
                'required' => true
            ],
            'password' => [
                'class' => PasswordField::class,
                'label' => self::t('User.main', 'Password'),
                'required' => true
            ]
        ];
    }

    public function clean($attributes)
    {
        $email = $attributes['email'];
        $password = $attributes['password'];

        $user = $this->_auth->findUserByLogin($email);
        if ($user) {
            if (!$this->_auth->verifyPassword($user, $password)) {
                $this->addError('password', self::t('User.main', 'Wrong password'));
            }
        } else {
            $this->addError('email', self::t('User.main', 'There is no users with current e-mail'));
        }
    }

    public function login()
    {
        $data = $this->getAttributes();
        $user = $this->getUser($data['email']);
        if ($user) {
            $this->_auth->login($user);
        }
    }

    public function getUser($email)
    {
        return $this->_auth->findUserByLogin($email);
    }
}