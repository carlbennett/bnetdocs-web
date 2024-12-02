<?php

namespace BNETDocs\Models\User;

class CreatePassword extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?string $input = null;
    public ?string $output = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'input' => $this->input,
            'output' => $this->output,
        ]);
    }
}
