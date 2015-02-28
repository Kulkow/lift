CREATE TABLE IF NOT EXISTS house
(
  id SERIAL  PRIMARY KEY,
  level integer,
  number integer,
  description text
)
WITH (
  OIDS=FALSE
);

INSERT INTO house (id, level, description, number) VALUES
(1, 10, 'Новый дом', 5);

CREATE TABLE IF NOT EXISTS lift
(
   id  SERIAL PRIMARY KEY,
   level  integer NOT NULL,
   number  integer NOT NULL,
   house_id  integer NOT NULL,
   status  integer DEFAULT NULL,
   current  integer NOT NULL,
   direction  varchar NOT NULL,
   updated  integer NOT NULL 
)WITH (
  OIDS=FALSE
);

INSERT INTO lift (id, level, number, house_id, status, current, direction, updated) VALUES
(2, 5, 1, 1, 0, 5, 'down', 1425109321),
(3, 1, 2, 1, 0, 1, 'down', 1425111323),
(4, 1, 3, 1, 2, 1, 'down', 1425117073),
(5, 4, 4, 1, 0, 4, 'up', 1425110850);


CREATE TABLE IF NOT EXISTS log
(
  id  SERIAL PRIMARY KEY,
  event varchar NOT NULL,
  target varchar NOT NULL,
  description text NOT NULL,
  created integer NOT NULL
)WITH (
  OIDS=FALSE
);

CREATE TABLE IF NOT EXISTS request
(
  id  SERIAL PRIMARY KEY,
  lift_id integer NOT NULL,
  user_id integer NOT NULL,
  level integer NOT NULL,
  direction varchar NOT NULL,
  status integer DEFAULT NULL,
  created integer NOT NULL,
  ip varchar NOT NULL
)WITH (
  OIDS=FALSE
);

CREATE TABLE IF NOT EXISTS roles
(
  id  SERIAL PRIMARY KEY,
  name varchar NOT NULL UNIQUE,
  description varchar NOT NULL
)WITH (
  OIDS=FALSE
);

INSERT INTO roles (id, name, description) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

CREATE TABLE IF NOT EXISTS roles_users
(
  user_id  SERIAL PRIMARY KEY,
  role_id  integer UNIQUE
)WITH (
  OIDS=FALSE
);

CREATE TABLE IF NOT EXISTS  site  (
  id  SERIAL PRIMARY KEY,
   name  varchar NOT NULL,
   logo  varchar DEFAULT NULL,
   slogan  varchar DEFAULT NULL,
   brief  text,
   content  text,
   keywords  varchar DEFAULT NULL,
   description  varchar DEFAULT NULL,
   skin  varchar NOT NULL
)WITH (
  OIDS=FALSE
);

INSERT INTO  site  ( id ,  name ,  logo ,  slogan ,  brief ,  content ,  keywords ,  description ,  skin ) VALUES
(1, 'Работа 4 лифтов в доме', 'Работа 4 лифтов в доме', 'Слоган', '', '<p>Текст на главной</p>', 'key', 'desc', 'rcc');

CREATE TABLE IF NOT EXISTS  users (
   id  SERIAL PRIMARY KEY,
   login  varchar NOT NULL UNIQUE,
   email  varchar NOT NULL,
   phone  varchar DEFAULT NULL,
   name  varchar NOT NULL,
   address  text,
   password  varchar NOT NULL,
   created  integer DEFAULT NULL,
   logins  integer NOT NULL DEFAULT '0',
   last_login  integer DEFAULT NULL,
   active_time  integer DEFAULT NULL
)WITH (
  OIDS=FALSE
);
INSERT INTO  users  ( id ,  login ,  email ,  phone ,  name ,  address ,  password ,  created ,  logins ,  last_login ,  active_time ) VALUES
(3, 'admin', 'scorp1785@mail.ru', '+79374353527', 'Кульков Игорь', 'г. Пенза, ул. Гагарина, 16', '671728946c25115dfb7ac934d2dae387b5f45da2fad065fadb960ecbec597948', 1378369661, 66, 1424198481, NULL),
(4, 'guest', 'guest@guest.guest', 'guest', 'guest', 'guest', '', NULL, 0, NULL, NULL);
