<?php

namespace BNETDocs\Models\News;

class View extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?array $comments = null;
    public ?\BNETDocs\Libraries\News\Post $news_post = null;
    public ?int $news_post_id = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'comments' => $this->comments,
            'news_post' => $this->news_post,
            'news_post_id' => $this->news_post_id,
        ]);
    }
}
