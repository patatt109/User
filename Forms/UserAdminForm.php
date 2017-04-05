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
 * @date 05/04/17 13:15
 */
namespace Modules\User\Forms;

use Modules\User\UserModule;
use Modules\User\Validators\PasswordValidator;
use Phact\Form\Fields\PasswordField;
use Phact\Form\ModelForm;
use Modules\User\Models\User;

class UserAdminForm extends ModelForm
{
    public function getFields()
    {
        return [
            'new_password' => [
                'class' => PasswordField::class,
                'label' => 'Новый пароль',
                'validators' => [
                    new PasswordValidator()
                ]
            ],
            'new_password_repeat' => [
                'class' => PasswordField::class,
                'label' => 'Повторите пароль',
                'validators' => [
                    new PasswordValidator()
                ]
            ],
        ];
    }

    public function clean($attributes)
    {
        if ($attributes['new_password'] && ($attributes['new_password'] != $attributes['new_password_repeat'])) {
            $this->addError('new_password_repeat', 'Указнные пароли не совпадают');
        }
    }

    public function getModel()
    {
        return new User;
    }


    public function save($safeAttributes = [])
    {
        $saved = parent::save($safeAttributes);
        $password = $this->getField('new_password')->getValue();
        if ($saved && $password) {
            $instance = $this->getInstance();
            $hasher = UserModule::getPasswordHasher();
            $instance->password = $hasher::hash($password);
            $instance->save();
        }
        return $saved;
    }
}