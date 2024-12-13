<?php

namespace BNETDocs\Controllers\Community;

use \BNETDocs\Libraries\Core\Config;

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
        $this->model->donations_btc_address = Config::get('bnetdocs.donations.btc_address');
        $this->model->donations_email_address = Config::get('bnetdocs.donations.email_address');
        $this->model->donations_paypal_url = Config::get('bnetdocs.donations.paypal_url');
        $this->model->donations_user_id = Config::get('bnetdocs.donations.user_id');
        if (is_int($this->model->donations_user_id))
        {
            try
            {
                $this->model->donations_user = new \BNETDocs\Libraries\User\User($this->model->donations_user_id);
            }
            catch (\BNETDocs\Exceptions\UserNotFoundException)
            {
                $this->model->donations_user = null;
            }
        }
        $this->model->_responseCode = \BNETDocs\Libraries\Core\HttpCode::HTTP_OK;
        return true;
    }
}
