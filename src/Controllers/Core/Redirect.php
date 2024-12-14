<?php

namespace BNETDocs\Controllers\Core;

class Redirect extends \BNETDocs\Controllers\Base
{
    /**
     * Constructs a Controller, typically to initialize properties.
     */
    public function __construct()
    {
        $this->model = new \BNETDocs\Models\Core\Redirect();
    }

    /**
     * Invoked by the Router class to handle the request.
     *
     * @param array|null $args The optional route arguments and any captured URI arguments.
     * @return boolean Whether the Router should invoke the configured View.
     */
    public function invoke(?array $args): bool
    {
        $this->model->location = \BNETDocs\Libraries\Core\UrlFormatter::format(
            !empty($args) && \is_string($args[0]) ? \array_shift($args) : null
        );
        $this->model->_responseCode = !empty($args) && \is_int($args[0]) ?
            \array_shift($args) : \BNETDocs\Libraries\Core\HttpCode::HTTP_FOUND;
        $this->model->_responseHeaders['Location'] = $this->model->location;
        return true;
    }
}
