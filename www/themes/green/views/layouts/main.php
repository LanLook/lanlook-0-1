<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Yii::app()->language; ?>" lang="<?php echo Yii::app()->language; ?>">
<head>
<?php echo $this->toString($this->pageMetaTags); ?>
<title><?php echo CHtml::encode($this->pageTitle.'-'.mb_convert_case(Yii::t('msg',Yii::app()->name), MB_CASE_TITLE, "UTF-8")); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::t('msg', Yii::app()->name)); ?></div>
	</div><!-- header -->
	
	<div class="mainmenu" >
	<?php $this->widget('application.extensions.simpleMenu.simpleMenu', array('items'=>$this->menus)); ?>
	</div><!-- top menu -->
	<div class="clear"></div>

	<div id="language-selector" style="float:right; margin:5px;">
    <?php $this->widget('application.components.LangBox'); ?>
	</div>
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>
	
	<div class="mainmenu" >
	<?php $this->widget('application.extensions.simpleMenu.simpleMenu', array('items'=>$this->menus, 'upward'=>true)); ?>
	</div><!-- bottom menu -->
	<div id="footer">
		<?php echo Yii::t('msg', "Copyright &copy; {year} by", array('{year}'=>date('Y')));?> <?php echo Yii::t('msg', Yii::app()->name);?>.<br/>
		<?php echo Yii::t('msg', 'All Rights Reserved.');?><br /><?php echo Yii::t('msg', 'Developed by')?> <?php echo $this->pageAuthor;?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
