<?php

namespace BNETDocs\Views\Core;

class MaintenanceHtml extends \BNETDocs\Views\Base\Html
{
  public static function invoke(\BNETDocs\Interfaces\Model $model): void
  {
    if (!$model instanceof \BNETDocs\Models\Core\Maintenance)
    {
      throw new \BNETDocs\Exceptions\InvalidModelException($model);
    }

    (new \BNETDocs\Libraries\Core\Template($model, 'Core/Maintenance'))->invoke();
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
