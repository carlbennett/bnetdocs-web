<?php

namespace BNETDocs\Models\User;

class View extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
  public ?array $comments = null;
  public int $contributions = 0;
  public ?array $documents = null;
  public ?array $news_posts = null;
  public ?array $packets = null;
  public ?array $servers = null;
  public int $sum_comments = 0;
  public int $sum_documents = 0;
  public int $sum_news_posts = 0;
  public int $sum_packets = 0;
  public int $sum_servers = 0;
  public ?\BNETDocs\Libraries\User\User $user = null;
  public ?int $user_id = null;
  public ?\BNETDocs\Libraries\User\Profile $user_profile = null;

  public function jsonSerialize(): mixed
  {
    $r = \array_merge(parent::jsonSerialize(), [
      'comments' => $this->comments,
      'contributions' => $this->contributions,
      'documents' => $this->documents,
      'news_posts' => $this->news_posts,
      'packets' => $this->packets,
      'servers' => $this->servers,
      'sum_comments' => $this->sum_comments,
      'sum_documents' => $this->sum_documents,
      'sum_news_posts' => $this->sum_news_posts,
      'sum_packets' => $this->sum_packets,
      'sum_servers' => $this->sum_servers,
      'user' => $this->user,
      'user_id' => $this->user_id,
      'user_profile' => $this->user_profile,
    ]);
    ksort($r);
    return $r;
  }
}
