
/*
 * @file: views.sql
 * @author: jequeto@gmail.com
 * @since: 2014 enero
*/

/*
Vista que recuperará todos los permisos de los que disfruta un usuario,
recopilando los asignados directamente en la tabla usuarios_permisos,
y los asignados indirectamente en la tabla usuarios_roles.
*/
create or replace view daw2_v_usuarios_permisos_roles
as
-- de usuarios_permisos
select
		 up.login
		,up.controlador
		,up.metodo
		,null as rol -- rol donante del permiso
from daw2_usuarios_permisos up
union distinct
-- de usuarios_roles
select
		 ur.login
		,rp.controlador
		,rp.metodo
		,ur.rol -- rol donante del permiso
from daw2_usuarios_roles ur inner join daw2_roles_permisos rp on ur.rol=rp.rol
order by login, controlador, metodo, rol
;

/*
Vista que devolverá una relación única de los permisos que tiene asignados
un usuario, sumados los directos más los indirectos (a través de los roles que 
tiene asignados).
*/
create or replace view daw2_v_usuarios_permisos
as
select distinct
		login
		,controlador
		,metodo
from daw2_v_usuarios_permisos_roles
order by login, controlador, metodo
;




create or replace view daw2_v_menu_submenu
(orden_nivel_1, orden_nivel_2, texto_menu, texto_submenu, accion_controlador, accion_metodo, title)
as
-- Items de nivel 1
select
	nivel as orden_nivel_1, null, texto as texto_menu, null, accion_controlador, accion_metodo, title
from daw2_menu
where nivel = 1
union
-- Items de nivel 2 o submenus
select
	m.nivel as orden_nivel_1, sm.orden as orden_nivel_2, m.texto as texto_menu, sm.texto as texto_submenu, sm.accion_controlador, sm.accion_metodo, sm.title
from daw2_menu as sm inner join daw2_menu as m on sm.es_submenu_de_id=m.id
where sm.nivel = 2
order by orden_nivel_1, orden_nivel_2, texto_menu, texto_submenu
;

