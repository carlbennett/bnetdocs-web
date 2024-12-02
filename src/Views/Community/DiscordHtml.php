<?php

namespace BNETDocs\Views\Community;

class DiscordHtml extends \BNETDocs\Views\Base\Html
{
    public static function invoke(\BNETDocs\Interfaces\Model $model): void
    {
        if (!$model instanceof \BNETDocs\Models\Community\Discord)
        {
            throw new \BNETDocs\Exceptions\InvalidModelException($model);
        }

        (new \BNETDocs\Libraries\Core\Template($model, 'Community/Discord'))->invoke();
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
