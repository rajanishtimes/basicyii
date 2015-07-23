<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\module\Generator */

?>
<div class="module-form">
<?php
    echo $form->field($generator, 'moduleClass');
    echo $form->field($generator, 'moduleID');
	
	echo $form->field($generator, 'modelClass');	
	echo $form->field($generator, 'searchModelClass');
	echo $form->field($generator, 'controllerClass');
	
	echo $form->field($generator, 'tsearchModelClass');
	echo $form->field($generator, 'tcontrollerClass');
	
	
	echo $form->field($generator, 'baseControllerClass');	
        echo $form->field($generator, 'isworkflow')->dropDownList([
		0 => 'No Work Flow system required',
		1 => 'Work Flow system required',
	]);
	echo $form->field($generator, 'indexWidgetType')->dropDownList([
		'grid' => 'GridView',
		'list' => 'ListView',
	]);
	echo $form->field($generator, 'enableI18N')->checkbox();
	echo $form->field($generator, 'messageCategory');
?>
</div>
