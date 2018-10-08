<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 04/08/16 10:42
 */

namespace Modules\User;

use Modules\Admin\Contrib\AdminMenuInterface;
use Modules\Admin\Traits\AdminTrait;
use Phact\Module\Module;

class UserModule extends Module implements AdminMenuInterface
{
    use AdminTrait;

    public function getVerboseName()
    {
        return $this->t('User.main', 'Users');
    }
}