<?php
namespace DmitriiKoziuk\yii2ConfigManager;

use Yii;
use yii\base\BootstrapInterface;
use DmitriiKoziuk\yii2ModuleManager\services\ModuleInitService;

final class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        ModuleInitService::registerModule(ConfigManagerModule::class, function () use ($app) {
            return [
                'class' => ConfigManagerModule::class,
                'diContainer' => Yii::$container,
                'globalValues' => $app->params,
            ];
        });
    }
}