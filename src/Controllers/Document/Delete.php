<?php

namespace BNETDocs\Controllers\Document;

use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\Core\Router;
use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Models\Document\Delete as DeleteModel;

class Delete extends \BNETDocs\Controllers\Base
{
  public function __construct()
  {
    $this->model = new DeleteModel();
  }

  public function invoke(?array $args): bool
  {
    $this->model->acl_allowed = $this->model->active_user
      && $this->model->active_user->getOption(\BNETDocs\Libraries\User\User::OPTION_ACL_DOCUMENT_DELETE);

    if (!$this->model->acl_allowed)
    {
      $this->model->_responseCode = HttpCode::HTTP_FORBIDDEN;
      $this->model->error = $this->model->active_user ? DeleteModel::ERROR_ACL_NOT_SET : DeleteModel::ERROR_NOT_LOGGED_IN;
      return true;
    }

    $q = Router::query();
    $this->model->id = isset($q['id']) ? (int) $q['id'] : null;

    try { if (!is_null($this->model->id)) $this->model->document = new \BNETDocs\Libraries\Document($this->model->id); }
    catch (\UnexpectedValueException) { $this->model->document = null; }

    if (!$this->model->document)
    {
      $this->model->_responseCode = HttpCode::HTTP_NOT_FOUND;
      $this->model->error = DeleteModel::ERROR_NOT_FOUND;
      return true;
    }

    $this->model->title = $this->model->document->getTitle();

    if (Router::requestMethod() == Router::METHOD_POST)
    {
      $this->model->error = $this->model->document->deallocate() ? false : DeleteModel::ERROR_INTERNAL;

      $event = Logger::initEvent(
        \BNETDocs\Libraries\EventLog\EventTypes::DOCUMENT_DELETED,
        $this->model->active_user,
        getenv('REMOTE_ADDR'),
        [
          'error' => $this->model->error,
          'document' => $this->model->document,
        ]
      );

      if ($event->commit())
      {
        $content = $this->model->document->getContent(false);
        $markdown = $this->model->document->isMarkdown();
        $user = $this->model->document->getUser();
        $embed = Logger::initDiscordEmbed($event, $this->model->document->getURI(), [
          'Title' => $this->model->document->getTitle(),
          'Brief' => $this->model->document->getBrief(false),
          'Markdown' => $markdown ? ':white_check_mark:' : ':x:',
          'Authored by' => !\is_null($user) ? $user->getAsMarkdown() : '*Anonymous*',
          'Deleted by' => $this->model->active_user->getAsMarkdown(),
        ]);
        $embed->setDescription($markdown ? $content : '```' . \PHP_EOL . $content . \PHP_EOL . '```');
        Logger::logToDiscord($event, $embed);
      }
    }

    $this->model->_responseCode = HttpCode::HTTP_OK;
    return true;
  }
}
