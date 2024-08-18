<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->isNewRecord ? 'Create Book' : 'Update Book';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coverImageFile')->fileInput() ?>

    <?= $form->field($model, 'authorIds')->checkboxList(
        $model->getAuthorsList()
    )->label('Authors') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>