<aside id="sidebar">
    <nav class="nav">
    <?php // Menu::render('left') ?>
      <ul>
        <!--<li <?php echo ($side_menu == 'balans' ? 'class="active"' : '') ?> ><a href="<?php echo $user->url('balans') ?>" title="Баланс">Баланс</a></li>-->
        <!--  <li <?php echo ($side_menu == 'plan' ? 'class="active"' : '') ?>><a href="<?php echo $auth_user->url('plan') ?>">Планируемые начисления</a></li>-->
       <!-- <li <?php echo ($side_menu == 'order' ? 'class="active"' : '') ?> ><a href="<?php echo $auth_user->url('order') ?>">Заказать выписку</a></li>-->
        <!--<li <?php echo ($side_menu == 'reports' ? 'class="active"' : '') ?> ><a href="<?php echo $user->url('reports') ?>" title="Отчеты">Отчеты</a></li>-->
        <?php $settings = array('new_password', 'setting', 'profile', 'subscription'); ?>
        <li <?php echo (in_array($side_menu, $settings)  ? 'class="active"' : '') ?> ><a href="<?php echo $user->url('setting') ?>" title="Настройки">Настройки</a></li>
      </ul>
    </nav>
</aside>