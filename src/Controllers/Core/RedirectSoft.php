<?php

namespace BNETDocs\Controllers\Core;

class RedirectSoft extends \BNETDocs\Controllers\Base
{
  /**
   * Constructs a Controller, typically to initialize properties.
   */
  public function __construct()
  {
    $this->model = new \BNETDocs\Models\Core\RedirectSoft();
  }

  /**
   * Invoked by the Router class to handle the request.
   *
   * @param array|null $args The optional route arguments and any captured URI arguments.
   * @return boolean Whether the Router should invoke the configured View.
   */
  public function invoke(?array $args): bool
  {
    $this->model->location = \CarlBennett\MVC\Libraries\Common::relativeUrlToAbsolute(\array_shift($args));
    $this->model->_responseCode = \BNETDocs\Libraries\Core\HttpCode::HTTP_FOUND;
    $this->model->_responseHeaders['Location'] = $this->model->location;
    return true;
  }
}
