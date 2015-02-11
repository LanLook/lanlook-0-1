<style>
<!--
.title {
	float: left;
	width: 701px;
	margin: 5px 0;
	padding: 10px 10px;
	font-size: 14px;
	font-weight: bold;
	color: #999;
	border: 0px #CCC solid;
}

.normal_row {
	width:720px;
	height: 60px;
	margin: 4px auto;
}

.normal_row:hover {
	border-bottom: 1px #669900 solid;
	box-shadow: 1px 0px 4px #5C8A00;

}

.normal_col {
	float: left;
	height: 50px;
	padding: 5px 5px;
}

.col1{
	width: 40px;
	text-align:center;
}
.col1 p {
	line-height: 50px;
	margin:0;
}
.col2 {
	width: 60px;
}
.col3 {
	width: 530px;
}
.col4 {
	width: 50px; 
	text-align: center;
}

.pty0 {
	border-bottom: 1px #999 dashed;
	background-color: none;
}
.pty1 {
	border: 1px #5C8A00 solid;
	background-color: #E0EBCC;
}
.pty2 {
	border: 1px #CC5200 solid;
	background-color: #FFCC80;
}

.clear {
	width:0px;
	height:0px;
	padding:0px;
	margin:0px;
	clear:both;
}
-->
</style>

<?php // var_dump($this->items);?>


<div class="form">
<h3><?php echo $this->selCategory->description; ?></h3>


<?php if (isset($this->items['msg'])): ?>
<p class="errorMessage"><?php echo $this->items['msg']; ?></p>
<?php else: ?>

<div>
<?php foreach ($this->items as $subCategory): ?>
<h3><?php echo CHtml::link($subCategory['category_name'], $this->createUrl('site/list', array('pid'=>$subCategory['category_id']))); ?></h3>
<?php endforeach;?>
</div>
<hr class="line" />

<div>
<?php foreach ($this->items as $id=>$category): ?>

<div class="title"><?php echo $category['category_name']; ?></div>

<div class="clear"></div>

	<?php foreach ($category['items'] as $key=>$item):?>
	
	<?php // echo CHtml::openTag('a', array('href'=>CHtml::normalizeUrl(array('site/item', 'pid'=>$item['product_id']))));?>
	
	<div class="normal_row pty<?php echo $item['priority'];?>"> 
	
	<div class="normal_col col1">
	<p><?php echo $key+1;?></p>
	</div>
	<div class="normal_col col2">
	<?php echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/'.$item['file_name'], $item['product_name'],array('style'=>'width:55px')), $this->createUrl('site/item', array('pid'=>$item['product_id'])));?>
	</div>
	<div class="normal_col col3">
	<strong><?php echo CHtml::link($item['product_name'], $this->createUrl('site/item', array('pid'=>$item['product_id']))); ?></strong>
	<?php echo $item['product_description'];?>
	</div>
	<div class="normal_col col4">
	<p><?php echo CHtml::encode('('.$item['quantity'].')');?></p>
	<p><?php echo CHtml::encode('('.$item['priority'].')');?></p>
	</div>
	<div class="clear"></div>
	
	</div>
	
	<?php // echo CHtml::closeTag('a');?>
	
	<?php endforeach;?>

<?php endforeach;?>
</div>
<?php endif;?>
</div>