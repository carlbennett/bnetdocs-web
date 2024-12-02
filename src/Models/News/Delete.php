<?php

namespace BNETDocs\Models\News;

class Delete extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?int $id = null;
    public ?\BNETDocs\Libraries\News\Post $news_post = null;
    public string $title = '';

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'id' => $this->id,
            'news_post' => $this->news_post,
            'title' => $this->title,
        ]);
    }
}
