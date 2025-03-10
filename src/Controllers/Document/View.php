<?php

namespace BNETDocs\Controllers\Document;

use \BNETDocs\Libraries\Comment;
use \BNETDocs\Libraries\Core\HttpCode;

class View extends \BNETDocs\Controllers\Base
{
  /**
   * Constructs a Controller, typically to initialize properties.
   */
  public function __construct()
  {
    $this->model = new \BNETDocs\Models\Document\View();
  }

  /**
   * Invoked by the Router class to handle the request.
   *
   * @param array|null $args The optional route arguments and any captured URI arguments.
   * @return boolean Whether the Router should invoke the configured View.
   */
  public function invoke(?array $args): bool
  {
    $this->model->document_id = array_shift($args);

    try { $this->model->document = new \BNETDocs\Libraries\Document($this->model->document_id); }
    catch (\BNETDocs\Exceptions\DocumentNotFoundException) { $this->model->document = null; }

    if ($this->model->document && !$this->model->document->isPublished()
      && !($this->model->active_user && $this->model->active_user->isStaff()))
    {
      $this->model->_responseCode = HttpCode::HTTP_FORBIDDEN;
      $this->model->document = null;
      return true;
    }

    if ($this->model->document)
    {
      $this->model->comments = Comment::getAll(Comment::PARENT_TYPE_DOCUMENT, $this->model->document_id);
    }

    $this->model->_responseCode = $this->model->document ? HttpCode::HTTP_OK : HttpCode::HTTP_NOT_FOUND;
    return true;
  }
}
