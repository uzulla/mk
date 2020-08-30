<?php
declare(strict_types=1);

namespace Torb;

use Monolog\Logger;
use PDO;
use RuntimeException;

class PDOUzuWrapper extends PDO
{
    public Logger $logger;
    public string $sql;
    public float $start_at_us;

    public function __construct($logger, $dsn, $username = null, $passwd = null, $options = null)
    {
        $this->logger = $logger;
        parent::__construct($dsn, $username, $passwd, $options);
    }

    public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = array())
    {
        $this->sql = $statement;
        $this->start_at_us = microtime(true);

        return parent::query($statement, $mode);
    }

    public function prepare($statement, $driver_options = []): PDOStatementUzuWrapper
    {
        $this->sql = $statement;
        $this->start_at_us = microtime(true);

        /** @var PDOStatementUzuWrapper|false $stmt */
        $stmt = parent::prepare($statement, $driver_options);
        if (is_bool($stmt)) {
            $this->logger->notice(__FILE__ . ":" . __LINE__ . " Prepare is failed.");
            throw new RuntimeException("prepare is failed");
        }
        return $stmt;
    }
}
