<?php

namespace BNETDocs\Controllers\Community;

use \BNETDocs\Libraries\Core\Config;

class PrivacyPolicy extends \BNETDocs\Controllers\Base
{
    /**
     * Constructs a Controller, typically to initialize properties.
     */
    public function __construct()
    {
        $this->model = new \BNETDocs\Models\Community\PrivacyPolicy();
    }

    /**
     * Invoked by the Router class to handle the request.
     *
     * @param array|null $args The optional route arguments and any captured URI arguments.
     * @return boolean Whether the Router should invoke the configured View.
     */
    public function invoke(?array $args): bool
    {
        $this->model->data_location = Config::get('bnetdocs.privacy.data_location');
        $this->model->email_domain = Config::get('bnetdocs.privacy.contact.email_domain');
        $this->model->email_mailbox = Config::get('bnetdocs.privacy.contact.email_mailbox');
        $this->model->organization = Config::get('bnetdocs.privacy.organization');
        $this->model->_responseCode = \BNETDocs\Libraries\Core\HttpCode::HTTP_OK;
        return true;
    }
}
