<?php $this->beginContent('//layouts/main'); ?>
<div class="span-5">
	<div id="sidebar">
	<?php
// 		$this->beginWidget('zii.widgets.CPortlet', array(
// 			'title'=>'Categories',
// 		));
// 		$this->widget('zii.widgets.CMenu', array(
// 			'items'=>$this->menu,
// 			'htmlOptions'=>array('class'=>'operations'),
// 		));
// 		$this->endWidget();
	?>
	<?php // echo '<pre>'; var_dump($this->categories)?>
	<div style="height: 400px" >
		<?php   $this->widget('application.extensions.emenu.EMenu', array('items'=>$this->categories, 'vertical'=>true));?>
		<br />
		<?php  $this->widget('application.extensions.adropdown.aDropDown', array('items'=>$this->categories));?>
	</div> 
	</div><!-- sidebar -->
</div>
<div class="span-19 last">
	<div id="content">
		<h1><?php echo $this->pageTitle ?></h1>
		<?php echo $content; ?>
	</div><!-- content -->
</div>

<?php $this->endContent(); ?>