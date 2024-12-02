<?php

namespace BNETDocs\Views\Community;

class LegalPlain extends \BNETDocs\Views\Base\Plain
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\Community\Legal)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        echo $model->license;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
