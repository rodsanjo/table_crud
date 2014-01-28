
/*
 * @file: tables_and_user.sql
 * @author: jequeto@gmail.com
 * @since: 2012 enero
*/
drop database if exists daw2;
create database daw2;

create user daw2_user identified by 'daw2_user';
# Concedemos al usuario daw2_user todos los permisos sobre esa base de datos
grant all privileges on daw2.* to daw2_user;

use daw2;

set names utf8;

set sql_mode = 'traditional';

drop table if exists daw2_categorias;
create table if not exists daw2_categorias
( id integer auto_increment
, nombre varchar(100) not null
, descripcion varchar(1000) null
, primary key (id)
, unique (nombre)
)
engine = myisam default charset=utf8
;


drop table if exists daw2_articulos;
create table if not exists daw2_articulos
( id integer auto_increment
, categoria_nombre varchar(100) not null
, nombre varchar(100) not null
, precio decimal(12,2) null default null
, unidades_stock decimal(12,2) null default null
, primary key (id)
, unique (nombre)
, foreign key (categoria_nombre) references catgegorias(nombre)
)
engine = myisam default charset=utf8
;


drop table if exists daw2_usuarios;
CREATE TABLE daw2_usuarios (
id int(11) NOT NULL AUTO_INCREMENT,
login varchar(30) NOT NULL,
email varchar(100) NOT NULL,
password char(32) NOT NULL,
fecha_alta timestamp not null default current_timestamp(),
fecha_confirmacion_alta datetime default null,
clave_confirmacion char(30) null,
PRIMARY KEY (id),
UNIQUE KEY login (login),
UNIQUE KEY email (email)
)
ENGINE=myisam DEFAULT CHARSET=utf8
;


-- Recursos almacena la colección de funcionalidades que es posible desarrollar en la aplicación.
drop table if exists daw2_metodos;
create table daw2_metodos
( id integer unsigned auto_increment not null
, controlador varchar(50) not null comment "Si vale * equivale a todos los controladores"
, metodo varchar(50) null comment "Si vale * equivale a todos los métodos de un controlador. Si nulo equivale a una sección sin submenú." 
, destino  varchar(50) null comment "Utilización de este recurso en la aplicación"
, texto_menu varchar(100) null comment "Texto que aparecerá en el menú desplegable y en los botones"
, descripcion varchar(255) null comment "Explicación de la acción, aparecerá en el title"
, primary key (id)
, unique (controlador, metodo)

)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=myisam;

/*
 * Un rol es igual que un grupo de trabajo o grupo de usuarios.
 * Todos los usuarios serán miembros del rol usuario.
 */


drop table if exists daw2_roles;
create table daw2_roles
( id integer unsigned auto_increment not null
, rol varchar(50) not null
, descripcion varchar(255) null
, primary key (id)
, unique (rol)
)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=myisam;


/* seccion y subseccion se validarán en v_negocios_permisos */
drop table if exists daw2_roles_permisos;
create table daw2_roles_permisos
( id integer unsigned auto_increment not null
, rol varchar(50) not null
, controlador varchar(50) not null comment "Si vale * equivale a todos los controladores"
, metodo varchar(50) null comment "Si vale * equivale a todos los métodos de un controlador"
, primary key (id)
, unique(rol, controlador, metodo) -- Evita que a un rol se le asinge más de una vez un mismo permiso
, foreign key (rol) references daw2_roles(rol) on delete cascade on update cascade
-- , foreign key (controlador, metodo) references daw2_metodos(controlador, metodo) on delete cascade on update cascade
)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=myisam;


drop table if exists daw2_usuarios_roles;
create table daw2_usuarios_roles
( id integer unsigned auto_increment not null
, login varchar(20) not null
, rol varchar(50) not null

, primary key (id)
, unique (login, rol) -- Evita que a un usuario se le asigne más de una vez el mismo rol
, foreign key ( login) references daw2_usuarios(login) on delete cascade on update cascade
, foreign key ( rol) references daw2_roles(rol) on delete cascade on update cascade
)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=myisam;



-- Algunos hosting no dan el permiso de trigger por lo que habrá que implementarlo en programación php.
drop trigger if exists daw2_t_usuarios_ai;
delimiter //
create trigger daw2_t_usuarios_ai after insert on daw2_usuarios
for each row
begin
	insert into daw2_usuarios_roles (login, rol) values ( new.login, 'usuarios');
	if (new.login != "anonimo") then
		insert into daw2_usuarios_roles (login,  rol) values ( new.login, 'usuarios_logueados');
	end if;
end;

//
delimiter ;



drop table if exists daw2_usuarios_permisos;
create table daw2_usuarios_permisos
( id integer unsigned auto_increment not null
, login varchar(20) not null
, controlador varchar(50) not null comment "Si vale * equivale a todos los controladores"
, metodo varchar(50) null comment "Si vale * equivale a todos los métodos de un controlador"

, primary key (id)
, unique(login, controlador, metodo) -- Evita que a un usuario se le asignen más de una vez un permiso
, foreign key (login) references daw2_usuarios(login) on delete cascade on update cascade
-- , foreign key (controlador, metodo) references daw2_metodos(controlador, metodo) on delete cascade on update cascade

)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=myisam;


drop table if exists daw2_menu;
create table daw2_menu
( id integer unsigned not null
, es_submenu_de_id integer unsigned null
, nivel integer unsigned not null comment '1 menu principal, 2 submenú, ...'
, orden integer unsigned null comment 'Orden en que aparecerán'
, texto varchar(50) not null comment 'Texto a mostrar en el item del menú'
, accion_controlador varchar(50) not null
, accion_metodo varchar(50) null comment 'null si es una entrada de nivel 1 con submenu de nivel 2'
, title varchar(255) null
, primary key (id)
, foreign key (es_submenu_de_id) references daw2_menu(id)
, unique (es_submenu_de_id, texto) -- Para evitar repeticiones de texto
, unique (accion_controlador, accion_metodo) -- Si una acción/funcionalidad solo debe aparecer una vez en el menú
)
CHARACTER SET utf8 COLLATE utf8_general_ci
engine=myisam;



drop table if exists daw2_foros;
create table daw2_foros
( id integer unsigned auto_increment not null primary key 
, nombre varchar(100) not null unique
, fecha_alta timestamp not null default current_timestamp()
, creador_usuario_id integer unsigned not null references foros_usuarios(id)
)
engine = myisam;

/*
El tema de un foro es equivalente a una pregunta.
*/
drop table if exists daw2_foros_temas;
create table daw2_foros_temas
( id integer unsigned auto_increment not null primary key
, titulo varchar(100) not null references foros_foros(id) on delete cascade
, fecha_alta timestamp not null default current_timestamp()
, daw2_id integer unsigned not null
, creador_usuario_id integer unsigned not null references foros_usuarios(id)

, unique(daw2_id, titulo)
)
engine = myisam;


/*
El mensaje dentro de un tema es equivalente a una respuesta a la pregunta (tema) dentro de un foro.
*/
drop table if exists daw2_foros_temas_mensajes;
create table daw2_foros_temas_mensajes
( id integer unsigned auto_increment not null primary key
, titulo varchar(255) not null
, texto varchar(1000) not null
, fecha_alta timestamp not null default current_timestamp()
, tema_id integer unsigned not null references foros_foros_temas(id) on delete cascade
, creador_usuario_id integer unsigned not null references foros_usuarios(id)

)
engine = myisam;
