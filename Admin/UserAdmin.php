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
 * @date 05/04/17 13:12
 */
namespace Modules\User\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\User\Forms\UserAdminForm;
use Modules\User\Models\User;

class UserAdmin extends Admin
{
    public function getSearchColumns()
    {
        return ['name'];
    }
    
    public function getModel()
    {
        return new User;
    }

    public function getForm()
    {
        return new UserAdminForm();
    }

    public static function getName()
    {
        return 'Пользователи';
    }

    public static function getItemName()
    {
        return 'Пользователь';
    }
}