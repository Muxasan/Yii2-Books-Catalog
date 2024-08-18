<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Library Management System';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to the Library!</h1>
        <p class="lead">
            <?php if (Yii::$app->user->isGuest): ?>
                Browse our collection of books and authors. Sign up to manage your own content!
            <?php else: ?>
                Manage books, authors, and your subscriptions from one place.
            <?php endif; ?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Books</h2>
                <p>Browse the book catalog.</p>
                <?= Html::a('Go to Books', ['book/list'], ['class' => 'btn btn-primary']) ?>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <p><?= Html::a('Add New Book', ['book/create'], ['class' => 'btn btn-success']) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <h2>Authors</h2>
                <p>Explore our authors.</p>
                <?= Html::a('Go to Authors', ['author/list'], ['class' => 'btn btn-primary']) ?>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <p><?= Html::a('Add New Author', ['author/create'], ['class' => 'btn btn-success']) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <h2>Top 10 Authors by Year</h2>

                <!-- Форма для выбора года -->
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['author/report'], // Путь к действию в контроллере
                ]); ?>

                <?= $form->field($reportModel, 'year')->textInput([
                    'placeholder' => 'Enter Year'
                ])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('Get Report', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>