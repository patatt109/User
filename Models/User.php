<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 07/08/16 13:18
 */

namespace Modules\User\Models;

use Phact\Interfaces\UserInterface;
use Phact\Orm\Fields\BooleanField;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\EmailField;
use Phact\Orm\Model;
use Phact\Translate\Translator;

/**
 * Class User
 * @package Modules\User\Models
 *
 * @property String email User email
 * @property String password Hashed password
 * @property bool is_superuser User is superuser (admin)
 * @property bool is_staff User is staff
 * @property bool isGuest Is guest (not authorized user)
 */
class User extends Model implements UserInterface
{
    use Translator;

    public $is_guest = false;

    public static function getFields()
    {
        return [
            'email' => [
                'class' => EmailField::class,
                'label' => self::t('User.main', 'E-mail')
            ],
            'password' => [
                'class' => CharField::class,
                'label' => self::t('User.main', 'Password'),
                'editable' => false
            ],
            'is_superuser' => [
                'class' => BooleanField::class,
                'label' => self::t('User.main', 'Is superuser'),
                'default' => false
            ],
            'is_staff' => [
                'class' => BooleanField::class,
                'label' => self::t('User.main', 'Is staff'),
                'default' => false
            ]
        ];
    }

    public function getIsGuest()
    {
        return $this->is_guest;
    }

    public function getIsSuperuser()
    {
        return $this->is_superuser;
    }

    /**
     * Get unique id for user
     * @return bool
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get unique login for user
     * @return bool
     */
    public function getLogin()
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->email;
    }

    /**
     * Set hashed password
     */
    public function setPassword(string $hashedPassword)
    {
        $this->password = $hashedPassword;
    }

    /**
     * Get hashed password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set uniquie login for user
     */
    public function setLogin(string $login)
    {
        $this->email = $login;
    }
}