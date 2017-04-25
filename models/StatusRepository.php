<?php

class StatusRepository extends DbRepository
{
    public function insert($user_id, $body)
    {
        $now = new DateTime();

        $sql = "insert into status(user_id, body, created_at)
                values(:user_id, :body, :created_at)";

        $stmt = $this->execute($sql, array(
            ':user_id' => $user_id,
            ':body' => $body,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }

    public function fetchAllPersonalArchivesByUserId($user_id)
    {
        $sql = "select a.*, u.user_name from status a
                left join user u on a.user_id = u.id
                left join following f on f.following_id = a.user_id and f.user_id = :user_id
                where f.user_id = :user_id or u.id = :user_id
                order by a.created_at desc";
        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    public function fetchAllByUserId($user_id)
    {
        $sql = "select a.*, u.user_name from status a
                left join user u on a.user_id = u.id
                where u.id = :user_id
                order by a.created_at desc";
        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    public function fetchByIdAndUserName($id, $user_name)
    {
        $sql = "select a.*, u.user_name from status a
                left join user u on u.id = a.user_id
                where a.id = :id and u.user_name = :user_name";
        return $this->fetch($sql, array(
            ':id' => $id,
            ':user_name' => $user_name,
        ));
    }
}
