<?php
/**
 *  BNETDocs, the Battle.net(TM) protocol documentation and discussion website
 *  Copyright (C) 2008-2016  Carl Bennett
 *  This file is part of BNETDocs.
 *
 *  BNETDocs is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  BNETDocs is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with BNETDocs.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace BNETDocs;

use \BNETDocs\Libraries\Logger;
use \BNETDocs\Libraries\VersionInfo;
use \CarlBennett\MVC\Libraries\Cache;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\DatabaseDriver;
use \CarlBennett\MVC\Libraries\GlobalErrorHandler;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\Session;

function main() {

  if (!file_exists(__DIR__ . "/../lib/autoload.php")) {
    http_response_code(500);
    exit("Server misconfigured. Please run `composer install`.");
  }
  require(__DIR__ . "/../lib/autoload.php");

  GlobalErrorHandler::createOverrides();

  Logger::initialize();

  Common::$config = json_decode(file_get_contents(
    __DIR__ . "/../etc/config.phoenix.json"
  ));

  Session::initialize(
    Common::$config->memcache->session_server_string,
    "sid"
  );

  Common::$cache = new Cache(
    Common::$config->memcache->servers,
    Common::$config->memcache->connect_timeout,
    Common::$config->memcache->tcp_nodelay
  );

  Common::$database = null;

  DatabaseDriver::$character_set = Common::$config->mysql->character_set;
  DatabaseDriver::$database_name = Common::$config->mysql->database;
  DatabaseDriver::$password      = Common::$config->mysql->password;
  DatabaseDriver::$servers       = Common::$config->mysql->servers;
  DatabaseDriver::$timeout       = Common::$config->mysql->timeout;
  DatabaseDriver::$username      = Common::$config->mysql->username;

  VersionInfo::$version = VersionInfo::get();

  $router = new Router(
    "BNETDocs\\Controllers\\",
    "BNETDocs\\Views\\"
  );

  if (Common::$config->bnetdocs->maintenance[0]) {
    $router->addRoute( // URL: *
      "#.*#", "Maintenance", "MaintenanceHtml",
      Common::$config->bnetdocs->maintenance[1]
    );
  } else {
    $router->addRoute( // URL: /
      "#^/$#", "RedirectSoft", "RedirectSoftHtml", "/news"
    );
    $router->addRoute( // URL: /credits
      "#^/credits/?$#", "Credits", "CreditsHtml"
    );
    $router->addRoute( // URL: /servers
      "#^/servers/?$#", "Servers", "ServersHtml"
    );
    $router->addRoute( // URL: /servers.json
      "#^/servers\.json$#", "Servers", "ServersJSON"
    );
    $router->addRoute( // URL: /server/:id
      "#^/server/(\d+)/?#", "Server\\View", "Server\\ViewHtml"
    );
    $router->addRoute( // URL: /legal
      "#^/legal/?$#", "Legal", "LegalHtml"
    );
    $router->addRoute( // URL: /legal.txt
      "#^/legal.txt?$#", "Legal", "LegalPlain"
    );
    $router->addRoute( // URL: /user/login
      "#^/user/login/?$#", "User\\Login", "User\\LoginHtml"
    );
    $router->addRoute("#.*#", "PageNotFound", "PageNotFoundHtml"); // URL: *
  }

  $router->route();
  $router->send();

}

main();
