<?php

/**
 * @var $this \yii\web\View
 * @var $config \DmitriiKoziuk\yii2ConfigManager\interfaces\ConfigInterface
 * @var $form \yii\widgets\ActiveForm
 * @var $value \DmitriiKoziuk\yii2ConfigManager\interfaces\ValueInterface
 */
?>
<?php foreach ($config->getAllValues() as $value): ?>
<dl class="dl-horizontal">
  <dt><?= $value->getLabel() ?></dt>
  <dd>
      <?= $form->field($value, "value")
          ->textInput(['name' => "{$config->getId()}[{$value->getName()}]"])
          ->label(false)
      ?>
  </dd>
</dl>
<?php endforeach; ?>