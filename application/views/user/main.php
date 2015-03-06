<h1><?php echo $user->fullname() ?></h1>
<div class="content">
    <p>Бонусная карта <?php echo $user->login;  ?></p>
    <h2>Контакты</h2>
        <ul>
            <li><p>Телефон: <?php echo $user->phone;  ?></p></li>
            <li><p>E-mail: <?php echo $user->email;  ?></p></li>
            <?php if($user->address) : ?>
                <li><p>Адрес: <?php echo $user->address;  ?></p></li>
            <?php endif ?>
        </ul>
</div>