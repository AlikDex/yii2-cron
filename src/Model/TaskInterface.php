<?php
namespace Adx\Module\CronModule\Model;

use Cron\CronExpression;

/**
 * Interface for task.
 */
interface TaskInterface
{
    const STATUS_PLANNED = 'planned'; // 'planned'
    const STATUS_RUNNING = 'running'; // 'running'
    const STATUS_COMPLETED = 'completed'; // 'completed'
    const STATUS_FAILED = 'failed'; // 'failed'
    const STATUS_ABORTED = 'aborted'; // 'aborted'

    /**
     * Returns task-id.
     *
     * @return null|integer
     */
    public function getId();

    /**
     * Returns task-command.
     *
     * @return string
     */
    public function getCommand();

    /**
     * Returns last-execution date-time.
     *
     * @return string
     */
    public function getLastExecution();
}
