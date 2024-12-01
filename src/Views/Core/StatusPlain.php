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

    echo \BNETDocs\Libraries\Core\ArrayFlattener::flatten($model->status);
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
