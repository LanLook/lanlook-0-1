<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Yii::app()->language; ?>" lang="<?php echo Yii::app()->language; ?>">
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/prettify.js"></script>
<!-- PRETTIFY -->
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/kickstart.js"></script>
<!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/kickstart.css" media="all" />
<!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/custom.css" media="all" />
<!-- CUSTOM STYLES -->
<link href="/favicon.ico" rel="shortcut icon" rev="1.0" title="LanLook Real Announcements" type="image/x-icon" id="favicon" />
<link href="/favicon.ico" rel="shortcut icon" rev="1.0" title="LanLook Real Announcements" />
<?php echo $this->toString($this->pageMetaTags); ?>
<title><?php echo CHtml::encode($this->pageTitle.'-'.mb_convert_case(Yii::t('msg',Yii::app()->name), MB_CASE_TITLE, Yii::app()->charset)); ?></title>
</head>
<body><a id="top-of-page"></a>
<div id="wrap" class="clearfix">
	<div class="col_12">
		<div id="header">
			<div id="logo"><?php echo CHtml::encode(Yii::t('msg', Yii::app()->name)); ?></div>
		</div> <!-- header -->
		<div class="mainmenu">
			<?php // $this->widget('application.extensions.emenu.EMenu', array('items'=>$this->menus, 'theme'=>'default' )); ?>
			<?php $this->widget('zii.widgets.CMenu', array('items'=>$this->menus )); ?>
		</div><!-- mainmenu -->
		<br clear="all" />
		<div id="language-selector" style="float: right; margin: 5px;">
		<?php $this->widget('application.components.LangBox'); ?>
		</div>
		<?php if(isset($this->breadcrumbs)):?>
			<?php $this->widget ( 'zii.widgets.CBreadcrumbs', array ('links' => $this->breadcrumbs, 'htmlOptions' => array ('class' => 'breadcrumbs alt1' ) ) );
			?> <!-- breadcrumbs -->
		<?php endif?>
		<?php echo $content; ?>
		<div class="clear"></div>
		<div id="footer">
			<?php echo Yii::t('msg', "Copyright &copy; {year} by", array('{year}'=>date('Y')));?> <?php echo Yii::t('msg', Yii::app()->name);?>.<br />
			<?php echo Yii::t('msg', 'All Rights Reserved.');?><br /><?php echo Yii::t('msg', 'Developed by')?> <?php echo $this->pageAuthor;?>
		</div><!-- footer -->
	</div>
</div><!-- END WRAP -->
</body>
</html>