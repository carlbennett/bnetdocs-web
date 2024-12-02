<?php /* vim: set colorcolumn= expandtab shiftwidth=2 softtabstop=2 tabstop=4 smarttab: */

namespace BNETDocs\Controllers\Packet;

use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\Core\Router;
use \BNETDocs\Libraries\Discord\EmbedField as DiscordEmbedField;
use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Models\Packet\Delete as DeleteModel;

class Delete extends \BNETDocs\Controllers\Base
{
  public function __construct()
  {
    $this->model = new DeleteModel();
  }

  public function invoke(?array $args): bool
  {
    $this->model->acl_allowed = $this->model->active_user
      && $this->model->active_user->getOption(\BNETDocs\Libraries\User\User::OPTION_ACL_PACKET_DELETE);

    if (!$this->model->acl_allowed)
    {
      $this->model->_responseCode = HttpCode::HTTP_UNAUTHORIZED;
      $this->model->error = $this->model->active_user ? DeleteModel::ERROR_ACL_NOT_SET : DeleteModel::ERROR_NOT_LOGGED_IN;
      return true;
    }

    $q = Router::query();
    $this->model->packet_id = $q['id'] ?? null;

    try { if (!is_null($this->model->packet_id)) $this->model->packet = new \BNETDocs\Libraries\Packet\Packet($this->model->packet_id); }
    catch (\UnexpectedValueException) { $this->model->packet = null; }

    if (!$this->model->packet)
    {
      $this->model->_responseCode = HttpCode::HTTP_NOT_FOUND;
      $this->model->error = DeleteModel::ERROR_NOT_FOUND;
      return true;
    }

    $this->model->label = $this->model->packet->getLabel();

    if (Router::requestMethod() == Router::METHOD_POST) $this->tryDelete();
    $this->model->_responseCode = $this->model->error ? HttpCode::HTTP_INTERNAL_SERVER_ERROR : HttpCode::HTTP_OK;
    return true;
  }

  protected function tryDelete(): void
  {
    $this->model->error = $this->model->packet->deallocate() ? false : DeleteModel::ERROR_INTERNAL;

    $event = Logger::initEvent(
      \BNETDocs\Libraries\EventLog\EventTypes::PACKET_DELETED,
      $this->model->active_user,
      getenv('REMOTE_ADDR'),
      [
        'error' => $this->model->error,
        'packet_id' => $this->model->packet_id,
        'packet' => $this->model->packet,
      ]
    );

    if ($event->commit())
    {
      $packet = $this->model->packet;

      $brief = $packet->getBrief(false);
      $format = $packet->getFormat();
      $remarks = $packet->getRemarks(false);

      $offset = 13; // char count of code block, end-of-line, and ellipsis addons
      if (\strlen($brief) - $offset > DiscordEmbedField::MAX_VALUE)
      {
        $brief = \substr($brief, 0, DiscordEmbedField::MAX_VALUE - $offset) . '…';
      }
      if (\strlen($format) - $offset > DiscordEmbedField::MAX_VALUE)
      {
        $format = \substr($format, 0, DiscordEmbedField::MAX_VALUE - $offset) . '…';
      }
      if (\strlen($remarks) - $offset > DiscordEmbedField::MAX_VALUE)
      {
        $remarks = \substr($remarks, 0, DiscordEmbedField::MAX_VALUE - $offset) . '…';
      }

      $used_by = '';
      foreach ($packet->getUsedBy() as $product)
      {
        if (!empty($used_by)) $used_by .= ', ';
        $used_by .= $product->getLabel();
      }
      if (empty($used_by)) $used_by = '*Unknown*';

      $embed = Logger::initDiscordEmbed($event, $packet->getURI(), [
        'Direction' => $packet->getDirectionLabel(),
        'Id' => $packet->getPacketId(true),
        'Name' => $packet->getName(),
        'Brief' => !empty($brief) ? $brief : '*empty*',

        'Deprecated' => $packet->isDeprecated() ? ':white_check_mark:' : ':x:',
        'Draft' => !$packet->isPublished() ? ':white_check_mark:' : ':x:',
        'Markdown' => $packet->isMarkdown() ? ':white_check_mark:' : ':x:',
        'In research' => $packet->isInResearch() ? ':white_check_mark:' : ':x:',

        'Application layer' => $packet->getApplicationLayer()->getLabel(),
        'Transport layer' => $packet->getTransportLayer()->getTag(),
        'Used by' => $used_by,

        'Authored by' => !\is_null($packet->getUserId()) ? $packet->getUser()->getAsMarkdown() : '*Anonymous*',
        'Deleted by' => $this->model->active_user->getAsMarkdown(),
      ]);
      $embed->addField(new DiscordEmbedField('Format', '```' . \PHP_EOL . $format . \PHP_EOL . '```', false));
      $embed->setDescription($packet->isMarkdown() ? $remarks : '```' . \PHP_EOL . $remarks . \PHP_EOL . '```');
      Logger::logToDiscord($event, $embed);
    }
  }
}
