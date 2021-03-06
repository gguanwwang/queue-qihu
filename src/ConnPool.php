<?php

namespace Qihu\Queue;

use Qihu\Queue\Drive\RabbitmqFactory;
use Qihu\Queue\Drive\RedisFactory;

class ConnPool
{
    private static $pool = [];

    /**
     * @param string $queueName
     * @return Drive\Rabbitmq\Rabbitmq|Drive\Redis\Redis|null
     */
    public static function getQueueClient($queueName = '')
    {
        if (isset(self::$pool[$queueName])) {
            return self::$pool[$queueName];
        }
        $cfg = config('queueqihu');
        $drive = $cfg['connect']['drive'];
        $drive = isset($cfg['queue'][$queueName]['drive']) && !empty($cfg['queue'][$queueName]['drive']) ? $cfg['queue'][$queueName]['drive'] : $drive;
        switch ($drive) {
            case 'rabbitmq':
                self::$pool[$queueName] = RabbitmqFactory::createClient($cfg[$drive]);
                break;
            default:
                self::$pool[$queueName] = RedisFactory::createClient($cfg[$drive]);
                break;
        }
        return self::$pool[$queueName];
    }
}
