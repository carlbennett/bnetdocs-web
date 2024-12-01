<?php

namespace BNETDocs\Models\News;

class View extends \BNETDocs\Models\ActiveUser
{
  public bool $acl_allowed = false;
  public ?array $comments = null;
  public ?\BNETDocs\Libraries\News\Post $news_post = null;
  public ?int $news_post_id = null;
}
