<?php
namespace Adx\Module\CronModule\Executor;

use Cron\CronExpression;
use Adx\Module\CronModule\Model\TaskInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Executes scheduled tasks.
 */
class TaskExecutor implements TaskExecutorInterface
{
    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $taskExecutionRepository;
    /**
     * @var TaskHandlerFactoryInterface
     */
    private $taskHandlerFactory;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var LockInterface
     */
    private $lock;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $tasks = [];

    /**
     * @param TaskExecutionRepositoryInterface $executionRepository
     * @param TaskHandlerFactoryInterface $taskHandlerFactory
     * @param LockInterface $lock
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     */
    /*public function __construct(
        TaskExecutionRepositoryInterface $executionRepository,
        TaskHandlerFactoryInterface $taskHandlerFactory,
        LockInterface $lock,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger = null
    ) {
        $this->taskExecutionRepository = $executionRepository;
        $this->taskHandlerFactory = $taskHandlerFactory;
        $this->lock = $lock;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger ?: new NullLogger();
    }*/

    /**
     * @inheritdoc
     */
    public function runTasks()
    {
        $currentTime = new \DateTime('now', new \DateTimeZone('UTC'));

        if (!empty($this->tasks)) {
            foreach ($this->tasks as $task) {
                $cron = CronExpression::factory($task->expression);

                $firstRun = empty($task->last_execution); // Если пустое выражение, надо возвращать текущую дату следующего запуска.
                $nextRunTime = $cron->getNextRunDate($task->last_execution, 0, $firstRun, 'U');

                if ($currentTime >= $nextRunTime) {
                    $this->run($task);
                }
            }
        }
    }

    /**
     * Run execution with given handler.
     *
     * @param TaskInterface $task
     *
     * @param TaskExecutionInterface $execution
     */
    public function run($task)
    {
        $task->last_execution = \gmdate('Y-m-d H:i:s');
        $task->setStatus(TaskInterface::STATUS_RUNNING);
        $task->save();

        $start = \microtime(true);

        try {
            $result = $this->handle($task);
            $this->hasPassed($task, $result);
        } catch (\ProcessFailedException $e) {
            $this->hasFailed($task, $exception);
        } finally {
            $this->finalize($task, $start);
        }
    }

    public function addTask(TaskInterface $task)
    {
           $this->tasks[] = $task;
    }

    /**
     * Handle given execution and fire before and after events.
     *
     * @param TaskInterface $task
     *
     * @return \Serializable|string
     *
     * @throws \ProcessFailedException
     */
    private function handle(TaskInterface $task)
    {
        /*$this->eventDispatcher->dispatch(
            Events::TASK_BEFORE,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );*/
        try {
            $process = new Process($task->command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return $process->getOutput();
        } catch (\Exception $exception) {
            throw $exception;
        } finally {
            /*$this->eventDispatcher->dispatch(
                Events::TASK_AFTER,
                new TaskExecutionEvent($execution->getTask(), $execution)
            );*/
        }
    }

    /**
     * The given task passed the run.
     *
     * @param TaskInterface $task
     * @param mixed $result
     *
     * @return TaskExecutionInterface
     */
    private function hasPassed(TaskInterface $task, $result)
    {
        $task->setStatus(TaskInterface::STATUS_COMPLETED);
        $task->result = (string) $result;
    }

    /**
     * The given task failed the run.
     *
     * @param TaskInterface $task
     * @param \Exception $exception
     *
     * @return TaskExecutionInterface
     */
    private function hasFailed(TaskInterface $task, \Exception $exception)
    {
        // this find is necessary if the storage because the storage could be
        // invalid (clear in doctrine) after handling an execution.
        $task->setStatus(TaskInterface::STATUS_FAILED);
        $task->result = $exception->getMessage();

        /*
        $this->eventDispatcher->dispatch(
            Events::TASK_FAILED,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );*/
    }

    /**
     * Finalizes given execution.
     *
     * @param TaskInterface $task
     * @param int $start
     */
    private function finalize(TaskInterface $task, $start)
    {
        $task->setDuration(\microtime(true) - $start);
        $task->save();

        /*$this->eventDispatcher->dispatch(
            Events::TASK_FINISHED,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );*/
    }
}
