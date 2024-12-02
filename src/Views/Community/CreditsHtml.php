<?php

namespace BNETDocs\Views\Community;

class CreditsHtml extends \BNETDocs\Views\Base\Html
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\Community\Credits)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'Community/Credits'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
