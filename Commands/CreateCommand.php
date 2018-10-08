<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 12/12/16 16:08
 */

namespace Modules\User\Commands;

use Modules\User\Models\User;
use Phact\Commands\Command;
use Phact\Interfaces\AuthInterface;

class CreateCommand extends Command
{
    public function handle($arguments = [], AuthInterface $auth)
    {
        $email = readline("E-mail: ");

        if ($auth->findUserByLogin($email)) {
            echo "User with email {$email} already exists";
            exit();
        }
        
        $password = null;
        while (!$password) {
            $password = readline("Password: ");
        }

        $user = new User();
        $user->is_superuser = true;
        $user->is_staff = true;
        $auth->setLogin($user, $email);
        $auth->setPassword($user, $password);
        $user->save();
        echo "Superuser with email {$email} created successfully";
    }

    public function getDescription()
    {
        return 'Create superuser';
    }
}