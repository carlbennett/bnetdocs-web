<?php

namespace BNETDocs\Models\Core;

class AccessControl extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    /**
     * Stores whether $this->active_user is permitted to access this controller.
     *
     * @var bool
     */
    public bool $acl_allowed = false;

    /**
     * Implements the JSON serialization function from the JsonSerializable interface.
     */
    public function jsonSerialize(): mixed
    {
        return \array_merge(['acl_allowed' => $this->acl_allowed], parent::jsonSerialize());
    }
}
