<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
    <title><?php echo ($site->title ? $site->title : $site->name) ?></title>
	<meta name="keywords" content="<?php echo $site->keywords ?>" />
	<meta name="description" content="<?php echo $site->description ?>" />
     <link rel="icon" href="/media/images/favicon.ico" type="image/x-icon" />
	<!--[if lte IE 8]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="<?php echo URL::site('media/css/reset.css') ?>" />
	<link rel="stylesheet" href="<?php echo URL::site('media/css/style.css') ?>" />
    <link rel="stylesheet" href="<?php echo URL::site('media/css/forms.css') ?>" />
	<script type="text/javascript" src="<?php echo URL::site('media/js/jquery-2.0.3.min.js') ?>"></script>
</head>
<body>
<div id="container">
  <div id="wrapper">
    <header id="header">
      <div class="wrapper">
           <div class="logo">
                <a href="<?php echo URL::site('/')?>" title="<?php echo $site->name ?>"></a>
           </div>
           <div class="center">
                    <?php if(! $auth_user): ?>
                    <form action="<?php echo URL::site('login') ?>" method="post" accept-charset="utf-8" class="private" autocomplete="off">
            			<div class="it-col">
                            <label for="private-login"><? echo t('user.login') ?></label>
            				<input type="text" id="private-login" name="login" value="" class="it-text placeholder" data-placeholder="Логин" />
                            <!--<input type="checkbox" name="noremember" id="noremember" /><label for="noremember">Чужой компьютер</label>-->
            			</div>
            			<div class="it-col">
                            <label for="private-password"><? echo t('user.password') ?></label>
            				<input type="password" id="private-password" name="password" value="" class="it-text placeholder" data-placeholder="Пароль" />
                            <a href="<?php echo URL::site('forgot') ?>" tabindex="-1" class="forgot"><? echo t('user.forgot') ?></a>
            			</div>
            			<div class="it-col last">
                            <input type="hidden" name="token" value="<?php echo Security::token() ?>" />
            				<button type="submit" name="submit" value="1" class="it-button small">Войти</button>
            			</div>
            		</form>
                    <?php else: ?>
                    <div class="private auth">
                       <div class="it-col">
                         <label for="private-login">Логин</label>
                         <a href="<?php echo $auth_user->url(); ?>" title="" class="login"> <?php echo $auth_user->login ?></a>
                       </div>
                        <div class="it-col last">
                            <a href="<?php echo URL::site('logout') ?>" title="" class="logout it-button">Выйти</a>
                         </div>
                    </div>
                    <?php endif ?>
           </div>
       </div>    
     </header>  
        <div id="content"  <?php echo (! $sidebar ? 'class="full"' : ''); ?> >
           <div id="page" <?php echo (! $sidebar ? 'class="full"' : ''); ?> >
              	<?php if (isset($exception)) : ?>
						<h3><?php echo t('error.'.$exception.'.title') ?></h3>
						<div class="block content">
							<?php echo t('error.'.$exception.'.content') ?>
						</div>
						<div class="links">
							<a href="<?php echo URL::site() ?>"><?php echo t('site.go.home') ?></a>
							<a href="javascript:history.go(-1)"><?php echo t('site.go.back') ?></a>
						</div>
					<?php else : ?>
						<?php echo Message::render() ?>
						<?php echo $content ?>
					<?php endif ?>
           </div>
           <?php if($sidebar) : ?>
              <?php echo View::factory('sidebar'); ?>
        <?php endif ?>
    </div>
 </div>
</div>
<footer id="footer">
   <div class="line"></div> 
   <div class="wrapper">
       <div class="copyright">
           <p>&copy; 2013 Компьютерный центр.</p> 
           <span> Все права защищены.</span>
       </div>
       <div class="develop">
            <p>Проект разработан в веб-студии</p>
            <a href="http://ip-design.ru" title="Интернет-проекты">Интернет-проекты</a>
       </div>
   </div>    
</footer>
</body>
</html>