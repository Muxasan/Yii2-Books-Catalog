<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-list">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_bookItem', // Используем частичную вьюшку для каждого элемента списка
    ]); ?>
</div>
