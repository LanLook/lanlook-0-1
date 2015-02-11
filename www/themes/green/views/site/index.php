<?php foreach ($model as $category): ?>
<div style="float:left;width:47%;display: block;padding:10px">
<h3><?php echo CHtml::link($category['label'], $category['url']);?></h3>
<?php echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/'.$category['pic'], 'Tested', array('style'=>'box-shadow: 0 0 5px #999;margin: 10px;')), $category['url']); ?>
<?php if (isset($category['items'])): ?>
	<div align="justify">
		<?php foreach ($category['items'] as $subcategory): ?>
		<?php if (isset($subcategory['label'])): ?>
		<span><?php echo CHtml::link($subcategory['label'], $subcategory['url'], array('style'=>'text-decoration:none;'));?></span>
		<?php endif;?>
		<?php endforeach;?>
	</div>
<?php endif;?>
<hr style="border-size:1px;background-color:#0099ff;margin-top:10px;" />
</div>
<?php endforeach;?>