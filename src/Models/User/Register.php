<?php

namespace BNETDocs\Models\User;

class Register extends \BNETDocs\Models\ActiveUser
{
    public ?string $email = null;
    public ?string $error_extra = null;
    public ?\BNETDocs\Libraries\Core\Recaptcha $recaptcha = null;
    public ?string $username = null;
    public int $username_max_len = 0;
}
