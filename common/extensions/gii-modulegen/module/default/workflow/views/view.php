<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\helpers\Url;
use yii\adminUi\widget\WorkflowButtonsView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-gear',
            'headerButtonGroup' => true,
            'headerButton' => ((\Yii::$app->user->can(\Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/update')) ? Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) : '' )
                            .((\Yii::$app->user->can(\Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/delete')) ? Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
								'class' => 'btn btn-danger',
								'data' => [
									'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
									'method' => 'post',
								],
							]) : '')
        ]);
		
		echo DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]);
    echo WorkflowButtonsView::widget([
        'view' => $this,
        'model' => $model,
    ]);
        Box::end();
    Column::end();
Row::end();
?>