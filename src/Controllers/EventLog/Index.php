<?php

namespace BNETDocs\Controllers\EventLog;

use \BNETDocs\Libraries\Core\HttpCode;
use \BNETDocs\Libraries\EventLog\Event;

class Index extends \BNETDocs\Controllers\Base
{
  const PAGINATION_LIMIT_DEF = 15;  // The default amount of items per page.
  const PAGINATION_LIMIT_MIN = 5;   // The least amount of items per page.
  const PAGINATION_LIMIT_MAX = 250; // The most amount of items per page.

  public function __construct()
  {
    $this->model = new \BNETDocs\Models\EventLog\Index();
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
    $this->model->order = $q['order'] ?? 'datetime-desc';

    switch ($this->model->order)
    {
      case 'id-asc': $order = ['id', false]; break;
      case 'id-desc': $order = ['id', true]; break;
      case 'datetime-asc': $order = ['event_datetime', false]; break;
      case 'datetime-desc': $order = ['event_datetime', true]; break;
      default: $order = null;
    }

    $this->model->page = (isset($q['page']) ? (int) $q['page'] : 0);
    $this->model->limit = (isset($q['limit']) ? (int) $q['limit'] : self::PAGINATION_LIMIT_DEF);

    if ($this->model->page < 1) $this->model->page = 1;
    if ($this->model->limit < self::PAGINATION_LIMIT_MIN) $this->model->limit = self::PAGINATION_LIMIT_MIN;
    if ($this->model->limit > self::PAGINATION_LIMIT_MAX) $this->model->limit = self::PAGINATION_LIMIT_MAX;
    $this->model->pages = ceil(Event::countAll() / $this->model->limit);
    if ($this->model->page > $this->model->pages) $this->model->page = $this->model->pages;

    $this->model->events = Event::allocateAll(
      null,
      $order[0], $order[1],
      $this->model->limit,
      $this->model->limit * ( $this->model->page - 1 )
    );

    $this->model->sum_events = count($this->model->events);
    $this->model->_responseCode = HttpCode::HTTP_OK;
    return true;
  }
}
