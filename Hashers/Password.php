<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 07/08/16 13:24
 */

namespace Modules\User\Hashers;


class Password implements PasswordHasher
{
    public function hash($raw, $options = [])
    {
        $algo = PASSWORD_DEFAULT;
        if (isset($options['algo'])) {
            $algo = $options['algo'];
            unset($options['algo']);
        }
        return password_hash($raw, $algo, $options);
    }

    public function verify($raw, $hashed)
    {
        return password_verify($raw, $hashed);
    }
}