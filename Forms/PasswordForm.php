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

use Modules\User\Models\User;
use Modules\User\UserModule;
use Modules\User\Validators\PasswordValidator;
use Phact\Form\Fields\CheckboxField;
use Phact\Form\Fields\EmailField;
use Phact\Form\Fields\PasswordField;
use Phact\Form\Form;
use Phact\Interfaces\AuthInterface;
use Phact\Main\Phact;
use Phact\Form\Fields\CharField;
use Phact\Translate\Translator;

class PasswordForm extends Form
{
    use Translator;

    protected $_instance;

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
            'current_password' => [
                'class' => PasswordField::class,
                'label' => self::t('User.main', 'Current password')
            ],
            'new_password' => [
                'class' => PasswordField::class,
                'label' => self::t('User.main', 'New password'),
                'validators' => [
                    new PasswordValidator()
                ]
            ],
            'repeat_password' => [
                'class' => PasswordField::class,
                'label' => self::t('User.main', 'Repeat new password')
            ],
        ];
    }

    /**
     * @return User
     */
    public function getInstance()
    {
        return $this->_instance;
    }

    /**
     * @param $instance User
     * @return mixed
     */
    public function setInstance($instance)
    {
        $this->_instance = $instance;
        return $instance;
    }

    public function clean($attributes)
    {
        $password = $attributes['current_password'];
        $newPassword = $attributes['new_password'];
        $repeatPassword = $attributes['repeat_password'];
        $user = $this->getInstance();

        if ($this->_auth->verifyPassword($user, $password)) {
            $this->addError('current_password', self::t('User.main', 'Wrong password'));
        }
        if ($newPassword != $repeatPassword) {
            $this->addError('repeat_password', self::t('User.main', 'Passwords do not match'));
        }
    }

    public function save()
    {
        $attributes = $this->getAttributes();
        $user = $this->getInstance();
        $this->_auth->setPassword($user, $attributes['new_password']);
        return $user->save();
    }
}