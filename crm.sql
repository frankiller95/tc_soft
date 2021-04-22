CREATE TABLE IF NOT EXISTS usuarios (
	id int(255) auto_increment not null,
	nombre varchar(100) not null,
	apellidos varchar(100) not null,
	cedula varchar(100) not null,
	email varchar(255) not null,
	password varchar(255) not null,
	id_cargo int(11) not null,
	fecha_nacimiento date not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_usuarios PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE IF NOT EXISTS cargos (
	id int(11) auto_increment not null,
	descripcion_cargo varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_cargos PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE IF NOT EXISTS departamentos (
	id int(11) auto_increment not null,
	departamento varchar(100) not null
	CONSTRAINT pk_departamentos PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS ciudades (
	id int(11) auto_increment not null,
	ciudad varchar(100) not null,
	id_departamento int(11) not null
	CONSTRAINT pk_ciudades PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE IF NOT EXISTS inventario (
	id int(11) auto_increment not null,
	id_producto int(11) not null,
	costo double not null,
	precio_venta double not null,
	stock int(255) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_inventario PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS productos (
	id int(11) auto_increment not null,
	marca_producto varchar(100) not null,
	modelo_producto varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_productos PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS marcas (
	id int(11) auto_increment not null,
	marca_producto varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_marcas PRIMARY KEY(id)
)ENGINE=InnoDb;



CREATE TABLE IF NOT EXISTS clientes (
	id int(11) auto_increment not null,
	cliente_cedula varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_marcas PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS cliente_detalles (
	id int(11) auto_increment not null,
	id_cliente int(11) not null,
	cliente_nombre varchar(100) not null,
	cliente_apellidos varchar(100) not null,
	cliente_numero_contacto varchar(100) not null,
	cliente_dob date,
	ciudad_id int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_marcas PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE IF NOT EXISTS imagenes_clientes (
	id int(11) auto_increment not null,
	id_cliente int(11) not null,
	imagen_nombre_archivo varchar(100) not null,
	imagen_extension varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_imagenes_clientes PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS solicitudes (
	id int(11) auto_increment not null,
	id_cliente int(11) not null,
	id_producto int(11) not null,
	id_terminos_prestamo int(11) not null,
	id_frecuencia_pago int(11) not null,
	id_porcentaje_inicial int(11) not null,
	inicial double not null,
	fecha_inicio_credito TIMESTAMP,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_solicitudes PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE IF NOT EXISTS terminos_prestamos (
	id int(11) auto_increment not null,
	numero_meses int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_terminos_prestamos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS terminos_productos (
	id int(11) auto_increment not null,
	id_termino int(11) not null,
	id_producto int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_terminos_productos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS porcentajes_iniciales (
	id int(11) auto_increment not null,
	porcentaje int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_porcentajes_iniciales PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS porcentajesini_productos (
	id int(11) auto_increment not null,
	id_porcentaje int(11) not null,
	id_producto int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_porcentajesini_productos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS estados_solicitudes (
	id int(11) auto_increment not null,
	texto_estado varchar(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_estados_solicitudes PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS frecuencias_pagos (
	id int(11) auto_increment not null,
	frecuencia varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_frecuencias_pagos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS frecuencias_productos (
	id int(11) auto_increment not null,
	id_frecuencia_pago int(11) not null,
	id_producto int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_frecuencias_productos PRIMARY KEY(id)
)ENGINE = InnoDb;

/*INSERTS*/

INSERT INTO `terminos_prestamos` (`id`, `numero_meses`, `fecha_registro`, `del`) VALUES (NULL, '2', current_timestamp(), b'0'), (NULL, '4', current_timestamp(), b'0'), (NULL, '8', current_timestamp(), b'0'), (NULL, '12', current_timestamp(), b'0');

INSERT INTO `terminos_productos` (`id`, `id_termino`, `id_producto`, `fecha_registro`, `del`) VALUES (NULL, '1', '1', current_timestamp(), b'0'), (NULL, '2', '1', current_timestamp(), b'0'), (NULL, '3', '1', current_timestamp(), b'0'), (NULL, '4', '1', current_timestamp(), b'0'), (NULL, '1', '2', current_timestamp(), b'0'), (NULL, '2', '2', current_timestamp(), b'0'), (NULL, '3', '2', current_timestamp(), b'0'), (NULL, '4', '2', current_timestamp(), b'0'), (NULL, '1', '3', current_timestamp(), b'0'), (NULL, '2', '3', current_timestamp(), b'0'), (NULL, '3', '3', current_timestamp(), b'0'), (NULL, '3', '4', current_timestamp(), b'0'), (NULL, '1', '4', current_timestamp(), b'0'), (NULL, '2', '4', current_timestamp(), b'0'), (NULL, '3', '4', current_timestamp(), b'0'), (NULL, '4', '4', current_timestamp(), b'0'), (NULL, '1', '5', current_timestamp(), b'0'), (NULL, '2', '5', current_timestamp(), b'0'), (NULL, '3', '5', current_timestamp(), b'0'), (NULL, '4', '5', current_timestamp(), b'0'), (NULL, '1', '6', current_timestamp(), b'0'), (NULL, '2', '6', current_timestamp(), b'0'), (NULL, '3', '6', current_timestamp(), b'0'), (NULL, '4', '6', current_timestamp(), b'0');


