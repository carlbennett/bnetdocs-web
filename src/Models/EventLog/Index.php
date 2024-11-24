<?php

namespace BNETDocs\Models\EventLog;

class Index extends \BNETDocs\Models\ActiveUser
{
  public bool $acl_allowed = false;
  public ?array $events = null;
  public int $limit = 0;
  public string $order = '';
  public int $page = 0;
  public int $pages = 0;
  public int $sum_events = 0;
}
