<?php

namespace BNETDocs\Controllers\Core;

use \BNETDocs\Libraries\Core\HttpCode;

class PhpInfo extends \BNETDocs\Controllers\Base
{
  /**
   * Constructs a Controller, typically to initialize properties.
   */
  public function __construct()
  {
    $this->model = new \BNETDocs\Models\Core\PhpInfo();
  }

  /**
   * Invoked by the Router class to handle the request.
   *
   * @param array|null $args The optional route arguments and any captured URI arguments.
   * @return boolean Whether the Router should invoke the configured View.
   */
  public function invoke(?array $args): bool
  {
    if (!($this->model->active_user && $this->model->active_user->getOption(\BNETDocs\Libraries\User\User::OPTION_ACL_PHPINFO)))
    {
      $this->model->phpinfo = null;
      $this->model->_responseCode = HttpCode::HTTP_UNAUTHORIZED;
    }
    else
    {
      \ob_start();
      \phpinfo(INFO_ALL);
      $this->model->phpinfo = \ob_get_clean();
      $this->model->_responseCode = HttpCode::HTTP_OK;
    }
    return true;
  }
}
