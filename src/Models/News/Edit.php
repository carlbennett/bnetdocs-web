<?php

namespace BNETDocs\Models\News;

class Edit extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
    public const ERROR_ACL_NOT_SET = 'ACL_NOT_SET';
    public const ERROR_EMPTY_CONTENT = 'EMPTY_CONTENT';
    public const ERROR_EMPTY_TITLE = 'EMPTY_TITLE';
    public const ERROR_INTERNAL = 'INTERNAL_ERROR';
    public const ERROR_NOT_FOUND = 'NOT_FOUND';
    public const ERROR_NOT_LOGGED_IN = 'NOT_LOGGED_IN';

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
