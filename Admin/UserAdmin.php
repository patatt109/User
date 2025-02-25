<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 05/04/17 13:12
 */
namespace Modules\User\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\User\Forms\UserAdminForm;
use Modules\User\Models\User;
use Phact\Translate\Translator;

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
        return new UserAdminForm($this->_auth, []);
    }

    public function getName()
    {
        return $this->t('User.main', 'Users');
    }

    public function getItemName()
    {
        return $this->t('User.main', 'User');
    }
}