<?php

namespace BNETDocs\Models\Community;

class Donate extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $donations_btc_address = null;
    public ?string $donations_email_address = null;
    public ?string $donations_paypal_url = null;
    public ?int $donations_user_id = null;
    public ?\BNETDocs\Libraries\User\User $donations_user = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'donations_btc_address' => $this->donations_btc_address,
            'donations_email_address' => $this->donations_email_address,
            'donations_paypal_url' => $this->donations_paypal_url,
            'donations_user_id' => $this->donations_user_id,
            'donations_user' => $this->donations_user,
        ]);
    }
}
