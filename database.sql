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

create table unidades_aprendizaje(
    id_unidad_aprendizaje int primary key not null auto_increment,
    nombre varchar(100),
    id_semestre int,
    foreign key(id_semestre) references semestres(id_semestre) on delete cascade,
    id_programa_academico int, 
    foreign key(id_programa_academico) references programas_academicos(id_programa_academico) on delete cascade
);

create table usuarios(
    id_usuario int primary key not null auto_increment,
    correo varchar(50) unique,
    contrasenia varchar(50),
    tipo_usuario varchar(20) default 'ALUMNO'
);

create table alumnos(
    id_alumno int primary key not null auto_increment,
    nombre varchar(30),
    apellido_materno varchar(30),
    apellido_paterno varchar(30),
    id_usuario int,
    foreign key(id_usuario) references usuarios(id_usuario) on delete cascade,
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
    foreign key(id_unidad_aprendizaje) references unidades_aprendizaje(id_unidad_aprendizaje) on delete cascade,
    puntaje int not null
);

create table comentarios(
    id_comentario int primary key not null auto_increment,
    comentario varchar(255) not null,
    id_alumno int, 
    foreign key(id_alumno) references alumnos(id_alumno) on delete cascade
);

insert into programas_academicos(nombre) values('ING. EN SIS. COMPUTACIONALES');


insert into semestres(nombre) values('1er Semestre'), ('2do Semestre'), ('3er Semestre'), ('4to Semestre');

insert into unidades_aprendizaje
(nombre,id_semestre,id_programa_academico) values('ANALISIS DE ALGORITMOS', 1,1);

insert into unidades_aprendizaje
(nombre,id_semestre,id_programa_academico) values('APLICACIONES PARA COMUNICACIONES DE RED', 1,1);

insert into unidades_aprendizaje
(nombre,id_semestre,id_programa_academico) values('HIGH TECHNOLOGY ENTERPRISE MANAGEMENT', 1,1);

insert into unidades_aprendizaje
(nombre,id_semestre,id_programa_academico) values('ANALISIS VECTORIAL', 1,1);




insert into usuarios(correo, contrasenia,tipo_usuario) values('alumno','123', 'ALUMNO');
insert into usuarios(correo, contrasenia,tipo_usuario) values('admin','123', 'ADMIN');

insert into alumnos(nombre,apellido_materno,apellido_paterno, id_semestre,id_programa_academico, id_usuario) 
values('JOSE ALFONSO', 'GUTIERREZ', 'MARTINEZ', 1,1,1);

insert into preguntas(pregunta) values('¿Cómo consideras que ha sido la impartición de los temas en tu clase?');
insert into preguntas(pregunta) values('¿Pregunta 2?');
insert into preguntas(pregunta) values('¿Pregunta 3?');
insert into preguntas(pregunta) values('¿Pregunta 4?');
insert into preguntas(pregunta) values('¿Pregunta 5?');