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
 * @date 07/08/16 16:17
 */

namespace Modules\User\Forms;

use Modules\User\Models\User;
use Modules\User\UserModule;
use Modules\User\Validators\PasswordValidator;
use Phact\Form\Fields\EmailField;
use Phact\Form\Fields\PasswordField;
use Phact\Form\Form;
use Phact\Main\Phact;

class RegisterForm extends Form
{
    protected $_user;

    public function getFields()
    {
        return [
            'email' => [
                'class' => EmailField::class,
                'label' => 'E-mail',
                'required' => true,
                'attributes' => [
                    'placeholder' => 'E-mail'
                ]
            ],
            'password' => [
                'class' => PasswordField::class,
                'label' => 'Пароль',
                'validators' => [
                    new PasswordValidator()
                ],
                'attributes' => [
                    'placeholder' => 'Пароль'
                ]
            ],
            'password_repeat' => [
                'class' => PasswordField::class,
                'label' => 'Повторите пароль',
                'validators' => [
                    new PasswordValidator()
                ],
                'attributes' => [
                    'placeholder' => 'Повторите пароль'
                ]
            ]
        ];
    }

    public function clean($attributes)
    {
        if ($attributes['password'] != $attributes['password_repeat']) {
            $this->addError('password_repeat', 'Указнные пароли не совпадают');
        }
        if (User::objects()->filter(['email' => $attributes['email']])->count() > 0) {
            $this->addError('email', 'Данный адрес уже используется на сайте');
        }
    }

    public function save()
    {
        $attributes = $this->getAttributes();
        $hasher = UserModule::getPasswordHasher();

        $this->_user = new User();
        $this->_user->password = $hasher::hash($attributes['password']);
        $this->_user->email = $attributes['email'];
        Phact::app()->mail->template(
            $attributes['email'],
            "Вы успешно зарегистрировались",
            'mail/register.tpl',
            [
                'email' => $attributes['email'],
                'password' => $attributes['password']
            ]
        );
        return $this->_user->save();
    }

    public function login()
    {
        Phact::app()->auth->login($this->_user);
    }
}