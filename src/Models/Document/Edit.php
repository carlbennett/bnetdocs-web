<?php

namespace BNETDocs\Models\Document;

class Edit extends \BNETDocs\Models\ActiveUser
{
  public bool $acl_allowed = false;
  public ?string $brief = null;
  public ?string $category = null;
  public ?array $comments = null;
  public ?string $content = null;
  public ?\BNETDocs\Libraries\Document $document = null;
  public ?int $document_id = null;
  public ?bool $markdown = null;
  public ?bool $published = null;
  public ?string $title = null;
}
