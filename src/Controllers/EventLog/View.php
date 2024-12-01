<?php

namespace BNETDocs\Controllers\EventLog;

use \BNETDocs\Libraries\Core\HttpCode;

class View extends \BNETDocs\Controllers\Base
{
  public function __construct()
  {
    $this->model = new \BNETDocs\Models\EventLog\View();
  }

  public function invoke(?array $args): bool
  {
    $this->model->acl_allowed = $this->model->active_user
    && $this->model->active_user->getOption(\BNETDocs\Libraries\User\User::OPTION_ACL_EVENT_LOG_VIEW);

    if (!$this->model->acl_allowed)
    {
      $this->model->_responseCode = HttpCode::HTTP_FORBIDDEN;
      return true;
    }

    $q = \BNETDocs\Libraries\Core\Router::query();
    $this->model->id = isset($q['id']) ? (int) $q['id'] : null;

    try { if (!is_null($this->model->id)) $this->model->event = new \BNETDocs\Libraries\EventLog\Event($this->model->id); }
    catch (\UnexpectedValueException) { $this->model->event = null; }

    $this->model->_responseCode = $this->model->event ? HttpCode::HTTP_OK : HttpCode::HTTP_NOT_FOUND;
    return true;
  }
}
