<?php

namespace BNETDocs\Models\Community;

class Credits extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public array|false $top_contributors_by_comments = false;
    public array|false $top_contributors_by_documents = false;
    public array|false $top_contributors_by_news_posts = false;
    public array|false $top_contributors_by_packets = false;
    public array|false $top_contributors_by_servers = false;
    public int|false $total_users = false;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'top_contributors_by_comments' => $this->top_contributors_by_comments,
            'top_contributors_by_documents' => $this->top_contributors_by_documents,
            'top_contributors_by_news_posts' => $this->top_contributors_by_news_posts,
            'top_contributors_by_packets' => $this->top_contributors_by_packets,
            'top_contributors_by_servers' => $this->top_contributors_by_servers,
            'total_users' => $this->total_users,
        ]);
    }
}
