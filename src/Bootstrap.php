<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2ConfigManager;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use DmitriiKoziuk\yii2ModuleManager\services\ModuleRegistrationService;

final class Bootstrap implements BootstrapInterface
{
    /**
     * @param Application $app
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function bootstrap($app)
    {
        ModuleRegistrationService::addModule(ConfigManagerModule::class, function () use ($app) {
            return [
                'class' => ConfigManagerModule::class,
                'diContainer' => Yii::$container,
                'globalValues' => $app->params,
            ];
        });
    }
}