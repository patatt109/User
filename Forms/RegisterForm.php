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

use Modules\Mail\Components\MailerInterface;
use Modules\User\Models\User;
use Modules\User\Validators\PasswordValidator;
use Phact\Form\Fields\EmailField;
use Phact\Form\Fields\PasswordField;
use Phact\Form\Form;
use Phact\Interfaces\AuthInterface;
use Phact\Translate\Translator;

class RegisterForm extends Form
{
    use Translator;

    protected $_user;

    /**
     * @var AuthInterface
     */
    protected $_auth;

    /**
     * @var MailerInterface
     */
    protected $_mailer;

    public function __construct(array $config = [], AuthInterface $auth, MailerInterface $mailer = null)
    {
        $this->_auth = $auth;
        $this->_mailer = $mailer;
        parent::__construct($config);
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
                'validators' => [
                    new PasswordValidator()
                ]
            ],
            'password_repeat' => [
                'class' => PasswordField::class,
                'label' => self::t('User.main', 'Repeat password'),
                'validators' => [
                    new PasswordValidator()
                ]
            ]
        ];
    }

    public function clean($attributes)
    {
        if ($attributes['password'] !== $attributes['password_repeat']) {
            $this->addError('password_repeat', self::t('User.main', 'Passwords do not match'));
        }
        if ($this->_auth->findUserByLogin($attributes['email'])) {
            $this->addError('email', self::t('User.main', 'This e-mail address is already used'));
        }
    }

    public function save()
    {
        $attributes = $this->getAttributes();

        $this->_user = new User();
        $this->_auth->setLogin($this->_user, $attributes['email']);
        $this->_auth->setPassword($this->_user, $attributes['password']);
        if ($this->_mailer) {
            $this->_mailer->template(
                $attributes['email'],
                self::t('User.main', 'You have successfully registered'),
                'mail/register.tpl',
                [
                    'email' => $attributes['email'],
                    'password' => $attributes['password']
                ]
            );
        }
        return $this->_user->save();
    }

    public function login()
    {
        $this->_auth->login($this->_user);
    }
}