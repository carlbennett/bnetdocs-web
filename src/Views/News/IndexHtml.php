<?php

namespace BNETDocs\Views\News;

class IndexHtml extends \BNETDocs\Views\Base\Html
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\News\Index)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'News/Index'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
