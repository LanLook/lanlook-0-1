<div class="form">
<?php if (isset($this->selItem->msg)):?>
<p class="errorMessage"><?php echo CHtml::encode($this->selItem->msg)?></p>
<?php else:?>
<p><?php echo CHtml::encode($this->selItem->description)?></p>
<p><?php echo $this->selItem->full_description?></p>
<?php endif;?>
</div>