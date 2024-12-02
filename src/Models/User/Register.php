<?php

namespace BNETDocs\Models\User;

class Register extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $email = null;
    public ?string $error_extra = null;
    public ?\BNETDocs\Libraries\Core\Recaptcha $recaptcha = null;
    public ?string $username = null;
    public int $username_max_len = 0;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'email' => $this->email,
            'error_extra' => $this->error_extra,
            'recaptcha' => $this->recaptcha,
            'username' => $this->username,
            'username_max_len' => $this->username_max_len,
        ]);
    }
}
