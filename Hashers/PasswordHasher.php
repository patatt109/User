<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 08/10/2018 13:20
 */

namespace Modules\User\Hashers;


interface PasswordHasher
{
    /**
     * Hash password
     * @param $raw
     * @param array $options
     * @return mixed
     */
    public function hash($raw, $options = []);

    /**
     * Verify password
     * @param $raw
     * @param $hashed
     * @return mixed
     */
    public function verify($raw, $hashed);
}