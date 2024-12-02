<?php

namespace BNETDocs\Controllers\User;

use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Models\User\Verify as VerifyModel;

class Verify extends \BNETDocs\Controllers\Base
{
  /**
   * Constructs a Controller, typically to initialize properties.
   */
  public function __construct()
  {
    $this->model = new VerifyModel();
  }

  /**
   * Invoked by the Router class to handle the request.
   *
   * @param array|null $args The optional route arguments and any captured URI arguments.
   * @return boolean Whether the Router should invoke the configured View.
   */
  public function invoke(?array $args): bool
  {
    $q = \BNETDocs\Libraries\Core\Router::query();
    $this->model->token = $q['t'] ?? null;
    $this->model->user_id = $q['u'] ?? null;

    if (is_numeric($this->model->user_id))
    {
      try { $this->model->user = new \BNETDocs\Libraries\User\User((int) $this->model->user_id); }
      catch (\UnexpectedValueException) { $this->model->user = null; }
    }

    $user_token = $this->model->user ? $this->model->user->getVerifierToken() : null;
    if (!$this->model->user || $user_token !== $this->model->token)
    {
      $this->model->error = VerifyModel::ERROR_INVALID_TOKEN;
      $this->model->_responseCode = HttpCode::HTTP_BAD_REQUEST;
      return true;
    }

    try
    {
      $this->model->user->setVerified(true, true);
      $this->model->error = $this->model->user->commit() ? false : VerifyModel::ERROR_INTERNAL;
    }
    catch (\Throwable) { $this->model->error = VerifyModel::ERROR_INTERNAL; }

    if (!$this->model->error)
    {
      $event = Logger::initEvent(
        \BNETDocs\Libraries\EventLog\EventTypes::USER_VERIFIED,
        $this->model->user_id,
        getenv('REMOTE_ADDR'),
        ['error' => $this->model->error]
      );

      if ($event->commit())
      {
        $embed = Logger::initDiscordEmbed($event, $this->model->user->getURI());
        Logger::logToDiscord($event, $embed);
      }
    }

    $this->model->_responseCode = HttpCode::HTTP_OK;
    return true;
  }
}
