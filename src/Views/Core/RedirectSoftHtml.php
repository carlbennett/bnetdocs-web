<?php

namespace BNETDocs\Views\Core;

class RedirectSoftHtml extends \BNETDocs\Views\Base\Html
{
  public static function invoke(\BNETDocs\Interfaces\Model $model): void
  {
    if (!$model instanceof \BNETDocs\Models\Core\RedirectSoft)
    {
      throw new \BNETDocs\Exceptions\InvalidModelException($model);
    }

    (new \BNETDocs\Libraries\Core\Template($model, 'Core/RedirectSoft'))->invoke();
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
