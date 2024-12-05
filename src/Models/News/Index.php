<?php

namespace BNETDocs\Models\News;

class Index extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public ?array $news_posts = null;
    public ?\BNETDocs\Libraries\Core\Pagination $pagination = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'news_posts' => $this->news_posts,
            'pagination' => $this->pagination,
        ]);
    }
}
