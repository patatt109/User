<?php

/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 05/04/17 13:15
 */
namespace Modules\User\Forms;

use Modules\User\UserModule;
use Modules\User\Validators\PasswordValidator;
use Phact\Form\Fields\PasswordField;
use Phact\Form\ModelForm;
use Modules\User\Models\User;
use Phact\Interfaces\AuthInterface;
use Phact\Translate\Translator;

class UserAdminForm extends ModelForm
{
    use Translator;

    /**
     * @var AuthInterface
     */
    protected $_auth;

    public function __construct(AuthInterface $auth, array $config = [])
    {
        $this->_auth = $auth;
        parent::__construct($config);
    }

    public function getFields()
    {
        return [
            'new_password' => [
                'class' => PasswordField::class,
                'label' => self::t('User.main', 'New password'),
                'validators' => [
                    new PasswordValidator()
                ]
            ],
            'new_password_repeat' => [
                'class' => PasswordField::class,
                'label' => self::t('User.main', 'Repeat new password'),
                'validators' => [
                    new PasswordValidator()
                ]
            ],
        ];
    }

    public function clean($attributes)
    {
        if ($attributes['new_password'] && ($attributes['new_password'] !== $attributes['new_password_repeat'])) {
            $this->addError('new_password_repeat', self::t('User.main', 'Passwords do not match'));
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
            /** @var User $user */
            $user = $this->getInstance();
            $this->_auth->setPassword($user, $password);
            $user->save();
        }
        return $saved;
    }
}