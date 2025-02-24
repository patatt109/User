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
use Modules\User\UserModule;
use Phact\Form\Fields\EmailField;
use Phact\Form\Form;
use Phact\Interfaces\AuthInterface;
use Phact\Translate\Translator;

class RecoverForm extends Form
{
    use Translator;

    /**
     * @var AuthInterface
     */
    protected $_auth;

    /**
     * @var MailerInterface
     */
    protected $_mailer;

    public function __construct(AuthInterface $auth, array $config = [], MailerInterface $mailer = null)
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
                'label' => self::t('User.main', 'E-mail address'),
                'required' => true,
                'hint' => self::t('User.main', 'Enter your e-mail address and follow the instructions')
            ]
        ];
    }

    public function clean($attributes)
    {
        if (!$this->getUser($attributes['email'])) {
            $this->addError('email', self::t('User.main', 'There is no users with current e-mail'));
        }
    }

    public function save()
    {
        $attributes = $this->getAttributes();
        $password = uniqid('', true);
        $user = $this->getUser($attributes['email']);

        if ($user) {
            $this->_auth->setPassword($user, $password);
            $user->save();

            if ($this->_mailer) {
                $this->_mailer->template($user->email, self::t('User.main', 'Recover password'), 'mail/recover_password.tpl', [
                    'password' => $password
                ]);
            }

            return true;
        }

        return false;
    }

    public function getUser($email)
    {
        return $this->_auth->findUserByLogin($email);
    }
}