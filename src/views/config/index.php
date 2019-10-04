<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 * @var $configName string
 * @var $configService \DmitriiKoziuk\yii2ConfigManager\services\ConfigService
 */

$list = $configService->getConfigList();
$config = $configService->getConfig($configName);
?>

<div class="row">
    <div class="col-md-2">
        <ul>
        <?php foreach ($list as $code => $name): ?>
            <li><?= Html::a($name, Url::to(['config/index', 'id' => $code])) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-md-10">
        <?php $form = ActiveForm::begin(); ?>
            <?= $this->render('_config', [
                'config' => $config,
                'form' => $form,
            ]) ?>

      <div class="form-group">
          <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
      </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
