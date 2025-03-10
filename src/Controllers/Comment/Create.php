<?php

namespace BNETDocs\Controllers\Comment;

use \BNETDocs\Libraries\Comment;
use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\Core\Router;
use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Models\Comment\Create as CreateModel;

class Create extends \BNETDocs\Controllers\Base
{
  public function __construct()
  {
    $this->model = new CreateModel();
  }

  public function invoke(?array $args): bool
  {
    $this->model->acl_allowed = $this->model->active_user
      && $this->model->active_user->getOption(\BNETDocs\Libraries\User\User::OPTION_ACL_COMMENT_CREATE);

    if (!$this->model->acl_allowed)
    {
      $this->model->_responseCode = HttpCode::HTTP_FORBIDDEN;
      $this->model->error = $this->model->active_user ? CreateModel::ERROR_ACL_NOT_SET : CreateModel::ERROR_NOT_LOGGED_IN;
      $this->model->response = ['error' => 'Unauthorized'];
    }
    else if (Router::requestMethod() !== Router::METHOD_POST)
    {
      $this->model->_responseCode = HttpCode::HTTP_METHOD_NOT_ALLOWED;
      $this->model->_responseHeaders['Allow'] = Router::METHOD_POST;
      $this->model->response = ['error' => 'Method Not Allowed', 'allow' => [Router::METHOD_POST]];
    }
    else
    {
      $this->model->_responseCode = $this->createComment();
    }

    if (!empty($this->model->origin) && $this->model->_responseCode >= 300 && $this->model->_responseCode <= 399)
      $this->model->_responseHeaders['Location'] = $this->model->origin;

    return true;
  }

  protected function createComment(): int
  {
    $q = Router::query();
    $pid = (isset($q['parent_id']) ? (int) $q['parent_id'] : null);
    $pt = (isset($q['parent_type']) ? (int) $q['parent_type'] : null);
    $content = $q['content'] ?? null;

    if (empty($content))
    {
      $this->model->error = CreateModel::ERROR_EMPTY_CONTENT;
      $this->model->response = ['error' => 'Empty Content'];
      return HttpCode::HTTP_BAD_REQUEST;
    }

    $this->model->comment = new Comment(null);
    $this->model->comment->setContent($content);

    $this->model->comment->setParentId($pid);
    $this->model->comment->setParentType($pt);
    $this->model->origin = $this->model->comment->getParentUrl();

    $this->model->comment->setUser($this->model->active_user);

    if (!$this->model->comment->commit())
    {
      $this->model->error = CreateModel::ERROR_INTERNAL;
      return HttpCode::HTTP_INTERNAL_SERVER_ERROR;
    }

    $this->model->error = false;
    $this->model->response = [
      'content' => $content,
      'error' => $this->model->error,
      'origin' => $this->model->origin,
      'parent_id' => $pid,
      'parent_type' => $pt
    ];

    $event = Logger::initEvent(
      $this->model->comment->getParentTypeCreatedEventId(),
      $this->model->active_user,
      getenv('REMOTE_ADDR'),
      $this->model->response
    );

    if ($event->commit())
    {
      $embed = Logger::initDiscordEmbed($event, $this->model->comment->getParentUrl() . '#comments');
      $embed->setDescription($content);
      Logger::logToDiscord($event, $embed);
    }

    return HttpCode::HTTP_SEE_OTHER;
  }
}
