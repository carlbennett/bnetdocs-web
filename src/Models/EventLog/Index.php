<?php

namespace BNETDocs\Models\EventLog;

class Index extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?array $events = null;
    public int $limit = 0;
    public string $order = '';
    public int $page = 0;
    public int $pages = 0;
    public int $sum_events = 0;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'events' => $this->events,
            'limit' => $this->limit,
            'order' => $this->order,
            'page' => $this->page,
            'pages' => $this->pages,
            'sum_events' => $this->sum_events,
        ]);
    }
}
