<?php

namespace BNETDocs\Models\Document;

class Index extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public array|false $documents = false;
    public string $order = '';
    public int $sum_documents = 0;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'documents' => $this->documents,
            'order' => $this->order,
            'sum_documents' => $this->sum_documents,
        ]);
    }
}
