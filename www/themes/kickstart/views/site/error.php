<div class="form">
<?php if (isset($this->selMenu->msg)): ?>
<div class="errorMessage">
<?php echo CHtml::encode($this->selMenu->msg); ?>
</div>
<?php else: ?>
<h2><?php echo $this->pageTitle; ?> <?php echo CHtml::encode($code); ?></h2>
<div class="errorMessage">
<?php echo CHtml::encode($message); ?>
</div>
<?php endif;?>
</div>
