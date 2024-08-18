<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description',
            'year',
            'isbn',
            [
                'label' => 'Authors',
                'value' => function($model) {
                    $authors = $model->getAuthors()->all();
                    $authorNames = array_map(function($author) {
                        return Html::a(Html::encode($author->full_name), ['author/view', 'id' => $author->id]);
                    }, $authors);
                    return implode(', ', $authorNames);
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'cover_image',
                'value' => 'uploads/' . $model->cover_image,
                'format' => ['image', ['width' => '200', 'heght' => '200']],
            ],
        ],
    ]) ?>
</div>
