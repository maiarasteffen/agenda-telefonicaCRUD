create database lista_telefonica;

use lista_telefonica;

create table lista(
id int not null auto_increment primary key,
nome varchar (50),
telefone varchar (11),
) engine InnoDB;

select * from lista;