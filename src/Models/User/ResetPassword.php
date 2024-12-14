<?php

namespace BNETDocs\Models\User;

class ResetPassword extends \BNETDocs\Models\Core\HttpForm implements \JsonSerializable
{
    public const ERROR_BAD_EMAIL = 'BAD_EMAIL';
    public const ERROR_BAD_TOKEN = 'BAD_TOKEN';
    public const ERROR_EMPTY_EMAIL = 'EMPTY_EMAIL';
    public const ERROR_PASSWORD_CONTAINS_EMAIL = 'PASSWORD_CONTAINS_EMAIL';
    public const ERROR_PASSWORD_CONTAINS_USERNAME = 'PASSWORD_CONTAINS_USERNAME';
    public const ERROR_PASSWORD_MISMATCH = 'PASSWORD_MISMATCH';
    public const ERROR_PASSWORD_TOO_LONG = 'PASSWORD_TOO_LONG';
    public const ERROR_PASSWORD_TOO_SHORT = 'PASSWORD_TOO_SHORT';
    public const ERROR_USER_DISABLED = 'USER_DISABLED';
    public const ERROR_USER_NOT_FOUND = 'USER_NOT_FOUND';

    public ?string $email = null;
    public ?string $pw1 = null;
    public ?string $pw2 = null;
    public ?string $token = null;
    public ?\BNETDocs\Libraries\User\User $user = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'email' => $this->email,
            'pw1' => $this->pw1,
            'pw2' => $this->pw2,
            'token' => $this->token,
            'user' => $this->user,
        ]);
    }
}
