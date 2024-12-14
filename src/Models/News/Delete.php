<?php

namespace BNETDocs\Models\News;

class Delete extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_NOT_FOUND = 'NOT_FOUND';

    public ?int $id = null;
    public ?\BNETDocs\Libraries\News\Post $news_post = null;
    public string $title = '';

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'id' => $this->id,
            'news_post' => $this->news_post,
            'title' => $this->title,
        ]);
    }
}
