<?php

namespace BNETDocs\Views\News;

class IndexRSS extends \BNETDocs\Views\Base\RSS
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\News\Index)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'News/Index.rss'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
