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

/* cambios 23/04/2021 */

CREATE TABLE IF NOT EXISTS confirmacion_solicitud (
	id int(11) auto_increment not null,
	id_solicitud int(11) not null,
	id_medio_envio int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	fecha_vencimiento TIMESTAMP,
	estado_confirmacion bit(1) default 0,
	del bit(1) default 0,
	CONSTRAINT pk_confirmacion_solicitud PRIMARY KEY(id)
)ENGINE = InnoDb;

ALTER TABLE `confirmacion_solicitud` ADD `codigo` INT(11) NOT NULL AFTER `id_medio_envio`;

CREATE TABLE IF NOT EXISTS envios_confirmaciones (
	id int(11) auto_increment not null,
	medio varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_envios_confirmaciones PRIMARY KEY(id)
)ENGINE = InnoDb;

/* cambios 27/04/2021 */

CREATE TABLE IF NOT EXISTS resultados_cifin (
	id int(11) auto_increment not null,
	estado varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_estados_cifin PRIMARY KEY(id)
)ENGINE = InnoDb;


CREATE TABLE IF NOT EXISTS resultados_solicitud_cifin (
	id int(11) auto_increment not null,
	id_solicitud int(11) not null,
	id_estado_cifin int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_resultados_solicitud_cifin PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS capacidades_telefonos (
	id int(11) auto_increment not null,
	capacidad varchar(100) not null, 
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_capacidades_telefonos PRIMARY KEY(id)
)ENGINE = InnoDb;


CREATE TABLE IF NOT EXISTS proveedores (
	id int(11) auto_increment not null,
	proveedor_nombre varchar(100) not null, 
	provedor_nit varchar(100) not null,
	proveedor_direccion varchar(100) not null,
	proveedor_ciudad_id int(11) not null,
	proveedor_contacto varchar(100) not null,
	proveedor_email varchar(100) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_proveedores PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS productos_stock (
	id int(11) auto_increment not null,
	id_proveedor int(11) not null, 
	id_producto int(11) not null,
	imei_1 int(20) not null,
	imei_2 int(20) null,
 	producto_color varchar(100) not null,
 	id_estado_producto int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_productos_stock PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS estados_productos (
	id int(11) auto_increment not null,
	estado_desc varchar(100) not null, 
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_productos_stok PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS colores_productos (
	id int(11) auto_increment not null,
	color_desc varchar(100) not null, 
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_colores_productos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS solicitudes_dispositivos (
	id int(11) auto_increment not null,
	id_solicitud int(11) not null,
	id_existencia int(11) not null, 
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_solicitudes_dispositivos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS perfiles_usuarios (
	id int(11) auto_increment not null,
	id_usuario int(11) not null,
	id_permiso int(11) not null, 
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_perfiles_usuarios PRIMARY KEY(id)
)ENGINE = InnoDb;


CREATE TABLE IF NOT EXISTS permisos (
	id int(11) auto_increment not null,
	permiso varchar(100) not null,
	mostrar varchar(100) not null, 
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_permisos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS firmas_solicitudes (
	id int(11) auto_increment not null,
	id_solicitud int(11) not null,
	codigo varchar(100) not null, 
	fecha_creada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	fecha_utilizada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	enviado_codigo bit(1) default 0,
	terminado bit(1) default 0,
	CONSTRAINT pk_firmas_solicitudes PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS modelos (
	id int(11) auto_increment not null,
	id_marca int(11) not null,
	nombre_modelo int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_modelos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS form_tratamiento_datos (
	id int(11) auto_increment not null,
	cedula int(11) not null,
	fecha_exp date not null,
	nombre_apellidos varchar(200) not null,
	direccion_ciudad varchar(100) not null,
	telefono_contacto varchar(100) not null,
	trabajo_ciudad varchar(100) not null,
	telefono_trabajo varchar(100) null,
	cargo varchar(100) not null,
	salario double not null,
	antiguedad int(11) not null,
	referencia1 varchar(100) not null,
	referencia2 varchar(100) not null,
	referencia3 varchar(100) not null,
	id_modelo_compra int(11) not null,
	cuota_inicial double not null,
	cuotas_numero int(11) not null,
	valor_cuota int(11) not null,
	valor_total int(11) not null,
	codigo int(11) not null,
	clave int(11) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_form_tratamiento_datos PRIMARY KEY(id)
)ENGINE = InnoDb;

CREATE TABLE IF NOT EXISTS puntos_gane (
	id int(11) auto_increment not null,
	id_usuario int(11) not null,
	nombre_punto varchar(255) not null,
	direccion_punto varchar(255) not null,
	contacto_punto varchar(255) not null,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	del bit(1) default 0,
	CONSTRAINT pk_puntos_gane PRIMARY KEY(id)
)ENGINE = InnoDb;


CREATE TABLE `prospectos` (
  `id` int(11) auto_increment NOT NULL,
  `prospecto_cedula` varchar(100) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;


CREATE TABLE `prospecto_detalles` (
  `id` int(11) auto_increment NOT NULL,
  `id_prospecto` int(11) NOT NULL,
  `prospecto_nombre` varchar(100) NOT NULL,
  `prospecto_apellidos` varchar(100) NOT NULL,
  `prospecto_numero_contacto` varchar(100) NOT NULL,
  `prospecto_email` varchar(100) NOT NULL,
  `prospecto_sexo` varchar(1) NOT NULL,
  `prospecto_dob` date DEFAULT NULL,
  `prospecto_direccion` varchar(100) NOT NULL,
  `ciudad_id` int(11) NOT NULL,
  `fecha_exp` date NOT NULL,
  `id_ciudad_exp` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_prospecto_detalles PRIMARY KEY(id)
) ENGINE=InnoDB;


CREATE TABLE `imagenes_prospectos` (
  `id` int(11) auto_increment NOT NULL,
  `id_prospecto` int(11) NOT NULL,
  `imagen_nombre_archivo` varchar(100) NOT NULL,
  `tipo_img` varchar(11) NOT NULL,
  `imagen_extension` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_imagenes_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;


CREATE TABLE `modelos_prospectos` (
  `id` int(11) auto_increment NOT NULL,
  `id_prospecto` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_modelos_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;


CREATE TABLE `plataformas_credito` (
  `id` int(11) auto_increment NOT NULL,
  `nombre_plataforma` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_plataformas_credito PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `loguin_control` (
  `id` int(11) auto_increment NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_logueo` timestamp NOT NULL DEFAULT current_timestamp(),
  `logueado` bit(1) DEFAULT b'0',
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_loguin_control PRIMARY KEY(id)
) ENGINE=InnoDB;


CREATE TABLE `confirmacion_prospectos` (
  `id` int(11) auto_increment NOT NULL,
  `id_prospecto` int(11) NOT NULL,
  `numero_contacto_confirmacion` varchar(255) NOT NULL,
  `codigo` varchar(255) not null,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_confirmacion` bit(1) DEFAULT b'0',
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_confirmacion_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `solicitudes_creditos_numeros` (
  `id` int(11) auto_increment NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `credito_numero` int(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_solicitudes_creditos_numeros PRIMARY KEY(id)
) ENGINE=InnoDB;


CREATE TABLE `contratos_solicitudes_altiria` (
  `id` int(11) auto_increment NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_documento_altiria` varchar(255) NOT NULL,
  `hora_enviado` timestamp NOT NULL DEFAULT current_timestamp(),
  `firmado` bit(1) DEFAULT b'0',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_contratos_solicitudes_altiria PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `resumen_financiamiento` (
  `id` int(11) auto_increment NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `idNumber` int(11) NOT NULL,
  `id_existencia` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_contratos_solicitudes_altiria PRIMARY KEY(id)
) ENGINE=InnoDB;


CREATE TABLE `solicitudes_domiciliarios` (
  `id` int(11) auto_increment NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_domiciliario` int(11) NOT NULL,
  `solicitud_fecha_entrega` DATE NULL,
  `solicitud_inicio_tiempo` TIME NULL,
  `solicitud_final_tiempo` TIME NULL,
  `entrega_confirmada` DEFAULT B'0',
  `hora_entrega_confirmada` TIMESTAMP NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_solicitudes_domiciliario PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `usuarios_puntos_gane` (
  `id` int(11) auto_increment NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_punto_gane` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_usuarios_puntos_gane PRIMARY KEY(id)
) ENGINE=InnoDB;

/*INSERTS*/

INSERT INTO `terminos_prestamos` (`id`, `numero_meses`, `fecha_registro`, `del`) VALUES (NULL, '2', current_timestamp(), b'0'), (NULL, '4', current_timestamp(), b'0'), (NULL, '8', current_timestamp(), b'0'), (NULL, '12', current_timestamp(), b'0');

INSERT INTO `terminos_productos` (`id`, `id_termino`, `id_producto`, `fecha_registro`, `del`) VALUES (NULL, '1', '1', current_timestamp(), b'0'), (NULL, '2', '1', current_timestamp(), b'0'), (NULL, '3', '1', current_timestamp(), b'0'), (NULL, '4', '1', current_timestamp(), b'0'), (NULL, '1', '2', current_timestamp(), b'0'), (NULL, '2', '2', current_timestamp(), b'0'), (NULL, '3', '2', current_timestamp(), b'0'), (NULL, '4', '2', current_timestamp(), b'0'), (NULL, '1', '3', current_timestamp(), b'0'), (NULL, '2', '3', current_timestamp(), b'0'), (NULL, '3', '3', current_timestamp(), b'0'), (NULL, '3', '4', current_timestamp(), b'0'), (NULL, '1', '4', current_timestamp(), b'0'), (NULL, '2', '4', current_timestamp(), b'0'), (NULL, '3', '4', current_timestamp(), b'0'), (NULL, '4', '4', current_timestamp(), b'0'), (NULL, '1', '5', current_timestamp(), b'0'), (NULL, '2', '5', current_timestamp(), b'0'), (NULL, '3', '5', current_timestamp(), b'0'), (NULL, '4', '5', current_timestamp(), b'0'), (NULL, '1', '6', current_timestamp(), b'0'), (NULL, '2', '6', current_timestamp(), b'0'), (NULL, '3', '6', current_timestamp(), b'0'), (NULL, '4', '6', current_timestamp(), b'0');

/* inserts 2/08/2021 */

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES ('12', 'ver_prospectos', 'Ver Prospectos', '0', current_timestamp(), b'0');

/* 5/08/2021 */

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'todos_prospectos', 'Mostrar Todos los Prospectos', '12', current_timestamp(), b'0');


DELETE FROM form_tratamiento_datos;
ALTER TABLE form_tratamiento_datos AUTO_INCREMENT = 1;

DELETE FROM marcas;
ALTER TABLE marcas AUTO_INCREMENT = 1;

DELETE FROM modelos;
ALTER TABLE modelos AUTO_INCREMENT = 1;

DELETE FROM perfiles_usuarios;
ALTER TABLE perfiles_usuarios AUTO_INCREMENT = 1;

DELETE FROM puntos_gane;
ALTER TABLE puntos_gane AUTO_INCREMENT = 1;

DELETE FROM usuarios;
ALTER TABLE usuarios AUTO_INCREMENT = 1;