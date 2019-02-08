<?php
namespace DmitriiKoziuk\yii2ConfigManager;

use Yii;
use yii\web\Application as WebApp;
use yii\base\Application as BaseApp;
use DmitriiKoziuk\yii2Base\helpers\FileHelper;
use DmitriiKoziuk\yii2ModuleManager\interfaces\ModuleInterface;
use DmitriiKoziuk\yii2ConfigManager\services\ConfigService;
use DmitriiKoziuk\yii2ConfigManager\data\Config;
use DmitriiKoziuk\yii2ConfigManager\data\ValueString;

final class ConfigManagerModule extends \yii\base\Module implements ModuleInterface
{
    const ID = 'dk-config-manager';

    const TRANSLATE = self::ID;

    const GENERAL_CONFIG_NAME = 'general';

    const CONFIG_SAVE_LOCATION = '@common/storage/dk-config-manager/config';

    /**
     * @var \yii\di\Container
     */
    public $diContainer;

    /**
     * @var array
     */
    public $globalValues = [];

    /**
     * Overwrite this param if you backend app id is different from default.
     * @var string
     */
    public $backendAppId = 'app-backend';

    /**
     * Overwrite this param if you backend app id is different from default.
     * @var string
     */
    public $frontendAppId = 'app-frontend';

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        /** @var BaseApp $app */
        $app = $this->module;
        $this->_initLocalProperties($app);
        $this->_registerTranslation($app);
        $this->_registerClassesToDIContainer();
        $this->_initGlobalConfig();
    }

    public function getId(): string
    {
        return self::ID;
    }

    public function getBackendMenuItems(): array
    {
        return ['label' => 'Config manager', 'url' => ['/' . self::ID . '/config/index']];
    }

    private function _initLocalProperties(BaseApp $app)
    {
        if ($app instanceof WebApp && $app->id == $this->backendAppId) {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\backend';
        }
    }

    private function _registerTranslation(BaseApp $app)
    {
        $app->i18n->translations[self::TRANSLATE] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@DmitriiKoziuk/yii2FileManager/messages',
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    private function _registerClassesToDIContainer(): void
    {
        /** @var FileHelper $fileHelper */
        $fileHelper = $this->diContainer->get(FileHelper::class);
        $this->diContainer->setSingleton(
            ConfigService::class,
            function () use ($fileHelper) {
                return new ConfigService(
                    Yii::getAlias(self::CONFIG_SAVE_LOCATION),
                    $fileHelper
                );
            }
        );
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    private function _initGlobalConfig()
    {
        /** @var ConfigService $configService */
        $configService = $this->diContainer->get(ConfigService::class);
        $configService->registerConfig(new Config(self::GENERAL_CONFIG_NAME, 'Config manager', [
            new ValueString(
                'frontendDomainName',
                $this->globalValues['frontendDomainName'] ?? '',
                'Frontend domain name'
            ),
            new ValueString(
                'backendDomainName',
                $this->globalValues['backendDomainName'] ?? '',
                'Backend domain name'
            ),
            new ValueString(
                'frontendAppId',
                $this->globalValues['frontendAppId'] ?? $this->frontendAppId,
                'Frontend app id'
            ),
            new ValueString(
                'backendAppId',
                $this->globalValues['backendAppId'] ?? $this->backendAppId,
                'Backend app id'
            ),
        ]));
    }
}