Здравствуйте, <b><?php echo $user->login ?>!<br /><br />
Для получения нового пароля перейдите по ссылке <a href="<?php echo URL::site('confirm/'.$token, TRUE, FALSE) ?>"><?php echo URL::site('confirm/'.$token, TRUE, FALSE) ?></a><br /><br />
Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо или пройдите по ссылке <a href="<?php echo URL::site('cancel/'.$token, TRUE, FALSE) ?>"><?php echo URL::site('cancel/'.$token, TRUE, FALSE) ?></a><br /><br />
С уважением, команда проекта &laquo;<a href="<?php echo URL::site('', TRUE, FALSE) ?>"><?php echo $site->name ?></a>&raquo;.