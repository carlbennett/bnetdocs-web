<?php

namespace BNETDocs\Libraries;

use \BNETDocs\Libraries\Common;
use \BNETDocs\Libraries\Cache;

class CSRF {

  const TTL = 30;

  private function __construct() {}

  public static function generate($id) {
    $id = (int) $id;
    $t  = microtime(true);
    $s  = mt_rand();
    $v  = hash("sha256", $t * $s * $id);
    Common::$cache->set("bnetdocs-csrf-" . $id, $v, self::TTL);
    return $v;
  }

  public static function invalidate($id) {
    $key = "bnetdocs-csrf-" . (int) $id;
    return Common::$cache->set($key, "", 1);
  }

  public static function validate($id, $token) {
    $key = "bnetdocs-csrf-" . (int) $id;
    $val = Common::$cache->get($key);
    if (is_null($val)) return false;
    return ($val === $token);
  }

}
