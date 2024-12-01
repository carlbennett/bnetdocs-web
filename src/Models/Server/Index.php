<?php

namespace BNETDocs\Models\Server;

class Index extends \BNETDocs\Models\ActiveUser
{
    public ?array $server_types = null;
    public ?array $servers = null;
    public ?array $status_bitmasks = null;
}
