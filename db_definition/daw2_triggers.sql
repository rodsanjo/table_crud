/*
 * @file: dables_and_user.sql
 * @author: jergo23@gmail.com
 * @since: 2014 enero
*/

-- use rodsanjo;

drop trigger if exists daw2_t_add_7anhos_f_salida_de_elementos_ai;

/*Función para poner la fecha de salida por defecto 7 años desde la fecha en la que se insertan los datos
delimiter // /*No funciona*/
create trigger daw2_t_add_7anhos_f_salida_de_elementos_ai
after insert on daw2_elementos for each row
begin
	declare _fecha_salida date;
	select fecha_salida into _fecha_salida
	from daw2_elementos
	where id = (SELECT LAST_INSERT_ID());
	if( isnull(_fecha_salida)) then 
		update daw2_elementos
		set fecha_salida = now()
		where id = (SELECT LAST_INSERT_ID()) ;
	end if;
end;
//
delimiter ;
