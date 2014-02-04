drop table if exists daw2_tipos;
create table daw2_tipos
(id integer unsigned primary key
,tipo varchar(30) not null unique
)
engine=myisam
;

drop table if exists daw2_elementos;
create table if not exists daw2_elementos
( id integer auto_increment
, nombre varchar(100) not null
, simbolo_quimico varchar(3) not null unique
, numero_atomico integer
, masa_atomica decimal(10,3) default null
, tipo_id integer unsigned
, fecha_entrada timestamp default now()
, fecha_salida date /*Solo admite un current_timestamp() por tabla - Error Code: 1293. Incorrect table definition; there can be only one TIMESTAMP column with CURRENT_TIMESTAMP in DEFAULT or ON UPDATE clause   */
, primary key(id)
, unique(nombre)
, unique(simbolo_quimico)
, foreign key(tipo_id) references daw2_tipos(id)
)
engine = myisam default charset=utf8
;

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