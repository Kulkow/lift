<h1>Баланс</h1>
<?php if($auth_user->active_time > time()): ?>
<!--<p>Ожидайте активации бонусной карты:<?php echo date('d.m.Y', $auth_user->active_time) ?></p>-->
<?php endif ?>
<?php if($balance) : ?>
    <p><b>Текущий баланс:</b><?php echo $balance  ?> баллов</p>
<?php else : ?>
<p>Ожидайте начисления баллов</p>
<?php endif ?>
<?php if($count): ?>
<table class="table">
    <thead>
        <th>Дата планируемого начисления</th>
        <th>Операция</th>
        <th>Баллов</th>
    </thead>
    <tbody>
       <?php foreach($plans as $plan) : ?>
        <tr>
            <td><?php echo date("d.m.Y H:i",$plan->accrual) ?></td>
            <td><?php echo $plan->operation() ?></td>
            <td><?php echo $plan->ball ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
<?php else : ?>
<!--<p>Нет планируемых начислений</p>-->
<?php endif ?>