<?php
/** @var $model \app\models\Ticket */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Рассчет счастливых билетов';
$this->params['breadcrumbs'][] = $this->title;
$urlCount = Url::to('ticket/count');
$urlList = Url::to('ticket/list');

$this->registerJs(
    <<<JS
  function luckyList() {
    $.ajax({
            url: '{$urlList}',
            data: {length: $("#ticket-length").val()},
            dataType: 'json'
        }).done(function(data) {
            $("#numberCount").append('<small>' + data.message.join(', ') + ' (пока отображаются первые 100)</small>');
        });
}
JS
, \yii\web\View::POS_HEAD);
$this->registerJs(<<<JS
var ticketInput = $("#ticket-length");
ticketInput.keyup(function() { // отлавливаем любое нажатие и запускаем yii-валидацию
    ticketInput.trigger('change');
});
//событие, вызываемое после валидации формы
$('form#{$model->formName()}').on('afterValidateAttribute', function (event, attribute, messages) {
    event.preventDefault();
    if (!messages.length) { // если нет ошибок валидации, отправляем запрос на сервер
        $.ajax({
            url: '{$urlCount}',
            data: {length: ticketInput.val()},
            dataType: 'json',
        }).done(function(data) {
            $("#numberCount").html('Количество счастливых билетов: ' + data.message + '<br /><button class="btn" onclick="luckyList(); return false;">Посмотреть список</button><br />').fadeIn("fast");
        });
    } else {
        $("#numberCount").fadeOut("fast");
    }
    return true;
});
JS
);
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(
        [
            'id'          => $model->formName(),
            'options'     => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template'     => "{label}\n<div class=\"col-lg-2\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-3 control-label']
            ]
        ]
    ); ?>

    <?= $form->field($model, 'length')->input('number') ?>

    <h2 id="numberCount"></h2>
    <?php ActiveForm::end(); ?>

    <!--<div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>-->
</div>
