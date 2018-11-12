<?php
namespace Adx\Module\CronModule\Executor;

/**
 * Interface for task-executor.
 */
interface TaskExecutorInterface
{
    /**
     * Run scheduled tasks.
     */
    public function runTasks();
}
