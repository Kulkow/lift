<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<title><?php echo $site->title ?></title>
	<meta name="keywords" content="<?php echo $site->keywords ?>" />
	<meta name="description" content="<?php echo $site->description ?>" />
	<link rel="shortcut icon" href="/favicon.ico" />
  	<link rel="icon" href="/favicon.ico" />
   <!--	<script src="<?php echo URL::site('media/js/browser.js') ?>"></script>-->
  	<link rel="stylesheet" href="<?php echo URL::site('media/libs/it/css/it.reset.css') ?>" />
	<link rel="stylesheet" href="<?php echo URL::site('media/css/style.css') ?>" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

</head>
<body>
	<div id="container">
		<header id="header">
			<a href="" title="logo"></a>
            <div class="">Компьютерный центр</div>
            <?php if($auth_user): ?>
                <a href="<?php echo $auth_user->url(); ?>" title="Личный кабинет">Личный кабинет</a>
                <a href="<?php echo Site::url('logout'); ?>" title="Выйти">Выйти</a>
            <?php else : ?>
               <a href="<?php echo Site::url('login'); ?>" title="Авторизоваться">Авторизоваться</a>
            <?php endif ?> 
		</header>
		<div id="wrapper">
			<div class="holder">
				<div id="content">
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
			</div>
		</div>
	</div>
	<footer id="footer">
		<div class="split"></div>
		<div class="holder">
			<p class="poweredby">Сайт разработан в студии</p>
			<p class="copyright">&copy; <?php echo date("Y") ?>, <a href="<?php echo URL::site() ?>">to do</a></p>
		</div>
	</footer>
</body>
</html>