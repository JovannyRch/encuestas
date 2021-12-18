drop database if exists encuestas_db;
create database encuestas_db;
use encuestas_db;

create table programas_academicos(
    id_programa_academico int primary key not null auto_increment,
    nombre varchar(100)
);

create table semestres(
    id_semestre int primary key not null auto_increment,
    nombre varchar(100)
);

create unidades_aprendizaje(
    id_unidad_aprendizaje int primary key not null auto_increment,
    nombre varchar(100),
    id_semestre int,
    foreign key(id_semestre) references semestres(id_semestre) on delete cascade,
    id_programa_academico int, 
    foreign key(id_programa_academico) references programas_academicos(id_programa_academico) on delete cascade
);

create table alumnos(
    id_alumno int primary key not null auto_increment,
    nombre varchar(30),
    apellido_materno varchar(30),
    apellido_paterno varchar(30),
    id_semestre int,
    foreign key(id_semestre) references semestres(id_semestre) on delete cascade,
    id_programa_academico int, 
    foreign key(id_programa_academico) references programas_academicos(id_programa_academico) on delete cascade
);


create table preguntas(
    id_pregunta int primary key not null auto_increment,
    pregunta varchar(255)
);

create table respuestas(
    id_respuesta int primary key not null auto_increment,
    id_pregunta int,
    foreign key(id_pregunta) references preguntas(id_pregunta) on delete cascade,
    id_alumno int, 
    foreign key(id_alumno) references alumnos(id_alumno) on delete cascade,
    id_unidad_aprendizaje int, 
    foreign key(id_unidad_aprendizaje) references unidades_aprendizaje(id_unidad_aprendizaje) on delete cascade
    puntaje int not null
);


insert into programas_academicos(nombre) values('ING. EN SIS. COMPUTACIONALES');


insert into semestres(nombre) values('1er Semestre'),values('2do Semestre'), values('3er Semestre'), values('4to Semestre');

insert into 