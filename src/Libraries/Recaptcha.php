<?php

namespace BNETDocs\Libraries;

use \BNETDocs\Exceptions\RecaptchaException;
use \BNETDocs\Libraries\Logger;

class Recaptcha
{
  public string $secret;
  public string $site_key;
  public string $url;

  public function __construct(string $secret, string $site_key, string $url)
  {
    $this->secret = $secret;
    $this->site_key = $site_key;
    $this->url = $url;
  }

  public function verify(string $response, ?string $remoteip = null) : bool
  {
    $data = [
      'secret'   => $this->secret,
      'response' => $response,
      'remoteip' => ($remoteip ? $remoteip : getenv('REMOTE_ADDR')),
    ];

    $r = \CarlBennett\MVC\Libraries\Common::curlRequest($this->url, $data);

    Logger::logMetric('recaptcha_r_code', $r->code);
    Logger::logMetric('recaptcha_r_type', $r->type);
    Logger::logMetric('recaptcha_r_data', $r->data);

    if ($r->code != 200) {
      throw new RecaptchaException('Received bad HTTP status');
    } else if (stripos($r->type, 'json') === false) {
      throw new RecaptchaException('Received unknown content type');
    } else if (empty($data)) {
      throw new RecaptchaException('Received empty response');
    }

    $j = json_decode($r->data);
    $e = json_last_error();

    Logger::logMetric('recaptcha_json_error', $e);

    if (!$j || $e !== JSON_ERROR_NONE || !property_exists($j, 'success'))
      throw new RecaptchaException('Received invalid response');

    return ($j->success);
  }
}
