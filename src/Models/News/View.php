<?php

namespace BNETDocs\Models\News;

class View extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public ?array $comments = null;
    public ?\BNETDocs\Libraries\News\Post $news_post = null;
    public ?int $news_post_id = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'comments' => $this->comments,
            'news_post' => $this->news_post,
            'news_post_id' => $this->news_post_id,
        ]);
    }
}
