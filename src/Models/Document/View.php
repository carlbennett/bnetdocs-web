<?php

namespace BNETDocs\Models\Document;

class View extends \BNETDocs\Models\ActiveUser
{
  public bool $acl_allowed = false;
  public ?array $comments = null;
  public ?\BNETDocs\Libraries\Document $document = null;
  public ?int $document_id = null;
}
