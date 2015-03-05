<h1>Логи</h1>
<?php if ($logs) : ?>
<div class="search form">
  <form action="" accept-charset="UTF-8" method="POST">
            <div class="it-row">
              <label for="it-field">Поле </label>
               <div class="it-select" id="it-field">
                    <select name="field">
                       <option value="event" <?php echo ($search_field == 'event' ? 'selected="selected"' : '') ?>><?php echo t('logs.event') ?></option>
                       <option value="target_id" <?php echo ($search_field == 'lift' ? 'selected="selected"' : '') ?>><?php echo t('logs.lift') ?></option>
                       <option value="user_id" <?php echo ($search_field == 'user' ? 'selected="selected"' : '') ?>><?php echo t('logs.user') ?></option>
                       <option value="level" <?php echo ($search_field == 'level' ? 'selected="selected"' : '') ?>><?php echo t('logs.level') ?></option>
                    </select>
                </div>
            </div>
            <div class="it-row">
                <label for="it-search">Поиск</label>
                <div class="it-text">
                    <input type="text" id="it-search" name="search" value="<?php echo $search ?>" />
                </div>
            </div>
            <div class="it-row last">
                <button class="it-button" value="1" name="send">Поиск</button>
            </div>
        </form>
</div>
   <table class="table">
        <thead>
            <tr>
                <th>Дата/время</th>
                <th>Пользователь</th>
                <th>Target</th>
                <th>Действие</th>
                <th>Запрос</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($logs as $log) : ?>
        		<tr class="item">
        		        <td><?php echo date("d.m.Y H:i:s", $log->created) ?></td>
                        <td><?php echo ($log->user_id ? $log->user->login : 'guest') ?></td>
                        <td><?php echo $log->target ?>/<?php echo $log->target_id ?></td>
                        <td><?php echo t($log->target.'.'.$log->event) ?></td>
                        <td><?php if (! empty($log->content)) : ?>
                            <?php $datas = unserialize($log->content); ?> 
                              <ul>
                                <?php foreach($datas as $key => $data) : ?>
                                    <li><b><?php echo $key ?></b>:<?php print_r($data) ?></li>
                                <?php endforeach ?>
                              </ul>
                            <?php else : ?>
                            <?php endif ?>
                        </td>
                        <td><?php echo $log->ip ?></td>
        		</tr>
        	<?php endforeach ?>
        </tbody>
    </table>
	<?php echo $paging ?>
<?php endif ?>
<style>
 .search.form {position: relative; overflow: hidden;}
 .search.form .it-row {float: left; width: 350px; clear: none; margin-right: 15px;}
 .search.form .it-row.last {padding-top: 20px;}
</style>