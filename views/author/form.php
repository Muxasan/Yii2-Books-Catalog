<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Author */

$this->title = $model->isNewRecord ? 'Create Author' : 'Update Author';
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>