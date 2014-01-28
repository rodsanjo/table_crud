/*
 * @file: dables_and_user.sql
 * @author: jequeto@gmail.com
 * @since: 2012 enero
*/

-- use daw2;

set names utf8;
set sql_mode = 'traditional';




insert into daw2_roles
  (rol					, descripcion) values
  ('administradores'	,'Administradores de la aplicación')
, ('usuarios'			,'Todos los usuarios incluido anónimo')
, ('usuarios_logueados'	,'Todos los usuarios excluido anónimo')
;


insert into daw2_usuarios 
  (login, email, password, fecha_alta ,fecha_confirmacion_alta, clave_confirmacion) values
  ('admin', 'admin@email.com', md5('admin00'), default, now(), null)
, ('anonimo', 'anonimo@email.com', md5(''), default, now(), null)
, ('juan', 'juan@email.com', md5('juan00'), default, now(), null)
, ('anais', 'anais@email.com', md5('anais00'), default, now(), null)
;

-- insert into daw2_metodos
--   (controlador,		metodo) values
--   ('*'				,'*')
-- , ('categorias'		,'*')
-- , ('articulos'		,'*')
-- , ('inicio'			,'*')
-- , ('usuarios'		,'*')
-- , ('usuarios'		,'desconectar')
-- , ('usuarios'		,'form_login')
-- , ('usuarios'		,'form_login_validar')
-- , ('usuarios'		,'confirmar_alta')
-- ;

insert into daw2_roles_permisos
  (rol					,controlador		,metodo) values
  ('administradores'	,'*'				,'*')
, ('usuarios'			,'inicio'			,'*')
, ('usuarios'			,'mensajes'			,'*')
, ('usuarios_logueados' ,'usuarios'			,'desconectar')
, ('usuarios_logueados' ,'usuarios'			,'form_cambiar_password')
, ('usuarios_logueados' ,'usuarios'			,'form_cambiar_password_validar')
, ('usuarios_logueados' ,'categorias'			,'index')

;

insert into daw2_usuarios_roles
  (login		,rol) values
  ('admin'		,'administradores')
, ('juan'		,'administradores')
-- , ('anonimo'	,'usuarios')
-- , ('juan'		,'usuarios')
-- , ('juan'		,'usuarios_logueados')
-- , ('anais'		,'usuarios')
-- , ('anais'		,'usuarios_logueados')
;


insert into daw2_usuarios_permisos
  (login			,controlador			,metodo) values
  ('anonimo'		,'usuarios'				,'form_login')
, ('anonimo'		,'usuarios'				,'form_login_validar')
, ('anonimo'		,'usuarios'				,'form_login_email')
, ('anonimo'		,'usuarios'				,'form_login_email_validar')
, ('anonimo'		,'usuarios'				,'confirmar_alta')
, ('anonimo'		,'usuarios'				,'form_insertar_externo')
, ('anonimo'		,'usuarios'				,'form_insertar_externo_validar')
;


insert into daw2_categorias
  ( nombre, descripcion ) values
  ('lacteos', null)
, ('frutas', null)
, ('legumbres', null)
, ('refrescos', null)
;


insert into daw2_articulos
  ( categoria_nombre, nombre,precio,unidades_stock ) values
  ('lacteos','leche', 1,500)
, ('lacteos','mantequilla', 0.5, 300)
, ('legumbres', 'arroz', 0.90, 500)
, ('refrescos', 'limonada', 1, 333)
;


truncate table daw2_menu;
insert into daw2_menu
  (id, es_submenu_de_id	, nivel	, orden	, texto, accion_controlador, accion_metodo, title) values
  (1 , null				, 1		, null	, 'Inicio', 'inicio', 'index', null)
, (2 , null				, 1		, null	, 'Internacional', 'inicio', 'internacional', null)
, (3 , null				, 1		, null	, 'Libros', 'libros', 'index', null)
, (4 , null				, 1		, null	, 'Revista', 'revista', 'index', null)
, (5 , null				, 1		, null	, 'Usuarios', 'usuarios', 'index', null)
, (6 , null				, 1		, null	, 'Categorías', 'categorias', 'index', null)
, (7 , null				, 1		, null	, 'Artículos', 'articulos', null, null)
, (8 , 7				, 2		, null	, 'listado', 'articulos', 'index', null)
, (9 , 7				, 2		, null	, 'recuento por categoría', 'articulos', 'recuento_por_categoria', null)
;

