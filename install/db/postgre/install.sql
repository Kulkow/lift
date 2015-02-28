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
  target_id integer DEFAULT NULL,
  user_id integer DEFAULT NULL,
  level integer DEFAULT NULL,
  distance integer DEFAULT NULL,
  content text NOT NULL,
  created integer NOT NULL,
  ip varchar NOT NULL
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

CREATE TABLE roles
(
  id serial PRIMARY KEY,
  "name" varchar(32) NOT NULL UNIQUE,
  description text NOT NULL
);

INSERT INTO roles (id, name, description) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

CREATE TABLE roles_users
(
  user_id integer,
  role_id integer
);

CREATE TABLE users
(
  id serial PRIMARY KEY,
  email varchar(254) NOT NULL UNIQUE,
  login varchar(32) NOT NULL UNIQUE,
  "password" varchar(64) NOT NULL,
  logins integer NOT NULL DEFAULT 0,
  last_login integer,
  phone varchar DEFAULT NULL,
  name varchar NOT NULL,
  address text,
  created integer,
  active_time integer
  CONSTRAINT users_logins_check CHECK (logins >= 0)
);

INSERT INTO  users  ( id ,  login ,  email ,  phone ,  name ,  address ,  password ,  created ,  logins ,  last_login ,  active_time ) VALUES
(3, 'admin', 'scorp1785@mail.ru', '+79374353527', 'Кульков Игорь', 'г. Пенза, ул. Гагарина, 16', '671728946c25115dfb7ac934d2dae387b5f45da2fad065fadb960ecbec597948', 1378369661, 66, 1424198481, NULL),
(4, 'guest', 'guest@guest.guest', 'guest', 'guest', 'guest', '', NULL, 0, NULL, NULL);

CREATE TABLE user_tokens
(
  id serial PRIMARY KEY,
  user_id integer NOT NULL,
  user_agent varchar NOT NULL,
  token character varying NOT NULL UNIQUE,
  created integer NOT NULL,
  expires integer NOT NULL
);

CREATE INDEX user_id_idx ON roles_users (user_id);
CREATE INDEX role_id_idx ON roles_users (role_id);

ALTER TABLE roles_users
  ADD CONSTRAINT user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  ADD CONSTRAINT role_id_fkey FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE;

ALTER TABLE user_tokens
  ADD CONSTRAINT user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

INSERT INTO roles (name, description) VALUES ('login', 'Login privileges, granted after account confirmation');
INSERT INTO roles (name, description) VALUES ('admin', 'Administrative user, has access to everything.');

