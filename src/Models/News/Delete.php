<?php

namespace BNETDocs\Models\News;

class Delete extends \BNETDocs\Models\ActiveUser
{
  public bool $acl_allowed = false;
  public ?int $id = null;
  public ?\BNETDocs\Libraries\News\Post $news_post = null;
  public string $title = '';
  public ?\BNETDocs\Libraries\User $user = null;
}
