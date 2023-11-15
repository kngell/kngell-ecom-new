<?php

declare(strict_types=1);

class AuthenticateUserManager extends Model
{
    protected string $_colID = 'userId';
    protected string $_table = 'users';

    public function __construct(
        protected UserSessionsManager $userSession,
        protected SessionInterface $session,
        protected VisitorsManager $visitor
    ) {
        parent::__construct($this->_table, $this->_colID);
    }

    public function authenticate() : bool|array
    {
        $user = $this->loginAttemps($this->getEntity()->getEmail());
        if ($user->count() > 0) {
            return [$user, current($user->get_results())->getNumber()];
        }
        return false;
    }

    public function login(bool|string $remember_me, array $data) : ?Model
    {
        if (!AuthManager::isUserLoggedIn()) {
            /* @var UsersEntity */
            $this->setEntity(current($this->get_results()));
            $this->session->set(CURRENT_USER_SESSION_NAME, [
                'id' => (int) $this->getEntity()->getUserId(),
                'firstName' => (string) $this->getEntity()->getFirstName() ?? '',
                'lastName' => (string) $this->getEntity()->getLastName() ?? '',
                'acl' => (array) $this->acls(),
                'verified' => (int) $this->getEntity()->getVerified() ?? 0,
                'customerId' => (string) $this->getEntity()->getCustomerId() ?? '',
            ]);
            return $this->userSession->assign(array_merge($data, $this->manageSession($this->visitor->manageVisitors([
                'ip' => H_visitors::getIP(),
            ]), $remember_me)))->save();
        }

        return null;
    }

    public function rememberMeCheck() : array
    {
        if (!$this->cookie->exists(REMEMBER_ME_COOKIE_NAME)) {
            $visitor = $this->visitor->manageVisitors();
            $cookies = $visitor->getEntity()->getCookies();
        }
        $rem = $cookies ?? $this->cookie->get(REMEMBER_ME_COOKIE_NAME);
        $this->userSession->getDetails($rem, 'rememberMeCookie');
        if ($this->userSession->count() === 1) {
            $userSession = $this->userSession->get_results();
            return [
                'remember' => true,
                'email' => $userSession->email,
                'password' => $userSession->password,
            ];
        }
        return [];
    }

    public function getEntity() : UsersEntity
    {
        return $this->entity;
    }

    private function rememberMe(bool|string $remember_me, UserSessionsManager $session) : string
    {
        $rem_cookie = '';
        if ($remember_me != false) {
            if ($session->count() === 1) {
                if (!$session->getEntity()->isInitialized('rememberMeCookie')) {
                    return $this->rememberCookie();
                }
                $cookieSession = $session->getEntity()->{'getRememberMeCookie'}();
                if ($this->cookie->exists(REMEMBER_ME_COOKIE_NAME) && $cookieSession != $this->cookie->get(REMEMBER_ME_COOKIE_NAME)) {
                    $cookieSession = $this->cookie->get(REMEMBER_ME_COOKIE_NAME);
                    $session->set('rememberMeCookie', $cookieSession);
                    $session->save();
                }
                return $cookieSession;
            }
            $rem_cookie = $this->rememberCookie();
        }
        return $rem_cookie;
    }

    private function rememberCookie() : string
    {
        $rem_cookie = '';
        if (!$this->cookie->exists(REMEMBER_ME_COOKIE_NAME)) {
            $this->userSession->getQueryParams();
            $rem_cookie = $this->userSession->getUniqueId('rememberMeCookie');
            $this->cookie->set($rem_cookie, REMEMBER_ME_COOKIE_NAME);
        } else {
            $rem_cookie = $this->cookie->get(REMEMBER_ME_COOKIE_NAME);
        }

        return $rem_cookie;
    }

    private function sessionToken(UserSessionsManager $session) : string
    {
        $sessionToken = '';
        if ($session->count() === 1) {
            if (!$session->getEntity()->isInitialized('sessionToken')) {
                if (!$this->cookie->exists(TOKEN_NAME)) {
                    $session->getQueryParams();
                    $sessionToken = $session->getUniqueId('sessionToken');
                    $this->cookie->set($sessionToken, TOKEN_NAME);
                } else {
                    $sessionToken = $this->cookie->get(TOKEN_NAME);
                }
            } else {
                $sessionToken = $session->getEntity()->getSessionToken();
                if (!$this->cookie->exists(TOKEN_NAME)) {
                    $this->cookie->set($sessionToken, TOKEN_NAME);
                } else {
                    if ($this->cookie->get(TOKEN_NAME) !== $sessionToken) {
                        $this->cookie->set($sessionToken, TOKEN_NAME);
                    }
                }
            }
        } else {
            if (!$this->cookie->exists(TOKEN_NAME)) {
                $session->getQueryParams();
                $sessionToken = $session->getUniqueId('session_token');
                $this->cookie->set($sessionToken, TOKEN_NAME);
            } else {
                $sessionToken = $this->cookie->get(TOKEN_NAME);
            }
            if ($session->count() > 1) {
                $session->getQueryParams();
                $session->table()->where(['userId' => '$this->userID']);
                $session->delete();
            }
        }
        $session = null;
        return $sessionToken;
    }

    private function manageSession(VisitorsManager $visitor, bool|string $remember_me) : array
    {
        $sessionDb = $this->userSession->getDetails($this->getEntity()->getUserId(), 'userId');
        $sessionDb->count() > 0 ? $sessionDb->setEntity($sessionDb->get_results()) : '';
        return [
            'rememberMeCookie' => $this->rememberMe($remember_me, $sessionDb),
            'sessionToken' => $this->sessionToken($sessionDb),
            'userCookie' => $visitor->count() > 0 ? $visitor->getEntity()->getCookies() : '',
            'userAgent' => $this->session->uagent_no_version(),
            'userId' => $this->getEntity()->getUserId(),
            'email' => $this->getEntity()->getEmail(),
        ];
    }

    private function loginAttemps(string $email) : self
    {
        $this->table()
            ->leftJoin('login_attempts', ['COUNT|laId|number'])
            ->on(['userId', 'userId|login_attempts'], ['timestamp|>=|' => [time() - 60 * 60, 'login_attempts']])
            ->where(['email' => $email . '|users'])
            ->groupBy(['userId' => 'users'])
            ->return('class')
            ->build();

        return $this->getAll();
    }

    private function acls() : array
    {
        return $this->container(UsersManager::class)->get_selectedOptions($this) ?? [];
    }
}