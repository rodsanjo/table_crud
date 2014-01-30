/*
 * @file: dables_and_user.sql
 * @author: jergo23@gmail.com
 * @since: 2014 enero
*/

-- use rodsanjo;

set names utf8;
set sql_mode = 'traditional';

insert into daw2_tipos values
(0,'metales alcalinos')
,(1,'alcalinoterreos')
,(2,'otros metales')
,(3,'metales de transición')
,(4,'lantánidos')
,(5,'actínidos')
,(6,'metaloides')
,(7,'no metales')
,(8,'halógenos')
,(9,'gases nobles')
,(10,'elementos desconocidos')
;

insert into daw2_elementos
 (nombre, simbolo_quimico, numero_atomico, masa_atomica, tipo_id, fecha_entrada, fecha_salida) values
 ('Oxigeno', 'O', 2, 15.9994, 7,'1994-10-15', null)
,('Hidrogeno', 'H', 1, 1.00794, 7,'1994-10-10', null)
,('Hierro', 'Fe', 26, 55.845, 3,'1996-04-20', '2016-11-05')
,('Fluor', 'F', 9, 18.998403, 8,'1999-10-15', '2014-12-05')
,('Helio', 'He', 2, 4.002602, 9,'1994-10-15', '2015-06-15')
,('Antimonio', 'Sb', 51, 121.76, 6,'1994-10-15', '2007-02-15')
,('Estaño', 'Sn', 50, 118.71, 2,'2002-02-20', '2009-08-20')
,('Estroncio', 'Sr', 38, 87.62, 1, null, '2023-06-15')
,('Potasio', 'K', 19, 39.0983, 0, null, '2020-01-05')
;


-- insert into daw2_roles
--   (rol					, descripcion) values
--   ('administradores'	,'Administradores de la aplicación')
-- , ('usuarios'			,'Todos los usuarios incluido anónimo')
-- , ('usuarios_logueados'	,'Todos los usuarios excluido anónimo')
-- ;
-- 
-- 
-- insert into daw2_usuarios 
--   (login, email, password, fecha_alta ,fecha_confirmacion_alta, clave_confirmacion) values
--   ('admin', 'admin@email.com', md5('admin00'), default, now(), null)
-- , ('anonimo', 'anonimo@email.com', md5(''), default, now(), null)
-- , ('juan', 'juan@email.com', md5('juan00'), default, now(), null)
-- , ('anais', 'anais@email.com', md5('anais00'), default, now(), null)
-- ;
-- 
-- -- insert into daw2_metodos
-- --   (controlador,		metodo) values
-- --   ('*'				,'*')
-- -- , ('categorias'		,'*')
-- -- , ('articulos'		,'*')
-- -- , ('inicio'			,'*')
-- -- , ('usuarios'		,'*')
-- -- , ('usuarios'		,'desconectar')
-- -- , ('usuarios'		,'form_login')
-- -- , ('usuarios'		,'form_login_validar')
-- -- , ('usuarios'		,'confirmar_alta')
-- -- ;
-- 
-- insert into daw2_roles_permisos
--   (rol					,controlador		,metodo) values
--   ('administradores'	,'*'				,'*')
-- , ('usuarios'			,'inicio'			,'*')
-- , ('usuarios'			,'mensajes'			,'*')
-- , ('usuarios_logueados' ,'usuarios'			,'desconectar')
-- , ('usuarios_logueados' ,'usuarios'			,'form_cambiar_password')
-- , ('usuarios_logueados' ,'usuarios'			,'form_cambiar_password_validar')
-- , ('usuarios_logueados' ,'categorias'			,'index')
-- 
-- ;
-- 
-- insert into daw2_usuarios_roles
--   (login		,rol) values
--   ('admin'		,'administradores')
-- , ('juan'		,'administradores')
-- -- , ('anonimo'	,'usuarios')
-- -- , ('juan'		,'usuarios')
-- -- , ('juan'		,'usuarios_logueados')
-- -- , ('anais'		,'usuarios')
-- -- , ('anais'		,'usuarios_logueados')
-- ;
-- 
-- 
-- insert into daw2_usuarios_permisos
--   (login			,controlador			,metodo) values
--   ('anonimo'		,'usuarios'				,'form_login')
-- , ('anonimo'		,'usuarios'				,'form_login_validar')
-- , ('anonimo'		,'usuarios'				,'form_login_email')
-- , ('anonimo'		,'usuarios'				,'form_login_email_validar')
-- , ('anonimo'		,'usuarios'				,'confirmar_alta')
-- , ('anonimo'		,'usuarios'				,'form_insertar_externo')
-- , ('anonimo'		,'usuarios'				,'form_insertar_externo_validar')
-- ;
-- 

-- truncate table daw2_menu;
-- insert into daw2_menu
--   (id, es_submenu_de_id	, nivel	, orden	, texto, accion_controlador, accion_metodo, title) values
--   (1 , null				, 1		, null	, 'Inicio', 'inicio', 'index', null)
-- , (2 , null				, 1		, null	, 'Internacional', 'inicio', 'internacional', null)
-- , (3 , null				, 1		, null	, 'Libros', 'libros', 'index', null)
-- , (4 , null				, 1		, null	, 'Revista', 'revista', 'index', null)
-- , (5 , null				, 1		, null	, 'Usuarios', 'usuarios', 'index', null)
-- , (6 , null				, 1		, null	, 'Categorías', 'categorias', 'index', null)
-- , (7 , null				, 1		, null	, 'Artículos', 'articulos', null, null)
-- , (8 , 7				, 2		, null	, 'listado', 'articulos', 'index', null)
-- , (9 , 7				, 2		, null	, 'recuento por categoría', 'articulos', 'recuento_por_categoria', null)
-- ;

