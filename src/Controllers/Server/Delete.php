<?php

namespace BNETDocs\Controllers\Server;

use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\Core\Router;
use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Models\Server\Form as FormModel;

class Delete extends \BNETDocs\Controllers\Base
{
  public const S_DISABLED = ':no_entry: Disabled';
  public const S_ONLINE   = ':white_check_mark: Online';
  public const S_OFFLINE  = ':x: Offline';

  public function __construct()
  {
    $this->model = new FormModel();
  }

  public function invoke(?array $args): bool
  {
    $this->model->server_delete = true;

    if (!($this->model->active_user && $this->model->active_user->getOption(\BNETDocs\Libraries\User\User::OPTION_ACL_SERVER_DELETE)))
    {
      $this->model->_responseCode = HttpCode::HTTP_FORBIDDEN;
      $this->model->error = $this->model->active_user ? FormModel::ERROR_ACL_NOT_SET : FormModel::ERROR_NOT_LOGGED_IN;
      return true;
    }

    $id = Router::query()['id'] ?? null;
    if (!is_numeric($id))
    {
      $this->model->_responseCode = HttpCode::HTTP_BAD_REQUEST;
      $this->model->error = FormModel::ERROR_NOT_FOUND;
      return true;
    }
    $id = (int) $id;

    try { $this->model->server = new \BNETDocs\Libraries\Server\Server($id); }
    catch (\UnexpectedValueException) { $this->model->server = null; }

    if (!$this->model->server)
    {
      $this->model->_responseCode = HttpCode::HTTP_NOT_FOUND;
      $this->model->error = FormModel::ERROR_NOT_FOUND;
      return true;
    }

    $this->model->_responseCode = HttpCode::HTTP_OK;
    if (Router::requestMethod() == Router::METHOD_POST) $this->handlePost();
    return true;
  }

  protected function handlePost(): void
  {
    $this->model->error = $this->model->server->deallocate() ? false : FormModel::ERROR_INTERNAL;
    if ($this->model->error === false)
    {
      $event = Logger::initEvent(
        \BNETDocs\Libraries\EventLog\EventTypes::SERVER_DELETED,
        $this->model->active_user,
        getenv('REMOTE_ADDR'),
        $this->model->server
      );

      if ($event->commit())
      {
        $embed = Logger::initDiscordEmbed($event, $this->model->server->getURI(), [
          'Type' => $this->model->server->getType()->getLabel(),
          'Label' => $this->model->server->getLabel(),
          'Server' => $this->model->server->getAddress() . ':' . $this->model->server->getPort(),
          'Status' => $this->model->server->isDisabled() ? self::S_DISABLED : (
            $this->model->server->isOnline() ? self::S_ONLINE : self::S_OFFLINE
          ),
        ]);
        Logger::logToDiscord($event, $embed);
      }
    }
  }
}
