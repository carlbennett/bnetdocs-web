<?php

namespace BNETDocs\Models\User;

class Index extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public int $limit = 0;
    public string|array|null $order = null;
    public int $page = 0;
    public int $pages = 0;
    public int $sum_users = 0;
    public ?array $users = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'limit' => $this->limit,
            'order' => $this->order,
            'page' => $this->page,
            'pages' => $this->pages,
            'sum_users' => $this->sum_users,
            'users' => $this->users,
        ]);
    }
}
