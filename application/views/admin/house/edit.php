<h1> <?php echo (Arr::get($_REQUEST, 'id', FALSE) ? t('site.edit') : t('site.add')) ?>&laquo;<? echo t('site.edit') ?>&raquo;</h1>
<form action="" method="post" accept-charset="utf-8">
   <div class="it-row">
		<label for="level-id"><? echo t('house.level') ?></label>
		<div class="it-text"><input type="text" id="level-id" name="level" value="<?php echo Arr::get($_REQUEST, 'level') ?>" /></div>
		<?php if ($error = Arr::get($errors, 'level')) : ?>
			<div class="it-error"><?php echo $error ?></div>
		<?php endif ?>
	</div>
    <div class="it-row">
		<label for="number-id"><? echo t('house.number') ?></label>
		<div class="it-text"><input type="text" id="number-id" name="number" value="<?php echo Arr::get($_REQUEST, 'number') ?>" /></div>
		<?php if ($error = Arr::get($errors, 'number')) : ?>
			<div class="it-error"><?php echo $error ?></div>
		<?php endif ?>
	</div>
    <div class="it-row">
		<label for="description-id"><? echo t('house.description') ?></label>
		<div class="it-text"><input type="text" id="description-id" name="description" value="<?php echo Arr::get($_REQUEST, 'description') ?>" /></div>
		<?php if ($error = Arr::get($errors, 'description')) : ?>
			<div class="it-error"><?php echo $error ?></div>
		<?php endif ?>
	</div>
	<div class="it-row">
		<button type="submit" name="submit" value="1" class="it-button">Сохранить</button>
	</div>
</form>

