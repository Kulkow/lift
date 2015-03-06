<h1>Лифты</h1>
   <div class="sort">
        <?php $sort_field = array() ?> 
        <?php foreach($sort_field as $field) : ?>
            <div class="field">
              <a href="<?php echo $field->url() ?>" title=""></a>  <?php echo $field['name'] ?>
            </div>
        <?php endforeach ?>
   </div>
   <div class="right">
    	<a href="/admin/lift/add" class="ico add text"><? echo t('site.add').' '.t('lift.name') ?></a>
    </div>
<?php if ($lifts) : ?>
   <table class="table">
        <thead>
            <tr>
                <th><? echo t('lift.number') ?></th>
                <th><? echo t('lift.level') ?></th>
                <th><? echo t('lift.house') ?></th>
                <th><? echo t('lift.status') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($lifts as $lift) : ?>
        		<tr class="item <?php //echo $lift->active ? '' : 'hide' ?>">
        		        <td><?php echo $lift->number ?></td>
                        <td><?php echo $lift->level; ?></td>
                        <td><?php echo $lift->house->number ?></td>
                        <td><?php echo $lift->status ?></td>
                        <td class="actions">
        					<a href="<?php echo $lift->url_admin('edit') ?>" class="ico edit" title="<? echo t('site.edit') ?>"></a>
        					<a href="<?php echo $lift->url_admin('delete') ?>" class="ico del" title="<? echo t('site.delete') ?>"></a>
                        </td>	
        		</tr>
        	<?php endforeach ?>
        </tbody>
    </table>
	<?php echo $paging ?>
<?php endif ?>