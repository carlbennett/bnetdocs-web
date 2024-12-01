<?php

namespace BNETDocs\Views\Core;

class NotFoundHtml extends \BNETDocs\Views\Base\Html
{
  public static function invoke(\BNETDocs\Interfaces\Model $model): void
  {
    if (!$model instanceof \BNETDocs\Models\Core\NotFound)
    {
      throw new \BNETDocs\Exceptions\InvalidModelException($model);
    }

    (new \BNETDocs\Libraries\Core\Template($model, 'Core/NotFound'))->invoke();
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
