# Kohana PHP Framework
web-ресурс управление лифтами в доме.

#Устанока
1. Скачать архив  и распаковать в папку будушего сайта 
2. Прописать конфигурации базы данных application/config/database.php - можно выбрать default - или MySQLi и PostgreSQL
3. Установить права доступа 0775 на папки application/logs, application/cache, minion
4. запустить в консоли установку migrate  - миграция версий базы данных, php minnion --task=migrate --action=install
5. запустить в консоли установку структуру базы данных  php minnion --task=migrate --action=new, или php minnion --task=migrate --action=up --token=install
6. Проверяем работу сайта
7. Административная панель site.ru/admin - По-умолчанию, Логин: admin, пароль: admin
8. Администратоа может добавлять дома, лифта просматривать логи. 

#Алгоритм
