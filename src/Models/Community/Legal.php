<?php

namespace BNETDocs\Models\Community;

class Legal extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $email_domain = null;
    public ?string $email_mailbox = null;
    public string|false|null $license = null;
    public string|array|null $license_version = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'email_domain' => $this->email_domain,
            'email_mailbox' => $this->email_mailbox,
            'license' => $this->license,
            'license_version' => $this->license_version,
        ]);
    }
}
