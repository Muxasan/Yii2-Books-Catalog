<?php
use yii\helpers\Html;

/* @var $model app\models\Author */
?>
<div class="author-item">
    <h3><?= Html::a(Html::encode($model->full_name), ['view', 'id' => $model->id]) ?></h3>
</div>
