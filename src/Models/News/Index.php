<?php

namespace BNETDocs\Models\News;

class Index extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?array $news_posts = null;
    public ?\BNETDocs\Libraries\Core\Pagination $pagination = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'news_posts' => $this->news_posts,
            'pagination' => $this->pagination,
        ]);
    }
}
