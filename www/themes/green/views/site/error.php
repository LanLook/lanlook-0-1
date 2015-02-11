<div class="form">
<?php if (isset($this->selMenu->msg)): ?>
<p class="errorMessage"><?php echo CHtml::encode($this->selMenu->msg); ?></p>
<?php else: ?>
<p class="errorMessage"><?php echo CHtml::encode($message); ?></p>
<?php endif;?>
</div>
