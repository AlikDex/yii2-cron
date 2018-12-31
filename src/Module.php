<?php
namespace Adx\Module\CronModule;

use Yii;
use yii\i18n\PhpMessageSource;
use yii\base\Module as BaseModule;
use yii\console\Application as ConsoleApplication;

/**
 * This is the main module class of the video extension.
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'Adx\Module\CronModule\Controller';
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'main/index';
    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->setViewPath(__DIR__ . '/Resources/views');

        parent::__construct ($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'Adx\Module\CronModule\Command';
            $this->defaultRoute = 'run/index';
        }

        //translations
        if (!isset(Yii::$app->get('i18n')->translations['cron'])) {
            Yii::$app->get('i18n')->translations['cron'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/Resources/i18n',
                'sourceLanguage' => 'en-US',
            ];
        }
    }
}
