<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-list">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_authorItem',
    ]); ?>
</div>