<?php

namespace BNETDocs\Views\Server;

class CreateHtml extends \BNETDocs\Views\Base\Html
{
  public static function invoke(\BNETDocs\Interfaces\Model $model): void
  {
    if (!$model instanceof \BNETDocs\Models\Server\Form)
    {
      throw new \BNETDocs\Exceptions\InvalidModelException($model);
    }

    (new \BNETDocs\Libraries\Core\Template($model, 'Server/Form'))->invoke();
    $model->_responseHeaders['Content-Type'] = self::mimeType();
  }
}
