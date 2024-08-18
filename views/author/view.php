<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $author app\models\Author */
/* @var $books app\models\Book[] */  // Убедитесь, что массив содержит объекты модели Book
/* @var $subscriptionModel app\models\Subscription */
/* @var $isGuest boolean */
/* @var $isSubscribed boolean */

$this->title = $author->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $author->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $author->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $author,
        'attributes' => [
            'full_name',
        ],
    ]) ?>

    <h3>Books by <?= Html::encode($author->full_name) ?></h3>
    <ul>
        <?php foreach ($books as $book): ?>
            <li><?= Html::a(Html::encode($book->title), ['book/view', 'id' => $book->id]) ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if (empty($books)): ?>
        <p>This author has not published any books yet.</p>
    <?php endif; ?>

    <?php if ($isGuest || !$isSubscribed): ?>
        <h3>Subscribe to new books by <?= Html::encode($author->full_name) ?></h3>

        <?php $form = ActiveForm::begin(['action' => ['author/subscribe', 'id' => $author->id]]); ?>

        <?= $form->field($subscriptionModel, 'phone')->textInput([
            'maxlength' => true,
            'placeholder' => 'Enter your phone number'
        ])->label(false) ?>
        
        <div class="form-group">
            <?= Html::submitButton('Subscribe', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <p>You are already subscribed to this author.</p>
    <?php endif; ?>
</div>