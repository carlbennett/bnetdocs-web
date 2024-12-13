<?php

namespace BNETDocs\Views\Core;

class StatusPlain extends \BNETDocs\Views\Base\Plain
{
  public static function invoke(\BNETDocs\Interfaces\Model $model): void
  {
    if (!$model instanceof \BNETDocs\Models\Core\Status)
    {
      throw new \BNETDocs\Exceptions\InvalidModelException($model);
    }

    $serialized = $model->jsonSerialize(); /* to drop _responseCode, etc. from Base */
    echo \BNETDocs\Libraries\Core\ArrayFlattener::flatten($serialized);
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
