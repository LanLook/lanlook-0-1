<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Yii::app()->language; ?>" lang="<?php echo Yii::app()->language; ?>">
<head>
<!-- blueprint CSS framework -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css" media="screen, projection" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/helper.css" />	
<?php echo $this->toString($this->pageMetaTags); ?>
<title><?php echo CHtml::encode($this->pageTitle.'-'.mb_convert_case(Yii::t('msg',Yii::app()->name), MB_CASE_TITLE, Yii::app()->charset)); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::t('msg', Yii::app()->name)); ?></div>
	</div><!-- header -->
	<div class="mainmenu">
	<?php $this->widget('application.extensions.emenu.EMenu', array('items'=>$this->menus, 'theme'=>'default' )); ?>
	</div>
<!-- <div id="mainmenu">
		<?php  //  $this->widget('zii.widgets.CMenu', array('items'=>$this->menu )); ?>
	</div> 
--> 
	<!-- mainmenu -->
	<br clear="all" />
	<div  id="language-selector" style="float:right; margin:5px;">
    <?php $this->widget('application.components.LangBox'); ?>
	</div>
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	
	<?php echo $content; ?>
	<div class="clear"></div>

	<div id="footer">
		<?php echo Yii::t('msg', "Copyright &copy; {year} by", array('{year}'=>date('Y')));?> <?php echo Yii::t('msg', Yii::app()->params['pageAuthor']);?>.<br/>
		<?php echo Yii::t('msg', 'All Rights Reserved.');?> 
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
