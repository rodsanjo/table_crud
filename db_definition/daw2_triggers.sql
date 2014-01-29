/*
 * @file: dables_and_user.sql
 * @author: jergo23@gmail.com
 * @since: 2014 enero
*/

-- use rodsanjo;

drop trigger if not exists daw2_add_7anhos_f_devolucion_de_elementos_ai

delimiter $$
create trigger daw2_add_7anhos_f_devolucion_de_elementos_ai after insert
on daw2_elemntos for each row begin
    update daw2_elementos
    set new.fecha_devolucion = timestampadd(year,7,fecha_devolucion)
    where old.id = new.id
end
$$
delimiter;
