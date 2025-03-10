<?php

namespace BNETDocs\Libraries\Core;

use \BNETDocs\Libraries\EventLog\Logger;
use \BNETDocs\Libraries\User\Authentication;

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

  private static function check_blizzard_cidrs(): int
  {
    $IP    = getenv('REMOTE_ADDR');
    $CIDRs = file_get_contents(__DIR__ . '/../../Static/a/Blizzard-CIDRs.txt');
    $CIDRs = preg_replace("/^#.*?\n/sm", '', $CIDRs);
    $CIDRs = StringProcessor::stripLinesWith($CIDRs, "\n");
    $CIDRs = explode("\n", $CIDRs);
    return (
      IP::checkCIDRArray($IP, $CIDRs) ? self::STATUS_BLIZZARD : self::STATUS_NOT_BLIZZARD
    );
  }

  private static function check_for_blizzard(): void
  {
    self::$status = self::check_blizzard_cidrs();
  }

  public static function is_blizzard(): bool
  {
    if (self::$status === self::STATUS_NOT_CHECKED)
    {
      self::check_for_blizzard();
    }

    return (self::$status === self::STATUS_BLIZZARD);
  }

  public static function log_blizzard_request(): void
  {
    if (!self::is_blizzard()) return; // do not log non-Blizzard requests

    // Blizzard would likely never login to our site... would they?
    // But if they happened to be logged in already from a previously non-Blizzard identity...
    // Authentication::$user will associate their previous session to this session.

    $method = \getenv('REQUEST_METHOD');
    $referer = \getenv('HTTP_REFERER');
    $url = UrlFormatter::format(\getenv('REQUEST_URI'));
    $user_agent = \getenv('HTTP_USER_AGENT');

    $event = Logger::initEvent(
      \BNETDocs\Libraries\EventLog\EventTypes::BLIZZARD_VISIT,
      Authentication::$user,
      getenv('REMOTE_ADDR'),
      [
        'method'     => $method,
        'referer'    => $referer,
        'uri'        => $url,
        'user_agent' => $user_agent,
        'version'    => VersionInfo::get(),
      ]
    );

    if ($event->commit())
    {
      $embed = Logger::initDiscordEmbed($event, $url, [
        'Method' => $method,
        'URL' => $url,
        'Referer' => ($referer === false || empty($referer) ? '*empty*' : $referer),
        'User Agent' => $user_agent,
      ]);
      Logger::logToDiscord($event, $embed);
    }
  }
}
