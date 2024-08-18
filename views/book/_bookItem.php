<?php
use yii\helpers\Html;

/* @var $model app\models\Book */
?>
<div class="book-item">
    <h3><?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]) ?></h3>
    <p>Authors: 
        <?php foreach ($model->authors as $author): ?>
            <?= Html::a(Html::encode($author->full_name), ['author/view', 'id' => $author->id]) ?>
        <?php endforeach; ?>
    </p>
    <p><?= Html::encode($model->description) ?></p>
</div>
