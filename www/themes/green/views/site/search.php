<?php if(Yii::app()->user->hasFlash('search')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('search'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form',
	'enableAjaxValidation'=>true,
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
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
 			 		'name'=>'form_save',
 			 		'caption'=>'Save',
 			 		'options'=>array(
 					         'onclick'=>'js:function(){alert("Yes");}',
 					),
 		)); ?>
	</div>

<?php $this->endWidget(); ?>

 	<div>
 	<?php
		/* $this->widget('zii.widgets.jui.CJuiButton', array(
 			 		'name'=>'submit1',
 			 		'caption'=>'Save',
 			 		'options'=>array(
 					         'onclick'=>'$("#mydialog").dialog("open"); return false;',
 					),
 		)); */

		$this->widget('zii.widgets.jui.CJuiButton',
		 		array(
		 			'name'=>'button1',
		  			'caption'=>'Save',
		 			'value'=>'Hello Baby',
		 			'onclick'=>'js:function(){alert("Would you like to confirm to save?"); this.blur(); return false;}',
				)
		);
	?>
 	</div>
	<div >
 		<?php
 			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
 			     	'name'=>'expireDate',
 				 	'language'=>Yii::app()->language,
 			     	// additional javascript options for the date picker plugin
 			     	'options'=>array(
 					         'showAnim'=>'fadeIn',
 			     			 'dateFormat'=>'dd-mm-yy',
 			     			 'defaultDate'=> +1,
//  			     			 'buttonImage'=>Yii::app()->theme->baseUrl."/images/datepicker.gif",
 			     			 'changeMonth'=>true,
 			     			 'changeYear'=>true,
 			     			 'showButtonPanel'=>false,
 			     			 'showOn'=>'both',
 			     		//	 'monthNamesShort'=>array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Sep","Okt","Nov","Dec"),
 			     		//	 'monthNames'=>array("Januar","Februar","Marts","April","Maj","Juni","Juli","August","September","Oktober","November","December"),
 					),
					//'themeUrl' => Yii::app()->theme->baseUrl.'/jui/css' ,
					//'theme'=>'gray',        //try 'bee' also to see the changes
					//'cssFile'=>array('jquery.ui.css' ,'jquery.ui.datepicker.css'),
 			     	'htmlOptions'=>array(
 					         'style'=>'height:22px;width:130px;',
 			     		//	 'class'=>'Datepicker',
 					 ),
 			 ));
 		?>
 	</div>

	<div>
 	<?php

//  	$this->widget('CTreeView', array('url' => array('ajaxFillTree')));
//  		$this->widget('CTreeView', array('animated'=>'fast', 'collapsed'=>true, 'data' => array(
//  															array('id'=>1,'text'=>CHtml::link('aaaaaaaaaaa'), 'hasChildren'=>false),
//  															array('id'=>2,'text'=>CHtml::link('bbbbbbbbbbbb'), 'hasChildren'=>true,
//  																	'children'=>array(
//  																			array('id'=>21,'text'=>CHtml::link('aaaaaaaaaaa'), 'hasChildren'=>false),
//  																			array('id'=>22,'text'=>CHtml::link('aaaaaaaaaaa'), 'hasChildren'=>false),
//  																			array('id'=>23,'text'=>CHtml::link('aaaaaaaaaaa'), 'hasChildren'=>false),
//  																			array('id'=>24,'text'=>CHtml::link('aaaaaaaaaaa'), 'hasChildren'=>false),
//  																			)
//  																),
//  															array('id'=>3,'text'=>CHtml::link('cccccccccccc'), 'hasChildren'=>false),
//  														)));
 	?>
 	</div>



	<div>
	<?php
// 		$this->widget('zii.widgets.jui.CJuiAccordion', array(
// 	      'panels'=>array(
// 	          'Real Estate'=>CHtml::image(Yii::app()->theme->baseUrl."/images/datepicker.gif",'').CHtml::link('Real Estate', $this->CreateUrl('site/list', array('pid'=>1))). '<br />'.date('d-m-Y'),
// 	          'Auto Vehicles'=>array('panels'=>array('aaaaaaaaa'=>'content for panel 2')),
// 	          'Clothing'=>'content for panel 2',
// 	          'Furniture'=>'content for panel 2',
// 	          // panel 3 contains the content rendered by a partial view
// 	          //'panel 3'=>$this->renderPartial('_partial',null,true),
// 	      ),
// 	      // additional javascript options for the accordion plugin
// 	      'options'=>array(
// 	          'animated'=>'bounceslide',
// 	      ),
// 		));
	?>
 	</div>



 	<div>
 	<?php
		$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
 			     'id'=>'mydialog',
 			    // additional javascript options for the dialog plugin
 			     'options'=>array(
 					         'title'=>'Dialog box 1',
 					         'autoOpen'=>false,
 			     			 'width'=>'360',
 			     			 'height'=>'100',
 			     			 'dialogClass'=>'alert',
 			     			 'modal'=>'true',
 			     			 'resizable'=>'false',
 			     			 'draggable'=>'false',
 			     			 'stack'=>'true',
							'buttons'=>"[{text:'Ok', click:'function() { $(this).dialog(\"close\");}}]'",
 					     ),
 			 ));

 	     echo 'dialog content here';

		$this->endWidget('zii.widgets.jui.CJuiDialog');
 	?>
 	</div>

 	<div>
 	<?php
 		echo CHtml::link('open dialog', '#', array(
 			    'onclick'=>'$("#mydialog").dialog("open"); return false;',
		));
 	?>
 	</div>

 	<div class="demo">
 	<?php
		/*$this->widget('zii.widgets.jui.CJuiButton', array(
 			 		'name'=>'submit',
 			 		'caption'=>'Save',
 			 		'options'=>array(
 					         'onclick'=>'js:function(){alert("Yes");}',
 					),
 		));

		$this->widget('zii.widgets.jui.CJuiButton',
		 		array(
		 			'name'=>'button',
		  			'caption'=>'Save',
		 			'value'=>'Hello Baby',
		 			'onclick'=>'js:function(){alert("Would you like to confirm to save?"); this.blur(); return false;}',
				)
		);*/
	?>
 	</div>

	<div>
	<?php
		/*$this->widget('zii.widgets.jui.CJuiSlider', array(
	     'value'=>37,
      // additional javascript options for the slider plugin
	      'options'=>array(
	          	'min'=>0,
	      		'max'=>100,
				'range'=>'max',
	      	  	'values'=>array(20, 50),
	      		'animate'=>false,
	      		'step'=>10,
	      ),
	      'htmlOptions'=>array(
          'style'=>'height:10px;'
      ),
  ));*/
	?>
	</div>
	<div class="border">
	<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			     'name'=>'city',
			     'source'=>array('ac1', 'ac2', 'ac3'),
			     // additional javascript options for the autocomplete plugin
			     'options'=>array(
					         'minLength'=>'1',

					     ),
			     'htmlOptions'=>array(
					         'style'=>'height:20px;width:170px'
					     ),
			 ));
	?>
	</div>
	<div class="border"><textarea rows="9" cols="17"></textarea></div>


	<div style="width:160px;float:right">
		<?php // $this->widget('application.extensions.simpleMenu.simpleMenu', array('items'=>$this->categories, 'theme'=>'default', 'vertical'=>true, 'rtl'=>true)); ?>
	</div>

	<div>
 		<?php
// 			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
// 			     	'name'=>'publishDate',
// 				 	'language'=>Yii::app()->language,
// 			     	// additional javascript options for the date picker plugin
// 			     	'options'=>array(
// 					         'showAnim'=>'fadeIn',
// 			     			 'dateFormat'=>'dd-mm-yy',
// 			     			 'defaultDate'=> +1,
////  			     			 'buttonImage'=>Yii::app()->theme->baseUrl."/images/datepicker.gif",
// 			     			 'changeMonth'=>true,
// 			     			 'changeYear'=>true,
// 			     			 'showButtonPanel'=>false,
// 			     			 'showOn'=>'both',
// 			     		//	 'monthNamesShort'=>array("Jan","Feb","Mar","Apr","Maj","Jun","Jul","Aug","Sep","Okt","Nov","Dec"),
// 			     		//	 'monthNames'=>array("Januar","Februar","Marts","April","Maj","Juni","Juli","August","September","Oktober","November","December"),
// 					),
//					/* 'themeUrl' => Yii::app()->theme->baseUrl.'/css/jui' ,
//					'theme'=>'green',        //try 'bee' also to see the changes
//					'cssFile'=>array('jquery.ui.css' ,'jquery.ui.datepicker.css'), */
// 			     	'htmlOptions'=>array(
// 				//	         'style'=>'height:22px;width:130px;',
// 					 ),
// 			 ));
 		?>
 	</div>


</div><!-- form -->

<?php endif; ?>
