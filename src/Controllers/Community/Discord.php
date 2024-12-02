<?php

namespace BNETDocs\Controllers\Community;

use \BNETDocs\Libraries\Core\HttpCode;

class Discord extends \BNETDocs\Controllers\Base
{
    /**
     * Constructs a Controller, typically to initialize properties.
     */
    public function __construct()
    {
        $this->model = new \BNETDocs\Models\Community\Discord();
    }

    /**
     * Invoked by the Router class to handle the request.
     *
     * @param array|null $args The optional route arguments and any captured URI arguments.
     * @return boolean Whether the Router should invoke the configured View.
     */
    public function invoke(?array $args): bool
    {
        $config = &\CarlBennett\MVC\Libraries\Common::$config->discord;

        $this->model->discord_server_id = $config->server_id;
        $this->model->discord_url = \sprintf('https://discord.gg/%s', $config->invite_code);
        $this->model->enabled = $config->enabled;

        $this->model->_responseCode = ($this->model->enabled ? HttpCode::HTTP_OK : HttpCode::HTTP_SERVICE_UNAVAILABLE);
        return true;
    }
}
