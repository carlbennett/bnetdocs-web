<?php

namespace BNETDocs\Exceptions;

class TemplateNotFoundException extends \InvalidArgumentException
{
  public function __construct(\BNETDocs\Libraries\Core\Template|string $value, \Throwable $previous = null)
  {
    $v = is_string($value) ? $value : $value->getTemplateFile();
    parent::__construct(\sprintf('Template not found: %s', $v), 0, $previous);
  }
}
