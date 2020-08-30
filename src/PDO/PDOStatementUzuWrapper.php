<?php
declare(strict_types=1);

namespace Torb;

use PDOStatement;

class PDOStatementUzuWrapper extends PDOStatement
{
    public PDOUzuWrapper $dbh;

    protected function __construct(PDOUzuWrapper $dbh)
    {
        $this->dbh = $dbh;
    }

    private array $vault = [];

    public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR): bool
    {
        $this->vault[$parameter] = $value;
        return parent::bindValue($parameter, $value, $data_type);
    }

    /**
     * 実行時間計測用Wrapper
     * @param null $input_parameters
     * @return bool
     */
    public function execute($input_parameters = null): bool
    {
        if(!is_null($input_parameters)) {
            $this->vault = array_merge($this->vault, $input_parameters);
        }
        $res = parent::execute($input_parameters);
        $this->vaultToLog();
        return $res;
    }

    /**
     * 実行時間計測用Wrapper
     * fetchAllをすべてサポートしていない（サポートしようとするとエラーが発生する）ので本物のFetchAllには引数をあたえていない
     * 前段でsetFetchModeなどを指定してから実行すること
     * @param null $how
     * @param null $class_name
     * @param null $ctor_args
     * @return array
     */
    public function fetchAll($how = NULL, $class_name = NULL, $ctor_args = NULL)
    {
        $start_us = microtime(true);
        $array = parent::fetchAll();
        $consume_ms = (microtime(true) - $start_us) * 1000;
        $this->dbh->logger->notice(__FILE__ . ":" . __LINE__ . " Db fetchAll time " . sprintf("%.2f ms", $consume_ms));
        return $array;
    }

    public function vaultToLog(): void
    {
        $sql = $this->dbh->sql;
        $sprintf_sql = preg_replace("/\?/u", "'%s'", $sql);
        $generated_sql = sprintf($sprintf_sql, ...$this->vault);
        $this->dbh->logger->notice("SQL LOG:" ,
            [
                'sql' => $sql,
                'real_sql' => $generated_sql,
                'vals' => json_encode($this->vault, JSON_UNESCAPED_UNICODE),
                'sec' => microtime(true)-$this->dbh->start_at_us
            ]);
    }
}
