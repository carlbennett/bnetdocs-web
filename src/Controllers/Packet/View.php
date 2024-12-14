<?php /* vim: set colorcolumn= expandtab shiftwidth=2 softtabstop=2 tabstop=4 smarttab: */
namespace BNETDocs\Controllers\Packet;

use \BNETDocs\Libraries\Comment;
use \BNETDocs\Libraries\Core\HttpCode;
use BNETDocs\Models\Packet\View as ViewModel;

class View extends \BNETDocs\Controllers\Base
{
  public function __construct()
  {
    $this->model = new ViewModel();
  }

  public function invoke(?array $args): bool
  {
    $this->model->packet_id = (int) array_shift($args);

    try { $this->model->packet = new \BNETDocs\Libraries\Packet\Packet($this->model->packet_id); }
    catch (\UnexpectedValueException) { $this->model->packet = null; }

    if (!$this->model->packet)
    {
      $this->model->_responseCode = HttpCode::HTTP_NOT_FOUND;
      $this->model->error = ViewModel::ERROR_NOT_FOUND;
      return true;
    }

    $this->model->comments = Comment::getAll(Comment::PARENT_TYPE_PACKET, $this->model->packet_id);
    $this->model->used_by = \BNETDocs\Libraries\Product::getProductsFromIds($this->model->packet->getUsedBy());
    $this->model->_responseCode = HttpCode::HTTP_OK;
    return true;
  }
}
