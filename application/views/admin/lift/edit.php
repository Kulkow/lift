<h1> <?php echo (Arr::get($_REQUEST, 'id', FALSE) ? t('site.edit') : t('site.add')) ?>&laquo;<? echo t('lift.name') ?>&raquo;</h1>
<form action="" method="post" accept-charset="utf-8">
   <div class="it-row">
		<label for="number-id"><? echo t('lift.number') ?></label>
		<div class="it-text"><input type="text" id="number-id" name="number" value="<?php echo Arr::get($_REQUEST, 'number') ?>" /></div>
		<?php if ($error = Arr::get($errors, 'number')) : ?>
			<div class="it-error"><?php echo $error ?></div>
		<?php endif ?>
	</div>
    <div class="it-row">
		<label for="house-id"><? echo t('lift.house') ?></label>
        <div class="it-select">
            <select name="house_id" id="house-id">
                <? foreach(ORM::factory('house')->find_all() as $house): ?>
                    <option value="<? echo $house->id ?>" <? echo ($house->id == Arr::get($_REQUEST,'house_id', NULL)) ?>><? echo t('house.name').' '.t('house.number').' '.$house->number; ?></option>
                <? endforeach ?>
            </select>
        </div>
		<?php if ($error = Arr::get($errors, 'house')) : ?>
			<div class="it-error"><?php echo $error ?></div>
		<?php endif ?>
	</div>
	<div class="it-row">
		<button type="submit" name="submit" value="1" class="it-button">Сохранить</button>
	</div>
</form>

