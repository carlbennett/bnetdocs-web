<?php

namespace BNETDocs\Models;

class Legacy extends ActiveUser
{
    public ?string $did = null;
    public ?bool $is_legacy = null;
    public ?string $lang = null;
    public ?string $nid = null;
    public ?string $op = null;
    public ?string $pid = null;
    public ?string $url = null;
}
