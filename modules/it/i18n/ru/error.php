<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'401.title'		=> '401. Вы не авторизованы.',
	'401.content'	=> '<p>Для доступа в данный раздел необходимо авторизоваться.</p>',

	'403.title'		=> '403. Доступ запрещен.',
	'403.content'	=> '<p>У Вас не доступа в данный раздел, по всем вопросам обращайтесь к администрации сайта.</p>',

	'404.title'		=> '404. Страница не найдена.',
	'404.content'	=> '<p>Данная страница не найдена. Возможно ее никогда не существовало, либо она была удалена.</p>',

	'500.title'		=> '500. Ошибка сервера.',
	'500.content'	=> '<p>Внутренняя ошибка сервера.</p>',

	'csrf.title'	=> 'CSRF',
	'csrf.content'	=> '<p><b>CSRF</b> (англ.&nbsp;<i><span xml:lang="en" lang="en">Сross Site Request Forgery</span></i>&nbsp;— «Подделка межсайтовых запросов», также известен как XSRF)&nbsp;— вид атак на посетителей веб-сайтов, использующий недостатки протокола HTTP. Если жертва заходит на сайт, созданный злоумышленником, от её лица тайно отправляется запрос на другой сервер (например, на сервер платёжной системы), осуществляющий некую вредоносную операцию (например, перевод денег на счёт злоумышленника). Для осуществления данной атаки, жертва должна быть авторизована на том сервере, на который отправляется запрос, и этот запрос не должен требовать какого-либо подтверждения со стороны пользователя, который не может быть проигнорирован или подделан атакующим скриптом.</p><p>Одно из применений CSRF&nbsp;— эксплуатация пассивных XSS, обнаруженных на другом сервере. Так же возможны отправка писем (спам) от лица жертвы и изменение каких-либо настроек учётных записей на других сайтах (например, секретного вопроса для восстановления пароля).</p>',

);
