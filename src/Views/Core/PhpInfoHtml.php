<?php

namespace BNETDocs\Views\Core;

class PhpInfoHtml extends \BNETDocs\Views\Base\Html
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\Core\PhpInfo)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'Core/PhpInfo'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
