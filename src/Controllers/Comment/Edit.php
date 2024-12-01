<?php

namespace BNETDocs\Controllers\Comment;

use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\Core\Router;
use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Models\Comment\Edit as EditModel;

class Edit extends \BNETDocs\Controllers\Base
{
  public function __construct()
  {
    $this->model = new EditModel();
  }

  public function invoke(?array $args): bool
  {
    $q = Router::query();
    $this->model->id = isset($q['id']) ? (int) $q['id'] : null;
    $this->model->content = $q['content'] ?? null;

    try { if (!is_null($this->model->id)) $this->model->comment = new \BNETDocs\Libraries\Comment($this->model->id); }
    catch (\UnexpectedValueException) { $this->model->comment = null; }

    $this->model->acl_allowed = $this->model->active_user && (
      $this->model->active_user->getOption(\BNETDocs\Libraries\User\User::OPTION_ACL_COMMENT_MODIFY) ||
      ($this->model->comment && $this->model->active_user->getId() === $this->model->comment->getUserId())
    );

    if (!$this->model->acl_allowed)
    {
      $this->model->_responseCode = HttpCode::HTTP_FORBIDDEN;
      $this->model->error = $this->model->active_user ? EditModel::ERROR_ACL_NOT_SET : EditModel::ERROR_NOT_LOGGED_IN;
      return true;
    }

    if (!$this->model->comment)
    {
      $this->model->_responseCode = HttpCode::HTTP_NOT_FOUND;
      $this->model->error = EditModel::ERROR_NOT_FOUND;
      return true;
    }

    $this->model->parent_id = $this->model->comment->getParentId();
    $this->model->parent_type = $this->model->comment->getParentType();
    $this->model->return_url = $this->model->comment->getParentUrl();

    if (is_null($this->model->content))
    {
      $this->model->content = $this->model->comment->getContent(false);
    }

    if (empty($this->model->content))
    {
      $this->model->_responseCode = HttpCode::HTTP_BAD_REQUEST;
      $this->model->error = EditModel::ERROR_EMPTY_CONTENT;
      return true;
    }

    if (Router::requestMethod() == Router::METHOD_POST) $this->tryEdit();
    $this->model->_responseCode = !$this->model->error ? HttpCode::HTTP_OK : HttpCode::HTTP_INTERNAL_SERVER_ERROR;
    return true;
  }

  protected function tryEdit(): void
  {
    $this->model->comment->setContent($this->model->content);
    $this->model->comment->incrementEdited();

    if (!$this->model->comment->commit())
    {
      $this->model->error = EditModel::ERROR_INTERNAL;
      return;
    }

    $this->model->error = false;

    $event = Logger::initEvent(
      $this->model->comment->getParentTypeEditedEventId(),
      $this->model->active_user,
      getenv('REMOTE_ADDR'),
      [
        'comment'     => $this->model->comment,
        'error'       => $this->model->error,
        'parent_type' => $this->model->parent_type,
        'parent_id'   => $this->model->parent_id
      ]
    );

    if ($event->commit())
    {
      $comment_user = $this->model->comment->getUser();
      $fields = [];
      if (!\is_null($comment_user)) $fields['Authored by'] = $comment_user->getAsMarkdown();
      $fields['Edited by'] = $this->model->active_user->getAsMarkdown();
      $embed = Logger::initDiscordEmbed($event, $this->model->comment->getParentUrl() . '#comments', $fields);
      if (!\is_null($comment_user)) $embed->setAuthor($comment_user->getAsDiscordEmbedAuthor());
      $embed->setDescription($this->model->comment->getContent(false));
      Logger::logToDiscord($event, $embed);
    }
  }
}
