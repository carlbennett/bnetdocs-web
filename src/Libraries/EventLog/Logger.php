<?php

namespace BNETDocs\Libraries\EventLog;

use \BNETDocs\Libraries\Core\Config;
use \BNETDocs\Libraries\Discord\Embed as DiscordEmbed;
use \BNETDocs\Libraries\Discord\Webhook as DiscordWebhook;
use \BNETDocs\Libraries\EventLog\Event;
use \BNETDocs\Libraries\User\User;

class Logger
{
  private function __construct() {}

  public static function initDiscordEmbed(Event $event, ?string $url = null, array $fields = []): DiscordEmbed
  {
    $embed = new DiscordEmbed();

    $embed->setColor(EventType::color($event->getTypeId()));
    $embed->setTimestamp($event->getDateTime());
    $embed->setTitle($event->getTypeName());

    $u = !\is_null($url) ? $url : \sprintf('/eventlog/view?id=%d', $event->getId());
    if (!empty($u))
    {
      $u = \BNETDocs\Libraries\Core\UrlFormatter::format($u);
      $embed->setUrl($u);
    }

    $user = $event->getUser();
    if (!\is_null($user))
    {
      $author = $user->getAsDiscordEmbedAuthor();
      $embed->setAuthor($author);
    }

    $embed->addFields($fields);

    return $embed;
  }

  public static function initEvent(int $type_id, User|int|null $user = null, ?string $ip_address = null, mixed $meta_data = null): Event
  {
    $e = new Event();

    $e->setDateTime('now');
    $e->setIPAddress($ip_address);
    $e->setMetaData($meta_data);
    $e->setTypeId($type_id);
    $e->setUserId($user);

    return $e;
  }

  public static function logToDiscord(Event $event, DiscordEmbed $embed): void
  {
    if (!Config::get('discord.forward_event_log.enabled')) return;
    if (\in_array($event->getTypeId(), Config::get('discord.forward_event_log.ignore_event_types'))) return;

    $webhook = new DiscordWebhook(Config::get('discord.forward_event_log.webhook'));

    if (Config::get('discord.forward_event_log.exclude_meta_data'))
    {
      $embed->removeAllFields();
    }

    $webhook->addEmbed($embed);
    $webhook->send();
  }
}
