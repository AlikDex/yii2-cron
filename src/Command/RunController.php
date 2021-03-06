<?php
namespace Adx\Module\CronModule\Command;

use yii\console\Controller;
use Adx\Module\CronModule\Model\Task;
use Adx\Module\CronModule\Executor\TaskExecutor;

/**
 * This command echoes the first argument that you have entered.
 */
class RunController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $tasks = Task::find()
            ->where(['enabled' => true])
            ->all();

        if (!empty($tasks)) {
             $runner = new TaskExecutor;

            foreach ($tasks as $task) {
                $runner->addTask($task);
            }

            $runner->runTasks();
        }
    }

}
