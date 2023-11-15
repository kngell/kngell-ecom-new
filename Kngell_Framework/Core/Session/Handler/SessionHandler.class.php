<?php

use SessionHandlerInterface;

declare(strict_types=1);

class SessionHandler implements SessionHandlerInterface
{
    protected bool $useTransactions;
    protected int $expiry;
    protected string $_table = 'user_sessions';
    protected $col_sid = 'sid';
    protected $col_expiry = 'expiry';
    protected $col_data = 'userData';
    protected array $unlockStatements = [];
    protected bool $collectGarbage = false;

    public function __construct(private UserSessionsManager $us, bool $useTransactions)
    {
        $this->useTransactions = $useTransactions;
        $this->expiry = time() + (int) ini_get('session.gc_maxlifetime');
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function read(string $session_id): string|false
    {
        try {
            $this->isAtransaction($session_id);
            $user_session = $this->us->getDetails((int) $session_id);
            if ($user_session->count()) {
                /** @var UserSessionsEntity */
                $en = $user_session->getEntity();
                if ($en->getExpiry() < time()) {
                    return '';
                }
                return $en->getUserData();
            }
            if ($this->useTransactions) {
                return $this->initializeRecord($user_session->getStatement(), $session_id);
            }
            return '';
        } catch (\Throwable $th) {
            if ($this->us->inTransaction()) {
                $this->us->rollBack();
            }
            throw new SessionException($th->getMessage(), $th->getCode());
        }
    }

    public function write(string $session_id, string $data): bool
    {
        try {
            $this->us->getQueryParams()->table()->updateOnDuplicate();
            $save = $this->us->assign([
                'sessionToken' => $session_id,
                'expiry' => $this->expiry,
                'userData' => $data,
            ])->save();
            if ($save->count()) {
                return true;
            }
        } catch (\Throwable $th) {
            if ($this->us->inTransaction()) {
                $this->us->rollBack();
            }
            throw new SessionException($th->getMessage(), $th->getCode());
        }
    }

    public function close(): bool
    {
        $this->handleUnlockStatements();
        if ($this->collectGarbage) {
            $this->us->getQueryParams()->table()->where([$this->col_expiry => '<|' . time()]);
            $this->us->delete();
            $this->collectGarbage = false;
        }
        return false;
    }

    public function destroy(string $id): bool
    {
        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        $this->collectGarbage = true;
        return true;
    }

    protected function getLock(mixed $session_id, mixed $value) : UserSessionsManager
    {
        $this->us->getQueryParams()->table()->getLock($session_id, $value);
        $locked = $this->us->getAll();
        if ($locked->count()) {
            $locked->getQueryParams()->table()->doRelease($session_id);
            return $locked->doRelease();
        }
        return null;
    }

    protected function initializeRecord(PDOStatement $stmt, string $session_id) : string|false
    {
        try {
            $save = $this->us->assign([
                'sessionToken' => $session_id,
                'expiry' => $this->expiry,
                'userData' => '',
            ])->save();
        } catch (\Throwable $th) {
            if (0 === strpos((string) $th->getCode(), '23')) {
                $stmt->execute();
                $results = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($results) {
                    return $results[$this->col_data];
                }
                return '';
            }
            if ($this->us->inTransaction()) {
                $this->us->rollBack();
            }
            throw new SessionException($th->getMessage(), $th->getCode());
        }
    }

    private function handleUnlockStatements() : void
    {
        if ($this->us->inTransaction()) {
            $this->us->commit();
        } elseif ($this->unlockStatements) {
            while ($unLockStmt = array_shift($this->unlockStatements)) {
                $unLockStmt->execute();
            }
        }
    }

    private function isAtransaction(string $session_id) : void
    {
        if ($this->useTransactions) {
            $this->us->customQuery('SET TRANSACTION ISOLATION LEVEL READ COMMITED');
            $this->us->beginTransaction();
        } else {
            $this->unlockStatements = $this->getLock((int) $session_id, 50);
        }
    }
}