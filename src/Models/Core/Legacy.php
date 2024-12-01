<?php

namespace BNETDocs\Models\Core;

class Legacy extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $did = null;
    public ?bool $is_legacy = null;
    public ?string $lang = null;
    public ?string $nid = null;
    public ?string $op = null;
    public ?string $pid = null;
    public ?string $url = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'did' => $this->did,
            'is_legacy' => $this->is_legacy,
            'lang' => $this->lang,
            'nid' => $this->nid,
            'op' => $this->op,
            'pid' => $this->pid,
            'url' => $this->url,
        ]);
    }
}
