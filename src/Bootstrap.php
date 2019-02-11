<?php
namespace DmitriiKoziuk\yii2ConfigManager;

use Yii;
use yii\base\BootstrapInterface;
use DmitriiKoziuk\yii2ModuleManager\services\ModuleService;

final class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function bootstrap($app)
    {
        $app->setModule(ConfigManagerModule::ID, [
            'class' => ConfigManagerModule::class,
            'diContainer' => Yii::$container,
            'globalValues' => $app->params,
        ]);
        /** @var ConfigManagerModule $module */
        $module = $app->getModule(ConfigManagerModule::ID);
        /** @var ModuleService $moduleService */
        $moduleService = Yii::$container->get(ModuleService::class);
        $moduleService->registerModule($module);
    }
}