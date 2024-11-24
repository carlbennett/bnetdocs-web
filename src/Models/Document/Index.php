<?php

namespace BNETDocs\Models\Document;

class Index extends \BNETDocs\Models\ActiveUser
{
  public array|false $documents = false;
  public string $order = '';
  public int $sum_documents = 0;
}
