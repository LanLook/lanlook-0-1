<?php $this->beginContent('//layouts/main'); ?>
<div class="span-5 last">
	<div id="sidebar">
	<?php
 		$this->beginWidget('zii.widgets.CPortlet', array(
 			'title'=>'Categories',
 		));

		$this->widget('application.extensions.simpleMenu.simpleMenu', array(
			'items'=>$this->categories,
			'theme'=>'default',
			'vertical'=>true));

 		$this->endWidget();
 	?>
	</div><!-- sidebar -->
</div>
<div class="span-19 last">
	<div id="content">
		<h1><?php echo $this->pageTitle ?></h1>
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>