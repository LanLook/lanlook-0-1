<?php if(Yii::app()->user->hasFlash('search')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('search'); ?>
</div>

<?php else: ?>

<div class="col_2">
	<!-- Menu Vertical Left -->
	<ul class="menu vertical">
		<li class="current"><a href="">Item 1</a></li>
		<li><a href="">Item 2</a></li>
		<li><a href="">Item 3</a>
			<ul>
				<li><a href="">Sub Item</a></li>
				<li><a href="">Sub Item</a>
					<ul>
						<li><a href="">Sub Item</a></li>
						<li><a href="">Sub Item</a></li>
						<li><a href="">Sub Item</a></li>
						<li><a href="">Sub Item</a></li>
					</ul></li>
				<li><a href="">Sub Item</a></li>
			</ul></li>
		<li><a href="">Item 4</a></li>
	</ul>
</div>
<div class="col_8">


<?php if ($this->selMenu->menu->menu_status>2): ?>

<?php
		
$form = $this->beginWidget ( 'CActiveForm', array ('id' => 'search-form-search-form', 'enableAjaxValidation' => false ) );
		?>

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
	<label for="textarea1">Message</label>
	<textarea id="textarea1"></textarea>

	<!-- Radio -->
	<label for="radio1" class="inline">Option1</label> 
	<input type="radio" name="radio" id="radio1" /> 
	<input type="radio" name="radio" id="radio2" /> 
	<input type="radio" name="radio" id="radio3" /> 
	<input type="radio" name="radio" id="radio4" />

	<!-- Select -->
	<label for="select1">Select Field</label> <select id="select1">
		<option value="0">-- Choose --</option>
		<option value="1">Option 1</option>
		<option value="2">Option 2</option>
		<option value="3">Option 3</option>
	</select>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

<?php else: ?>
<p class="errorMessage"><?php echo UNDER_CONSTRUCT; ?></p>
<?php endif;?>

<!-- form -->

<?php endif; ?>
</div>