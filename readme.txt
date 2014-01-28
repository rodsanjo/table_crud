Proyecto esmvcphp

esmvcphp es un proyecto que pretende contruir un framework para desarrollar
aplicaciones en php 5.3 o superior, usando el modelo MVC, y programación
orientada a objetos (POO).

Esta escrito en castellano y en inglés, aunque un programador que quiera 
participar en grandes proyectos, debe pensar que la mayor parte de la 
programación se escribe en inglés.


En esta carpeta encontramos:

./index.php Fichero con el arranque de la aplicación PHP escrita en POO y que
	seguirá una arquitectura MVC.

./app/ Carpeta que contiene el código de la aplicación escrito en php-POO.

./db_definition/ Carpeta con documentos conteniendo el análisis de base de datos
	y los scripts sql para crear la bases de datos, sus elementos y los datos
	iniciales o de prueba

./recursos/ Carpeta que debería contener todos los recursos que el navegador del
	cliente pudiera solicitar mediante comandos HTTP-GET: (css, fotos, videos,
	javascript).
	No abrá ningun documento ni script en html, pues todo el documento html será
	generado por la aplicación escrita en php desde index.php.

./docs/ Carpeta con documentación relativa al análisis del proyecto.


Hay muchos frameworks libres escritos en PHP-POO siguiendo el patrón MVC. Cada
uno de ellos implementa el modelo MVC de una manera distinta y también organiza
las carpetas en disco de una manera particular.

El objetivo es comprender los principios de la organización MVC para después
poder ver como se implementan en cada framework.

Profesor Jesús María de Quevedo Tomé (jequeto@gmail.com)
IES PALOMERAS VALLECAS
MADRID (ESPAÑA).
@last_update octubre 2013


