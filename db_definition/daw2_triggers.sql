/*
 * @file: dables_and_user.sql
 * @author: jergo23@gmail.com
 * @since: 2014 enero
*/

-- use rodsanjo;

drop trigger if exists daw2_t_add_7anhos_f_devolucion_de_elementos_ai;

delimiter // /*No funciona*/
create trigger daw2_t_add_7anhos_f_devolucion_de_elementos_ai
after insert on daw2_elementos for each row
begin
	declare _fecha_devolucion date;
	select fecha_devolucion into _fecha_devolucion
	from daw2_elementos
	where id = (SELECT LAST_INSERT_ID());
	if( isnull(_fecha_devolucion)) then 
		update daw2_elementos
		set fecha_devolucion = now()
		where id = (SELECT LAST_INSERT_ID()) ;
	end if;
end;
//
delimiter ;
