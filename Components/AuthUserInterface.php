<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 08/10/2018 14:45
 */

namespace Modules\User\Components;


use Phact\Interfaces\AuthInterface;

interface AuthUserInterface extends AuthInterface
{
    /**
     * @return mixed
     */
    public function getAfterLoginRoute();

    /**
     * @return mixed
     */
    public function getAfterLogoutRoute();

    /**
     * @return mixed
     */
    public function setAfterLoginRoute(string $route);

    /**
     * @return mixed
     */
    public function setAfterLogoutRoute(string $route);
}