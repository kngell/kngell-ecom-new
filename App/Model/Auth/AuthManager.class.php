<?php

declare(strict_types=1);
class AuthManager extends Model
{
    public static $currentLoggedInUser = null;
    private $_isLoggedIn = false;
    private $_confirm;

    public function __construct(string $user = '')
    {
        parent::__construct(tableSchema:'userId', tableSchemaID:'users');
        if ($user) {
            $u = is_numeric($user) ? $this->getDetails($user) : $this->getDetails($user, 'email');
            if ($u->count() > 0) {
                $this->entity->assign((array) $u->get_results());
            }
        }
    }

    public static function user() : string
    {
        $session = Application::diGet(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            return $session->get(CURRENT_USER_SESSION_NAME)['firstName'];
        }
        return '';
    }

    public static function acls() : array
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            return $session->get(CURRENT_USER_SESSION_NAME)['acl'];
        }
        return [];
    }

    // check if user is logged
    public static function isUserLoggedIn() : bool
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            return true;
        }
        return false;
    }

    public static function currentUser() : self
    {
        $session = Container::getInstance()->make(SessionInterface::class);
        if ($session->exists(CURRENT_USER_SESSION_NAME)) {
            $id = $session->get(CURRENT_USER_SESSION_NAME)['id'];
            if (self::$currentLoggedInUser === null) {
                /** @var AuhtManager */
                $user = Container::getInstance()->make(self::class, [
                    'user' => (int) $id,
                ]);
                self::$currentLoggedInUser = $user->assign((array) $user);
            }
        }
        return self::$currentLoggedInUser;
    }
}