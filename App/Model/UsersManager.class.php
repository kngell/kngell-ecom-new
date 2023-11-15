<?php

declare(strict_types=1);
class UsersManager extends Model
{
    protected string $_colID = 'userId';
    protected string $_table = 'users';
    protected string $_colContent = '';
    protected string $_media_img = 'profileImage';

    public function __construct(private GroupsMaganer $groups)
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function get_selectedOptions(?Model $m = null)
    {
        $query_params = $this->groups->table()->join('group_user', ['userId', 'groupId'])
            ->on(['grId', 'groupId'])
            ->where(['userId' => $m->getEntity()->{'getUserId'}() . '|group_user'])
            ->return('class')
            ->build();
        $user_roles = $this->groups->getAll($query_params);
        $response = [];
        if ($user_roles->count() >= 1) {
            foreach ($user_roles->get_results() as $role) {
                $response[$role->groupID] = $role->name;
            }
        }
        $user_roles = null;

        return $response ? $response : [];
    }

    public function singleUser(int $id = -1) : ?self
    {
        if (!($id < 0)) {
            $this->table()->leftJoin('user_extra_data', ['u_descr', 'u_comment', 'gender', 'dob', 'u_function'])
                ->on(['userId', 'userId'])
                ->where(['userId' => $id])
                ->return('class');
            $u = $this->getAll();
            if ($u->count() === 1) {
                return $u->assign((array) current($u->get_results()));
            }
        }

        return null;
    }
}