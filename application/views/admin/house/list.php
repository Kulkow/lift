<h1>Дома</h1>
   <div class="sort">
        <?php $sort_field = array() ?> 
        <?php foreach($sort_field as $field) : ?>
            <div class="field">
              <a href="<?php echo $field->url() ?>" title=""></a>  <?php echo $field['name'] ?>
            </div>
        <?php endforeach ?>
   </div>
   <div class="right">
    	<a href="/admin/house/add" class="ico add text"><? echo t('site.add').' '.t('house.name') ?></a>
    </div>
<?php if ($houses) : ?>
   <table class="table">
        <thead>
            <tr>
                <th><? echo t('house.number') ?></th>
                <th><? echo t('house.level') ?></th>
                <th><? echo t('house.description') ?></th>
                <th><? echo t('house.count_lift') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($houses as $house) : ?>
        		<tr class="item <?php //echo $house->active ? '' : 'hide' ?>">
        		        <td><?php echo $house->number ?></td>
                        <td><?php echo $house->level; ?></td>
                        <td><?php echo $house->description ?></td>
                        <td><?php echo $house->lifts->count_all() ?></td>
                        <td class="actions">
        					<a href="<?php echo $house->url_admin('edit') ?>" class="ico edit" title="<? echo t('site.edit') ?>"></a>
        					<a href="<?php echo $house->url_admin('delete') ?>" class="ico del" title="<? echo t('site.delete') ?>"></a>
                        </td>	
        		</tr>
        	<?php endforeach ?>
        </tbody>
    </table>
	<?php echo $paging ?>
<?php endif ?>