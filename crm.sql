/* TODOS LOS SCRIPTS (CREATE, ALTER, DELETE) DE LAS TABLAS GENERADOS A PARTIR DEL 11/10 */

ALTER TABLE `modelos` ADD `costo` DOUBLE NOT NULL AFTER `nombre_modelo`, ADD `precio_venta` DOUBLE NOT NULL AFTER `costo`;
ALTER TABLE `modelos` ADD `id_capacidad` INT(11) NOT NULL AFTER `precio_venta`;
ALTER TABLE `prospectos` ADD `id_usuario_validador` INT(11) NOT NULL AFTER `id_plataforma`;
ALTER TABLE `prospectos` CHANGE `id_usuario_validador` `id_usuario_validador` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `prospectos` ADD `id_estado_prospecto` INT(11) NOT NULL DEFAULT '0' AFTER `id_usuario_validador`;

CREATE TABLE `estados_prospectos` (
  `id` int(11) auto_increment NOT NULL,
  `estado_prospecto` VARCHAR(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_estados_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

INSERT INTO `estados_prospectos` (`id`, `estado_prospecto`, `fecha_registro`, `del`) VALUES (NULL, 'APROBADO', current_timestamp(), b'0');
INSERT INTO `estados_prospectos` (`id`, `estado_prospecto`, `fecha_registro`, `del`) VALUES (NULL, 'RECHAZADO', current_timestamp(), b'0'), (NULL, 'PDTE. POR ENTREGAR', current_timestamp(), b'0');

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_prospectos_cola', 'Ver prospectos en cola', '13', CURRENT_TIMESTAMP, b'0'), (NULL, 'ver_validaciones_pdtes', 'Ver validaciones Pdtes', '13', 
CURRENT_TIMESTAMP, b'0'), (NULL, 'ver_entregas_pdtes', 'Ver entregas pdtes', '0', CURRENT_TIMESTAMP, b'0');

CREATE TABLE `arandelas_creditos` (
  `id` int(11) auto_increment NOT NULL,
  `estudio_credito` double DEFAULT 0,
  `fianza` DOUBLE DEFAULT 0,
  `interaccion_tecnologica` double DEFAULT 0,
  `beriblock` double DEFAULT 0,
  `seguro_pantalla` double DEFAULT 0,
  `domicilio` double DEFAULT 0,
  `iva_arandelas` double DEFAULT 0,
  `tasa_interes_usura` DOUBLE DEFAULT 0, 
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_arandelas_creditos PRIMARY KEY(id)
) ENGINE=InnoDB;

ALTER TABLE `departamentos` ADD `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `departamento`, ADD `del` BIT(1) NOT NULL DEFAULT b'0' AFTER `fecha_registro`;


ALTER TABLE `ciudades` ADD `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `id_departamento`, ADD `del` BIT(1) NOT NULL DEFAULT b'0' AFTER `fecha_registro`;

/* hasta este punto se actualizo en las bd del servidor */

ALTER TABLE `prospecto_detalles` ADD `id_referencia` INT(11) NOT NULL DEFAULT '0' AFTER `id_ciudad_exp`, ADD `inicial_referencia` DOUBLE NOT NULL AFTER `id_referencia`, ADD `observacion_prospecto` MEDIUMTEXT NULL DEFAULT NULL AFTER `inicial_referencia`;

ALTER TABLE `prospecto_detalles` CHANGE `prospecto_sexo` `prospecto_sexo` VARCHAR(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;

UPDATE `permisos` SET `extend` = '13' WHERE `permisos`.`id` = 26;

UPDATE `estados_prospectos` SET `estado_prospecto` = 'PDTE. COMPROBANTE' WHERE `estados_prospectos`.`id` = 4;

INSERT INTO `estados_prospectos` (`id`, `estado_prospecto`, `fecha_registro`, `del`) VALUES (NULL, 'ENTREGADO', current_timestamp(), b'0');

ALTER TABLE `prospectos` ADD `imei_referencia` INT(100) NOT NULL DEFAULT '0' AFTER `id_estado_prospecto`;
ALTER TABLE `prospectos` ADD `id_medio_envio` INT(11) NOT NULL DEFAULT '0' AFTER `imei_referencia`;
ALTER TABLE `prospectos` ADD `confirmar_rechazado` BIT(1) NOT NULL DEFAULT B'0' AFTER `id_medio_envio`;
ALTER TABLE `prospectos` ADD `confirmar_aprobado` BIT(1) NOT NULL DEFAULT b'0' AFTER `confirmar_rechazado`;



ALTER TABLE `confirmacion_prospectos` ADD `id_usuario` BIT(1) NOT NULL DEFAULT b'0' AFTER `estado_confirmacion`;
ALTER TABLE `confirmacion_prospectos` ADD `proceso` INT(11) NOT NULL DEFAULT '0' AFTER `estado_confirmacion`;


UPDATE `permisos` SET `mostrar` = 'Ver Puntos Gane (reporte)' WHERE `permisos`.`id` = 16;
INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_puntos_gane', 'Ver puntos gane', '0', current_timestamp(), b'0');
UPDATE `permisos` SET `extend` = '4' WHERE `permisos`.`id` = 30;

/*aqui se cargan los archivos planos de puntos gane */
ALTER TABLE `usuarios` CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `confirmacion_prospectos` CHANGE `id_usuario` `id_usuario` INT(11) NOT NULL;



CREATE TABLE `configuraciones` (
  `id` int(11) auto_increment NOT NULL,
  `envios_sms_gane` int(11) DEFAULT 0,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_configuraciones PRIMARY KEY(id)
) ENGINE=InnoDB;

INSERT INTO `configuraciones` (`id`, `envios_sms_gane`, `fecha_registro`, `del`) VALUES (NULL, '0', current_timestamp(), b'0');

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_configuraciones', 'Ver configuraciones', '4', current_timestamp(), b'0');



INSERT INTO `estados_prospectos` (`id`, `estado_prospecto`, `fecha_registro`, `del`) VALUES (NULL, 'VENTA NO REALIZADA', current_timestamp(), b'0');

CREATE TABLE `rutas_gane` (
  `id` int(11) auto_increment NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_punto_gane` int(11) NOT NULL,
  `fecha_visita` DATE,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_rutas_gane PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `comentarios_rutas_gane` (
  `id` int(11) auto_increment NOT NULL,
  `id_ruta_gane` int(11) NOT NULL,
  `comentario_texto` MEDIUMTEXT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_comentarios_rutas_gane PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `evidencias_rutas_gane` (
  `id` int(11) auto_increment NOT NULL,
  `id_ruta_gane` int(11) NOT NULL,
  `img_nombre_archivo` VARCHAR(255) NOT NULL,
  `img_nombre_personalizado` VARCHAR(255) NOT NULL,
  `img_ext` VARCHAR(12) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_evidencias_rutas_gane PRIMARY KEY(id)
) ENGINE=InnoDB;

ALTER TABLE `puntos_gane` ADD `confirmado_capacitacion` BIT(1) NOT NULL DEFAULT b'0' AFTER `BARRIO`;

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'crear_rutas_gane', 'Crear rutas gane', '0', current_timestamp(), b'0');



ALTER TABLE `comentarios_rutas_gane` ADD `realizado_por` INT(11) NOT NULL AFTER `comentario_texto`;
ALTER TABLE `evidencias_rutas_gane` ADD `cargado_por` INT(11) NOT NULL AFTER `img_ext`;


INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_rutas_gane', 'Ver Rutas Gane', '0', current_timestamp(), b'0'), (NULL, 'mostrar_todas_rutas_gane', 'Mostrar Todas las rutas', '0', current_timestamp(), b'0');



INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'cotizaciones', 'Ver cotizaciones', '0', current_timestamp(), b'0');

CREATE TABLE `convenios` (
  `id` int(11) auto_increment NOT NULL,
  `nombre_empresa` VARCHAR(255) NOT NULL,
  `nit_empresa` VARCHAR(100) NOT NULL,
  `direccion_empresa` VARCHAR(100) NOT NULL,
  `id_ciudad` INT(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_convenios PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `cotizaciones` (
  `id` int(11) auto_increment NOT NULL,
  `id_convenio` int(11) NOT NULL,
  `id_prospecto` int(11) NOT NULL,
  `id_vendador` int(11) NOT NULL,
  `id_dispositivo` int(11) NOT NULL,
  `texto_adicional` MEDIUMTEXT NULL,
  `descuento` double DEFAULT 0,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_cotizaciones PRIMARY KEY(id)
) ENGINE=InnoDB;


INSERT INTO `convenios` (`id`, `nombre_empresa`, `nit_empresa`, `direccion_empresa`, `id_ciudad`, `fecha_registro`, `del`) VALUES (NULL, 'COOPERATIVA DE AHORRO Y CREDITO INVERCOOB', '890303400-3', 'CL 34 # 1-51', '1', current_timestamp(), b'0');

ALTER TABLE `cotizaciones` ADD `valido_hasta` DATE NOT NULL AFTER `descuento`;



ALTER TABLE `puntos_gane` CHANGE `confirmado_capacitacion` `confirmado_capacitacion` INT(1) NOT NULL DEFAULT '0';



ALTER TABLE `prospectos` ADD `resultado_dc_prospecto` INT(11) NOT NULL AFTER `confirmar_aprobado`;

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_todas_entregas_pdtes', 'Ver todas las entregas pdtes.', '0', current_timestamp(), b'0');

UPDATE `permisos` SET `extend` = '13' WHERE `permisos`.`id` = 36;

CREATE TABLE `entregas_servientrega` (
  `id` int(11) auto_increment NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `numero_guia` VARCHAR(255) NOT NULL,
  `id_estado_solicitud` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_entregas_servientrega PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `entregas_tienda` (
  `id` int(11) auto_increment NOT NULL,
  `id_solicitud` INT(11) NOT NULL,
  `id_usuario_responsable` INT(11) NOT NULL,
  `fecha_entrega` DATE NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `del` bit(1) DEFAULT b'0',
  CONSTRAINT pk_entregas_tienda PRIMARY KEY(id)
) ENGINE=InnoDB;





INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_reporte_domicilios', 'Asignar Domicilios', '0', current_timestamp(), b'0'), (NULL, 'ventas_del_dia', 'Ver ventas del dia', '0', current_timestamp(), b'0'), (NULL, 'prospectos_del_dia', 'Ver reporte prospectos', '0', current_timestamp(), b'0');

UPDATE `permisos` SET `extend` = '15' WHERE `permisos`.`id` = 37;
UPDATE `permisos` SET `extend` = '15' WHERE `permisos`.`id` = 38;
UPDATE `permisos` SET `extend` = '15' WHERE `permisos`.`id` = 39;



UPDATE `permisos` SET `mostrar` = 'Ver Reporte Prospectos' WHERE `permisos`.`id` = 39;

UPDATE `permisos` SET `mostrar` = 'Ver Equipos Entregados' WHERE `permisos`.`id` = 38;



INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_servientrega', 'Ver Entregas Servientrega', '0', current_timestamp(), b'0');

UPDATE `permisos` SET `extend` = '15' WHERE `permisos`.`id` = 40;

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'modificar_crear_usuarios', 'Modificar y crear usuarios', '0', current_timestamp(), b'0');



ALTER TABLE `solicitudes` ADD `ultimo_cambio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `del`;
ALTER TABLE `prospectos` ADD `ultimo_cambio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `del`;
ALTER TABLE `prospecto_detalles` ADD `ultimo_cambio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `del`;
ALTER TABLE `entregas_servientrega` ADD `ultimo_cambio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `del`;
ALTER TABLE `solicitudes_domiciliarios` ADD `ultimo_cambio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `del`;
ALTER TABLE `entregas_tienda` ADD `ultimo_cambio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `del`;
INSERT INTO `cargos` (`id`, `descripcion_cargo`, `fecha_registro`, `del`) VALUES (NULL, 'ASESOR EXTERNO', current_timestamp(), b'0'), (NULL, 'ASESOR INTERNO', current_timestamp(), b'0'), (NULL, 'VALIDADOR', current_timestamp(), b'0'), (NULL, 'MENSAJERO', current_timestamp(), b'0'), (NULL, 'ASISTENTE GERENCIA', current_timestamp(), b'0');

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_perfiles', 'Ver Perfiles', '0', current_timestamp(), b'0');



INSERT INTO `estados_prospectos` (`id`, `estado_prospecto`, `fecha_registro`, `del`) VALUES (NULL, 'PDTE. POR LLAMAR', current_timestamp(), b'0');

ALTER TABLE `entregas_servientrega` ADD `sobreflete_servientrega` DOUBLE NOT NULL AFTER `id_estado_solicitud`, ADD `destino_servientrega` INT(11) NOT NULL AFTER `sobreflete_servientrega`, ADD `bolsa_servientrega` BIT(1) NOT NULL AFTER `destino_servientrega`;

INSERT INTO `plataformas_credito` (`id`, `nombre_plataforma`, `fecha_registro`, `del`) VALUES (NULL, 'CREDITEK', current_timestamp(), b'0');



ALTER TABLE `usuarios` ADD `usuario_tropa` BIT(1) NOT NULL DEFAULT b'0' AFTER `domiciliario`;

ALTER TABLE `prospectos` ADD `id_prospecto_creacion` INT(11) NOT NULL DEFAULT '0' AFTER `resultado_dc_prospecto`;

CREATE TABLE `creaciones_prospectos` (
    `id` int(11) auto_increment NOT NULL,
    `crecion_from` varchar(100) NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_creaciones_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

INSERT INTO `creaciones_prospectos` (`id`, `crecion_from`, `fecha_registro`, `del`) VALUES (NULL, 'prospecto_gane', current_timestamp(), b'0'), (NULL, 'prospecto_tropa', current_timestamp(), b'0'), (NULL, 'prospecto_externo', current_timestamp(), b'0');

UPDATE `cargos` SET `descripcion_cargo` = 'VALIDADOR@' WHERE `cargos`.`id` = 8;

ALTER TABLE `prospecto_detalles` CHANGE `fecha_exp` `fecha_exp` DATE NULL;

ALTER TABLE `prospectos` CHANGE `id_confirmacion` `id_confirmacion` INT(11) NULL DEFAULT '0';

INSERT INTO `estados_prospectos` (`id`, `estado_prospecto`, `fecha_registro`, `del`) VALUES ('11', 'CREACIÓN', current_timestamp(), b'0');

CREATE TABLE `referencias_prospectos` (
    `id` int(11) auto_increment NOT NULL,
    `id_prospecto` INT(11) NOT NULL,
    `id_referencia` INT(11) NOT NULL,
    `inicial_confirmada` DOUBLE NOT NULL,
    `plazo_meses` INT(11) NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_referencias_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

ALTER TABLE `referencias_prospectos` ADD `ultimo_cambio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `fecha_registro`;

ALTER TABLE `imagenes_prospectos` CHANGE `id_confirmacion` `id_prospecto` INT(11) NOT NULL;

ALTER TABLE `imagenes_prospectos` DROP `tipo_img`;

INSERT INTO `estados_prospectos` (`id`, `estado_prospecto`, `fecha_registro`, `del`) VALUES (NULL, 'PDTE_VALIDAR', current_timestamp(), b'0');

ALTER TABLE `prospectos` ADD `info_completa` BIT(1) NOT NULL DEFAULT b'0' AFTER `id_prospecto_creacion`;

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'ver_validaciones', 'Ver Validaciones', '0', current_timestamp(), b'0');


CREATE TABLE `resultados_prospectos` (
    `id` int(11) auto_increment NOT NULL,
    `id_prospecto` INT(11) NOT NULL,
    `id_plataforma` INT(11) NOT NULL,
    `resultado_dc` INT(11) NOT NULL,
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_resultados_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

UPDATE `plataformas_credito` SET `nombre_plataforma` = 'ADELANTOS' WHERE `plataformas_credito`.`id` = 1;

UPDATE `plataformas_credito` SET `nombre_plataforma` = 'CREDITEK' WHERE `plataformas_credito`.`id` = 2;
UPDATE `plataformas_credito` SET `nombre_plataforma` = 'CREDIMINUYO' WHERE `plataformas_credito`.`id` = 3;
UPDATE `plataformas_credito` SET `nombre_plataforma` = 'CREDIMINUTO' WHERE `plataformas_credito`.`id` = 3;
UPDATE `plataformas_credito` SET `nombre_plataforma` = 'NOA 10' WHERE `plataformas_credito`.`id` = 4;

UPDATE `plataformas_credito` SET `del` = b'1' WHERE `plataformas_credito`.`id` = 4;

CREATE TABLE `estados_validaciones` (
    `id` int(11) auto_increment NOT NULL,
    `estado_validacion_nombre` INT(11) NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_estados_validaciones PRIMARY KEY(id)
) ENGINE=InnoDB;

ALTER TABLE `estados_validaciones` CHANGE `estado_validacion_nombre` `estado_validacion_nombre` VARCHAR(50) NOT NULL;

INSERT INTO `estados_validaciones` (`id`, `estado_validacion_nombre`, `fecha_registro`, `del`) VALUES (NULL, 'aprobado', current_timestamp(), b'0'), (NULL, 'rechazado', current_timestamp(), b'0'), (NULL, 'contracto_activo', current_timestamp(), b'0'), (NULL, 'otro', current_timestamp(), b'0');

CREATE TABLE `imagenes_resultados_prospectos` (
    `id` int(11) auto_increment NOT NULL,
    `id_resultado` INT(11) NOT NULL,
    `imagen_nombre_archivo` VARCHAR(50) NOT NULL,
    `imagen_extension` VARCHAR(10) NOT NULL,
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_imagenes_resultados_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `observaciones_resultados_prospectos` (
    `id` int(11) auto_increment NOT NULL,
    `id_resultado` INT(11) NOT NULL,
    `observacion_texto` MEDIUMTEXT NOT NULL,
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_observaciones_resultados_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `prospectos_pendiente_llamar` (
    `id` int(11) auto_increment NOT NULL,
    `id_prospecto` INT(11) NOT NULL,
    `fecha_hora_llamada` timestamp NOT NULL,
    `vencida` bit(1) DEFAULT b'0',
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_prospectos_pendiente_llamar PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `estados_pendiente_llamar` (
    `id` int(11) auto_increment NOT NULL,
    `estado_nombre` VARCHAR(11) NOT NULL,
    `estado_mostrar` VARCHAR(11) NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_estados_pendiente_llamar PRIMARY KEY(id)
) ENGINE=InnoDB;

ALTER TABLE `prospectos_pendiente_llamar` ADD `id_estado_recordatorio` INT(11) NOT NULL AFTER `id_prospecto`;

ALTER TABLE `estados_pendiente_llamar` CHANGE `estado_nombre` `estado_nombre` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

ALTER TABLE `estados_pendiente_llamar` CHANGE `estado_mostrar` `estado_mostrar` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

INSERT INTO `estados_pendiente_llamar` (`id`, `estado_nombre`, `estado_mostrar`, `fecha_registro`, `ultimo_cambio`, `del`) VALUES (NULL, 'cliente_no_contesta', 'cliente no contesta', current_timestamp(), current_timestamp(), b'0'), (NULL, 'no_cuenta_con_la_inicial', 'no cuenta con la inicial', current_timestamp(), current_timestamp(), b'0'), (NULL, 'pdte_confirmar_inicial', 'pdte confirmar inicial', current_timestamp(), current_timestamp(), b'0'), (NULL, 'no_puede_atender_llamada', 'no puede atender llamada', current_timestamp(), current_timestamp(), b'0'), (NULL, 'aprobado_no_contesta', 'aprobado no contesta', current_timestamp(), current_timestamp(), b'0');

CREATE TABLE `prospectos_pendiente_validar` (
    `id` int(11) auto_increment NOT NULL,
    `id_prospecto` INT(11) NOT NULL,
    `id_estado` INT(11) NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_prospectos_pendiente_validar PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `despachos_prospectos` (
    `id` int(11) auto_increment NOT NULL,
    `id_prospecto` INT(11) NOT NULL,
    `id_medio_envio` INT(11) NOT NULL,
    `imei_dispositivo` BIGINT NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_despachos_prospectos PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `despachos_prospectos_domicilios` (
    `id` int(11) auto_increment NOT NULL,
    `id_despacho` INT(11) NOT NULL,
    `id_domiciliario` INT(11) NOT NULL,
    `domicilio_entregado` bit(1) DEFAULT b'0',
    `fecha_hora_entrega` timestamp NULL DEFAULT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_despachos_prospectos_domicilios PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `despachos_prospectos_tienda` (
    `id` int(11) auto_increment NOT NULL,
    `id_despacho` INT(11) NOT NULL,
    `id_responsable_tienda` INT(11) NOT NULL,
    `entrega_realizada` bit(1) DEFAULT b'0',
    `fecha_hora_entrega` timestamp NULL DEFAULT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_despachos_prospectos_tienda PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE `despachos_prospectos_servientrega` (
    `id` int(11) auto_increment NOT NULL,
    `id_despacho` INT(11) NOT NULL,
    `guia_servientrega` BIGINT NOT NULL,
    `confirmacion_entrega` bit(1) DEFAULT b'0',
    `fecha_hora_entrega` timestamp NULL DEFAULT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_despachos_prospectos_servientrega PRIMARY KEY(id)
) ENGINE=InnoDB;

/* hasta este punto esta actualizado en el crm.noa10.com*/

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'Ver_Despachos', 'Ver Despachos', '43', current_timestamp(), b'0');

ALTER TABLE `despachos_prospectos` CHANGE `imei_dispositivo` `id_existencia` INT(11) NOT NULL;

ALTER TABLE `despachos_prospectos` ADD `id_plataforma` INT(11) NOT NULL AFTER `id_existencia`;

CREATE TABLE `detalles_prospectos_cancelados` (
    `id` int(11) auto_increment NOT NULL,
    `id_prospecto` INT(11) NOT NULL,
    `observacion_texto` MEDIUMTEXT NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_detalles_prospectos_cancelados PRIMARY KEY(id)
) ENGINE=InnoDB;

INSERT INTO `permisos` (`id`, `permiso`, `mostrar`, `extend`, `fecha_registro`, `del`) VALUES (NULL, 'crear_existencias', 'Crear Existencias', '1', current_timestamp(), b'0');

CREATE TABLE `plantillas_productos` (
    `id` int(11) auto_increment NOT NULL,
    `id_producto` INT(11) NOT NULL,
    `texto_plantilla` MEDIUMTEXT NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `ultimo_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
    `del` bit(1) DEFAULT b'0',
    CONSTRAINT pk_plantillas_productos PRIMARY KEY(id)
) ENGINE=InnoDB;

ALTER TABLE `referencias_prospectos` ADD `id_color` INT(11) NOT NULL DEFAULT '0' AFTER `plazo_meses`;

/* hasta este punto esta actualizado en el crm.noa10.com*/