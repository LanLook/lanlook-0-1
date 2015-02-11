<div class="form">
<?php if (isset($this->selItem->msg)):?>
<p class="errorMessage"><?php echo CHtml::encode($this->selItem->msg)?></p>
<?php else:?>
<p><?php echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/'.$this->selItem->product->productFiles[1]->file_name, $this->selItem->product_name,array('style'=>'width:200px')), $this->createUrl('site/item', array('pid'=>$this->selItem->product_id)));?></p>
<p><?php echo CHtml::encode($this->selItem->description)?></p>
<p><?php echo $this->selItem->full_description?></p>
<?php endif;?>
</div>
<?php foreach ($this->selItem->product->productFiles as $id=>$file): ?>
<div style="float:left;margin:10px"><?php echo CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/images/'.$file->file_name, $this->selItem->product_name,array('style'=>'width:200px')), $this->createUrl('site/item', array('pid'=>$this->selItem->product_id)));?></div>
<?php endforeach;?>
<div style="clear: both;"></div>