<div class="form">
<?php if ($this->selMenu->menu->menu_status>2):?>
<p><?php echo CHtml::encode($this->selMenu->page_content);  ?></p>
<?php else: ?>
<p class="errorMessage"><?php // echo CHtml::encode(UNDER_CONSTRUCT); ?></p>
<p class="errorMessage"><?php echo $this->selMenu->page_content; ?></p>
<?php endif;?>
</div>