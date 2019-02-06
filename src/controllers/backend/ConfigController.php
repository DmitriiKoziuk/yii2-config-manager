<?php
namespace DmitriiKoziuk\yii2ConfigManager\controllers\backend;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use DmitriiKoziuk\yii2ConfigManager\services\ConfigService;
use DmitriiKoziuk\yii2ConfigManager\ConfigManager as ConfigModule;

class ConfigController extends Controller
{
    /** @var ConfigService */
    private $_configService;

    public function __construct(string $id, Module $module, ConfigService $configService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_configService = $configService;
    }

    public function actionIndex(string $id = ConfigModule::GENERAL_CONFIG_NAME)
    {
        if (Yii::$app->request->isPost) {
            $configValues = Yii::$app->request->post($id);
            $this->_configService
                ->updateConfigValues($id, $configValues)
                ->save($id);
        }

        return $this->render('index', [
            'configName' => $id,
            'configService' => $this->_configService,
        ]);
    }
}