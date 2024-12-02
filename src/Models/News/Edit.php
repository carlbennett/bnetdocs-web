<?php

namespace BNETDocs\Models\News;

class Edit extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public bool $acl_allowed = false;
    public ?int $category = null;
    public ?array $comments = null;
    public ?string $content = null;
    public bool $markdown = false;
    public ?array $news_categories = null;
    public ?\BNETDocs\Libraries\News\Post $news_post = null;
    public ?int $news_post_id = null;
    public bool $published = false;
    public bool $rss_exempt = true;
    public ?string $title = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'acl_allowed' => $this->acl_allowed,
            'category' => $this->category,
            'comments' => $this->comments,
            'content' => $this->content,
            'markdown' => $this->markdown,
            'news_categories' => $this->news_categories,
            'news_post' => $this->news_post,
            'news_post_id' => $this->news_post_id,
            'published' => $this->published,
            'rss_exempt' => $this->rss_exempt,
            'title' => $this->title,
        ]);
    }
}
