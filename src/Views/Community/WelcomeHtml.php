<?php

namespace BNETDocs\Views\Community;

class WelcomeHtml extends \BNETDocs\Views\Base\Html
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\Community\Welcome)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'Community/Welcome'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
