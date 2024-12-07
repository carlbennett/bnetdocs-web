<?php

namespace BNETDocs\Controllers\Community;

class Donate extends \BNETDocs\Controllers\Base
{
    /**
     * Constructs a Controller, typically to initialize properties.
     */
    public function __construct()
    {
        $this->model = new \BNETDocs\Models\Community\Donate();
    }

    /**
     * Invoked by the Router class to handle the request.
     *
     * @param array|null $args The optional route arguments and any captured URI arguments.
     * @return boolean Whether the Router should invoke the configured View.
     */
    public function invoke(?array $args): bool
    {
        $this->model->donations = \BNETDocs\Libraries\Core\Config::get('bnetdocs.donations');
        $this->model->_responseCode = \BNETDocs\Libraries\Core\HttpCode::HTTP_OK;
        return true;
    }
}
