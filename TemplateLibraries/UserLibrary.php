<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 14/02/17 11:45
 */

namespace Modules\User\TemplateLibraries;


use Modules\User\Forms\LoginForm;
use Modules\User\Forms\RegisterForm;
use Phact\Template\TemplateLibrary;

class UserLibrary extends TemplateLibrary
{
    /**
     * @name get_login_form
     * @kind accessorProperty
     * @return LoginForm
     */
    public static function getLoginForm()
    {
        return new LoginForm();
    }

    /**
     * @name get_register_form
     * @kind accessorProperty
     * @return RegisterForm
     */
    public static function getRegisterForm()
    {
        return new RegisterForm();
    }
}