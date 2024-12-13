<?php /* vim: set colorcolumn=: */

namespace BNETDocs\Views\Base;

abstract class Json implements \BNETDocs\Interfaces\View
{
  public const MIMETYPE_JSON = 'application/json';

  /**
   * Gets the standard flags to call with json_encode() in subclasses.
   *
   * @return integer The flags to pass to json_encode().
   */
  public static function jsonFlags(): int
  {
    return \JSON_PRESERVE_ZERO_FRACTION | \JSON_THROW_ON_ERROR | self::prettyPrint();
  }

  /**
   * Provides the MIME-type that this View prints.
   *
   * @return string The MIME-type for this View class.
   */
  public static function mimeType(): string
  {
    return \sprintf('%s;charset=utf-8', self::MIMETYPE_JSON);
  }

  /**
   * Automatically passes the JSON_PRETTY_PRINT flag when using php-cli or when client is a browser.
   *
   * @return integer The JSON_PRETTY_PRINT flag or zero.
   */
  private static function prettyPrint(): int
  {
    return (\php_sapi_name() == 'cli'
      || \BNETDocs\Libraries\Core\StringProcessor::isBrowser() ? \JSON_PRETTY_PRINT : 0);
  }
}
