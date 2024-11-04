<?php

declare(strict_types=1);

namespace Affinite\WpUpdater;

require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/Response.php';

/**
 * Abstract server class
 */
abstract class Server
{
    /** @var string SERVER_HOST */
    protected const SERVER_HOST = 'https://affinite.io';

    /** @var string LOG_DIR */
    protected const LOG_DIR = '';// /var/logs

    /** @var ?Logger $logger  */
    protected ?Logger $logger = null;

    public function __construct() {
        $this->logger = new Logger( self::LOG_DIR );
    }
}
