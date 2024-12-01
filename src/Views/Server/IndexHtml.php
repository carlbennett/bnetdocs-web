<?php

namespace BNETDocs\Views\Server;

class IndexHtml extends \BNETDocs\Views\Base\Html
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\Server\Index)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'Server/Index'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
