<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 07/08/16 14:03
 */

namespace Modules\User\Components;


use Modules\User\Hashers\Password;
use Modules\User\Hashers\PasswordHasher;
use Modules\User\Models\User;
use Phact\Helpers\SmartProperties;
use Phact\Interfaces\UserInterface;
use Phact\Orm\Model;
use Phact\Request\HttpRequestInterface;
use Phact\Request\SessionInterface;

class Auth implements AuthUserInterface
{
    use SmartProperties;

    /**
     * @var User
     */
    protected $_user = null;

    /**
     * Login expire
     * Default: 60 days
     * @var int
     */
    public $expire = 5184000;

    /**
     * @var string
     */
    public $authCookieName = 'USER';

    /**
     * @var string
     */
    public $authSessionName = 'USER_ID';

    public $class = User::class;

    /**
     * @var SessionInterface
     */
    protected $_session;

    /**
     * @var HttpRequestInterface
     */
    protected $_request;

    /**
     * @var PasswordHasher
     */
    protected $_hasher;

    /**
     * @var string
     */
    protected $_afterLoginRoute;

    /**
     * @var string
     */
    protected $_afterLogoutRoute;

    public function __construct(HttpRequestInterface $request, SessionInterface $session, PasswordHasher $hasher = null)
    {
        $this->_request = $request;
        $this->_session = $session;
        $this->_hasher = $hasher ?: new Password();
    }

    public function login(UserInterface $user, $rememberMe = true)
    {
        $this->updateSession($user);
        if ($rememberMe) {
            $this->updateCookie($user);
        }
        $this->setUser($user);
    }

    /**
     * @param bool $clearSession
     * @internal param bool $total Clear all session
     */
    public function logout($clearSession = true)
    {
        $this->removeSession($clearSession);
        $this->removeCookie();
        $this->_user = null;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = $this->fetchUser();
        }
        return $this->_user;
    }

    public function setUser(UserInterface $user)
    {
        $this->_user = $user;
        $this->updateCookie($user);
        $this->updateSession($user);
    }

    public function fetchUser()
    {
        $user = $this->getSessionUser();
        if (!$user) {
            if ($user = $this->getCookieUser()) {
                $this->updateSession($user);
            }
        }
        if (!$user) {
            $class = $this->class;
            $user = new $class();
            $user->is_guest = true;
        }
        return $user;
    }

    public function getSessionUser()
    {
        $id = $this->getSession();
        if ($id) {
            return $this->findUserById($id);
        }
        return null;
    }

    public function getCookieUser()
    {
        $cookie = $this->getCookie();
        if ($cookie) {
            $data = explode(':', $cookie);
            if (count($data) == 2) {
                $id = $data[0];
                $key = $data[1];

                $user = $this->findUserById($id);
                if ($user && password_verify($user->email . $user->password, $key)) {
                    return $user;
                }
            }
        }
        return null;
    }

    public function updateSession(Model $user)
    {
        $this->setSession($user->id);
    }

    public function updateCookie(Model $user)
    {
        $value = implode(':', [$user->id, password_hash($user->email . $user->password, PASSWORD_DEFAULT)]);
        $this->setCookie($value);
    }

    public function setSession($session)
    {
        $this->_session->add($this->authSessionName, $session);
    }

    public function getSession()
    {
        return $this->_session->get($this->authSessionName);
    }

    public function removeSession($clearSession = true)
    {
        if ($clearSession) {
            $this->_session->destroy();
        } else {
            $this->_session->remove($this->authSessionName);
        }
    }
    
    public function setCookie($cookie)
    {
        $this->_request->getCookie()->add($this->authCookieName, $cookie, time() + $this->expire, '/');
    }
    
    public function getCookie()
    {
        return $this->_request->getCookie()->get($this->authCookieName);
    }

    public function removeCookie()
    {
        $this->_request->getCookie()->remove($this->authCookieName);
    }

    /**
     * @param UserInterface $user
     * @param string $password
     * @return bool
     */
    public function verifyPassword(UserInterface $user, string $password)
    {
        return $this->_hasher->verify($password, $user->getPassword());
    }

    /**
     * @param UserInterface $user
     * @param string $password
     */
    public function setPassword(UserInterface $user, string $password)
    {
        $hashed = $this->_hasher->hash($password);
        $user->setPassword($hashed);
    }

    /**
     * @param UserInterface $user
     * @param string $login
     */
    public function setLogin(UserInterface $user, string $login)
    {
        $user->setLogin($login);
    }

    public function findUserByLogin($login)
    {
        $class = $this->class;
        return $class::objects()->filter(['email' => $login])->limit(1)->get();
    }

    public function findUserById($id)
    {
        $class = $this->class;
        return $class::objects()->filter(['id' => $id])->limit(1)->get();
    }

    /**
     * @return mixed
     */
    public function getAfterLoginRoute()
    {
        return $this->_afterLoginRoute;
    }

    /**
     * @return mixed
     */
    public function getAfterLogoutRoute()
    {
        return $this->_afterLogoutRoute;
    }

    /**
     * @param string $route
     * @return void
     */
    public function setAfterLoginRoute(string $route)
    {
        $this->_afterLoginRoute = $route;
    }

    /**
     * @param string $route
     * @return void
     */
    public function setAfterLogoutRoute(string $route)
    {
        $this->_afterLogoutRoute = $route;
    }
}