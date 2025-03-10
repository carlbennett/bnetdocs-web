<?php

namespace BNETDocs\Controllers\User;

use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\Core\Router;
use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Libraries\User\Authentication;

class Logout extends \BNETDocs\Controllers\Base
{
  public function __construct()
  {
    $this->model = new \BNETDocs\Models\User\Logout();
  }

  public function invoke(?array $args): bool
  {
    if (!$this->model->active_user)
    {
      $this->model->_responseCode = HttpCode::HTTP_BAD_REQUEST;
      return true;
    }

    $this->model->_responseCode = HttpCode::HTTP_OK;
    $this->model->error = false;
    if (Router::requestMethod() == Router::METHOD_POST) $this->tryLogout();
    return true;
  }

  protected function tryLogout(): void
  {
    $user = $this->model->active_user;
    if (Authentication::logout()) $this->model->active_user = &Authentication::$user;

    $event = Logger::initEvent(
      \BNETDocs\Libraries\EventLog\EventTypes::USER_LOGOUT,
      $user,
      getenv('REMOTE_ADDR'),
      [
        'error' => $this->model->error
      ]
    );

    if ($event->commit())
    {
      $embed = Logger::initDiscordEmbed($event, $user->getURI());
      Logger::logToDiscord($event, $embed);
    }
  }
}
