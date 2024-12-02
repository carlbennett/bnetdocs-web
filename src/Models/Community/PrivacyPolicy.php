<?php

namespace BNETDocs\Models\Community;

class PrivacyPolicy extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $data_location = null;
    public ?string $email_domain = null;
    public ?string $email_mailbox = null;
    public ?string $organization = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'data_location' => $this->data_location,
            'email_domain' => $this->email_domain,
            'email_mailbox' => $this->email_mailbox,
            'organization' => $this->organization,
        ]);
    }
}
