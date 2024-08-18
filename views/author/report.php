<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'Top 10 Authors by Books Published in ' . Html::encode($year);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-report">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма для выбора года -->
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['author/report'], // Указываем путь к действию отчета
    ]); ?>

    <div class="form-group">
        <?= $form->field($reportModel, 'year')->textInput(['placeholder' => 'Enter Year'])->label('Select Year') ?>
        <?= Html::submitButton('Get Report', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'full_name',
            [
                'attribute' => 'book_count',
                'label' => 'Number of Books',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('View', ['author/view', 'id' => $model['id']], ['class' => 'btn btn-primary']);
                    },
                ],
            ],
        ],
    ]); ?>

    <p>
        <?= Html::a('Back to Authors', ['author/list'], ['class' => 'btn btn-default']) ?>
    </p>
</div>