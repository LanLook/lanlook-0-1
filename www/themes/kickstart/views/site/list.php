<div class="form">
<h3><?php echo $this->selCategory->description; ?></h3>
<?php if (isset($this->items['msg'])): ?>
<p class="errorMessage"><?php echo $this->items['msg']; ?></p>
<?php else: ?>
<p>
<?php foreach ($this->items as $id=>$prod):?>
<?php echo CHtml::link($prod->product_name, $this->createUrl('site/item', array('pid'=>$prod->product_id))). ' ('.$prod->product->quantity.')'?>
<?php endforeach;?>
</p>
<?php endif;?>
</div>