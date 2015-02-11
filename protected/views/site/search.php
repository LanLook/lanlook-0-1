<?php if(Yii::app()->user->hasFlash('search')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('search'); ?>
</div>

<?php else: ?>

<div class="form">

<?php if ($this->selMenu->menu->menu_status>2): ?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form-search-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('msg', 'Fields with <span class="required">*</span> are required.')?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'categories'); ?>
		<?php echo $form->textField($model,'categories'); ?>
		<?php echo $form->error($model,'categories'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'itemID'); ?>
		<?php echo $form->textField($model,'itemID'); ?>
		<?php echo $form->error($model,'itemID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'itemName'); ?>
		<?php echo $form->textField($model,'itemName'); ?>
		<?php echo $form->error($model,'itemName'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

<?php else: ?>
<p class="errorMessage"><?php echo UNDER_CONSTRUCT; ?></p>
<?php endif;?>

</div><!-- form -->

<?php endif; ?>
