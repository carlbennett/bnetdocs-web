<?php

namespace BNETDocs\Views\Core;

class StatusJson extends \BNETDocs\Views\Base\Json
{
  public static function invoke(\BNETDocs\Interfaces\Model $model): void
  {
    if (!$model instanceof \BNETDocs\Models\Core\Status)
    {
      throw new \BNETDocs\Exceptions\InvalidModelException($model);
    }

    echo json_encode($model->status, self::jsonFlags());
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
