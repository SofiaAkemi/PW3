create database db_mundo;
use db_mundo;

create table tb_continentes(
    id_continente int auto_increment primary key,
    nm_continente varchar(100) not null,
    n_populacao_continente bigint not null,
    n_area_continente float not null,
    n_paises_continente int not null
);

create table tb_governantes(
    id_governante int auto_increment primary key,
    nm_governante varchar(100) not null,
    nm_partido varchar(100),
    dt_nascimento date not null,
    n_idade int not null,
    dt_inicio_mandato date not null,
    dt_fim_mandato date not null
);

create table tb_paises(
    id_pais int auto_increment primary key,
    nm_pais varchar(100) not null,
    n_populacao_pais bigint not null,
    n_area_pais float not null,
    nm_idioma varchar(100) not null,
    clima varchar(100) not null,
    regime_politico varchar(100) not null,
    moeda varchar(50) not null,
	cd_continente int not null,
	cd_governante int not null,
    foreign key (cd_continente)
    references tb_continentes(id_continente),
    foreign key (cd_governante)
    references tb_governantes(id_governante)
);

create table tb_cidades(
    id_cidade int auto_increment primary key,
    nm_cidade varchar(100) not null,
    n_populacao_cidade bigint not null,
    n_area_cidade float not null,
    nm_clima varchar(100) not null,
    dt_fundacao date not null,
	cd_pais int not null,
	cd_governante int not null,
    foreign key (cd_pais)
    references tb_paises(id_pais)
    on delete cascade,
    foreign key (cd_governante)
    references tb_governantes(id_governante)
);