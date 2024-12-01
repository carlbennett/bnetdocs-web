<?php

namespace BNETDocs\Views\Core;

class RedirectSoftJson extends \BNETDocs\Views\Base\Json
{
  public static function invoke(\BNETDocs\Interfaces\Model $model): void
  {
    if (!$model instanceof \BNETDocs\Models\Core\RedirectSoft)
    {
      throw new \BNETDocs\Exceptions\InvalidModelException($model);
    }

    echo \json_encode($model, self::jsonFlags());
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
