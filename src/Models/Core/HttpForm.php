<?php

namespace BNETDocs\Models\Core;

class HttpForm extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    /**
     * The key-value store of the form.
     *
     * @var array
     */
    public array $form_fields = [];

    /**
     * Implements the JSON serialization function from the JsonSerializable interface.
     */
    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['form_fields' => $this->form_fields]);
    }
}
