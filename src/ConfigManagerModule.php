<?php declare(strict_types=1);

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

    const CONFIG_SAVE_LOCATION = '@common' . DIRECTORY_SEPARATOR .
        'storage' . DIRECTORY_SEPARATOR .
        'dk-config-manager' . DIRECTORY_SEPARATOR .
        'config';

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
    public $backendAppId = YII_ENV_TEST ? 'app-backend-tests' : 'app-backend';

    /**
     * Overwrite this param if you backend app id is different from default.
     * @var string
     */
    public $frontendAppId = YII_ENV_TEST ? 'app-frontend-tests' : 'app-frontend';

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        /** @var BaseApp $app */
        $app = $this->module;
        $this->initLocalProperties($app);
        $this->registerTranslation($app);
        $this->registerClassesToDIContainer();
        $this->initGlobalConfig();
    }

    public static function getId(): string
    {
        return self::ID;
    }

    public function getBackendMenuItems(): array
    {
        return ['label' => 'Config manager', 'url' => ['/' . self::ID . '/config/index']];
    }

    public static function requireOtherModulesToBeActive(): array
    {
        return [];
    }

    private function initLocalProperties(BaseApp $app)
    {
        if ($app instanceof WebApp && $app->id == $this->backendAppId) {
            $this->controllerNamespace = __NAMESPACE__ . '\controllers\backend';
        }
    }

    private function registerTranslation(BaseApp $app)
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
    private function registerClassesToDIContainer(): void
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
    private function initGlobalConfig()
    {
        /** @var ConfigService $configService */
        $configService = $this->diContainer->get(ConfigService::class);
        $configService->registerConfig(new Config(self::GENERAL_CONFIG_NAME, 'Config manager', [
            new ValueString(
                'frontendDomainName',
                $this->globalValues['frontendDomainName'] ?? 'http://default-domain',
                'Frontend domain name'
            ),
            new ValueString(
                'backendDomainName',
                $this->globalValues['backendDomainName'] ?? 'http://b.default-domain',
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