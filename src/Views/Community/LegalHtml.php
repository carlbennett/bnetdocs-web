<?php

namespace BNETDocs\Views\Community;

class LegalHtml extends \BNETDocs\Views\Base\Html
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\Community\Legal)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'Community/Legal'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
