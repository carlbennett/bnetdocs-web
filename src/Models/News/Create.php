<?php

namespace BNETDocs\Models\News;

class Create extends \BNETDocs\Models\Core\AccessControl implements \JsonSerializable
{
  public const ACL_NOT_SET = 'ACL_NOT_SET';
  public const EMPTY_CONTENT = 'EMPTY_CONTENT';
  public const EMPTY_TITLE = 'EMPTY_TITLE';
  public const INTERNAL_ERROR = 'INTERNAL_ERROR';

  public ?int $category_id = null;
  public string $content = '';
  public mixed $error = 'INTERNAL_ERROR';
  public bool $markdown = false;
  public ?\BNETDocs\Libraries\News\Post $news_post = null;
  public ?array $news_categories = null;
  public bool $rss_exempt = false;
  public string $title = '';

  public function jsonSerialize(): mixed
  {
    return \array_merge(parent::jsonSerialize(), [
      'category' => $this->category_id,
      'content' => $this->content,
      'error' => $this->error,
      'markdown' => $this->markdown,
      'news_categories' => $this->news_categories,
      'rss_exempt' => $this->rss_exempt,
      'title' => $this->title,
    ]);
  }
}
