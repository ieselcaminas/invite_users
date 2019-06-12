<?php
namespace IESElCaminas;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    /**
     * @var \Monolog\Logger
     */
    private $log;

    private $level;

    /**
     * MyLog constructor.
     * @param string $filename
     * @throws \Exception
     */
    private function __construct(string $channel, string $filename, int $level)
    {

        $this->log = new Logger($channel);
        $this->level = $level;
        $this->log->pushHandler(new StreamHandler($filename, $this->level));
    }

    /**
     * @param string $filename
     * @return Log
     * @throws Exception
     */
    public static function load(string $channel, string $filename, int $level = Logger::INFO)
    {
        return new Log($channel, $filename, $level);
    }

    /**
     * @param $message
     */
    public function add($message)
    {
        $this->log->log($this->level, $message);
    }
}