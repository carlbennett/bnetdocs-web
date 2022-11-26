<?php

namespace BNETDocs\Libraries;

use \BNETDocs\Libraries\Authentication;
use \BNETDocs\Libraries\EventTypes;
use \BNETDocs\Libraries\Logger;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\IP;

/**
 * Provides a set of methods to identify clients originating from Blizzard.
 */
class BlizzardCheck
{
  private const STATUS_NOT_CHECKED = 0;
  private const STATUS_NOT_BLIZZARD = 1;
  private const STATUS_BLIZZARD = 2;

  private static int $status = self::STATUS_NOT_CHECKED;

  /**
   * Block instantiation of this object. All public methods are static.
   */
  private function __construct() {}

  private static function check_blizzard_cidrs()
  {
    $IP    = getenv('REMOTE_ADDR');
    $CIDRs = file_get_contents(getcwd() . '/Static/a/Blizzard-CIDRs.txt');
    $CIDRs = preg_replace("/^#.*?\n/sm", '', $CIDRs);
    $CIDRs = Common::stripLinesWith($CIDRs, "\n");
    $CIDRs = explode("\n", $CIDRs);
    return (
      IP::checkCIDRArray($IP, $CIDRs) ? self::STATUS_BLIZZARD : self::STATUS_NOT_BLIZZARD
    );
  }

  private static function check_for_blizzard()
  {
    self::$status = self::check_blizzard_cidrs();
  }

  public static function is_blizzard()
  {
    if (self::$status === self::STATUS_NOT_CHECKED)
    {
      self::check_for_blizzard();
    }

    return (self::$status === self::STATUS_BLIZZARD);
  }

  public static function log_blizzard_request()
  {
    if (!self::is_blizzard()) return; // do not log non-Blizzard requests

    // Blizzard would likely never login to our site... would they?
    // But if they happened to be logged in already from a previously non-Blizzard identity...

    Logger::logEvent(
      EventTypes::BLIZZARD_VISIT,
      isset(Authentication::$user) ? Authentication::$user->getId() : null,
      getenv('REMOTE_ADDR'),
      json_encode([
        'method'     => getenv('REQUEST_METHOD'),
        'referer'    => getenv('HTTP_REFERER'),
        'uri'        => Common::relativeUrlToAbsolute(getenv('REQUEST_URI')),
        'user_agent' => getenv('HTTP_USER_AGENT'),
        'version'    => VersionInfo::$version,
      ])
    );
  }
}
