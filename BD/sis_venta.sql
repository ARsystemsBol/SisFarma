-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2023 a las 07:28:48
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sis_venta`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (IN `n_cantidad` INT, IN `n_precio` DECIMAL(10,2), IN `codigo` INT)   BEGIN
DECLARE nueva_existencia int;
DECLARE nuevo_total decimal(10,2);
DECLARE nuevo_precio decimal(10,2);

DECLARE cant_actual int;
DECLARE pre_actual decimal(10,2);

DECLARE actual_existencia int;
DECLARE actual_precio decimal(10,2);

SELECT precio, existencia INTO actual_precio, actual_existencia FROM producto WHERE codproducto = codigo;

SET nueva_existencia = actual_existencia + n_cantidad;
SET nuevo_total = n_precio;
SET nuevo_precio = nuevo_total;

UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE codproducto = codigo;

SELECT nueva_existencia, nuevo_precio;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))   BEGIN
DECLARE precio_actual decimal(10,2);
SELECT precio INTO precio_actual FROM producto WHERE codproducto = codigo;
INSERT INTO detalle_temp(token_user, codproducto, cantidad, precio_venta) VALUES (token_user, codigo, cantidad, precio_actual);
SELECT tmp.correlativo, tmp.codproducto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto WHERE tmp.token_user = token_user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp_lote` (IN `token_usermed` VARCHAR(50), IN `codigomed` BIGINT, IN `cantidadmed` INT, IN `preciounitario` DECIMAL(10,2), IN `fecvenmed` DATE, IN `nombrecompleto` VARCHAR(100))   BEGIN
INSERT INTO detalle_temp_lote(token_user, codmedicamento, cantidad, precio_compra, fecvencimiento, nombrecompleto) VALUES (token_usermed, codigomed, cantidadmed, preciounitario, fecvenmed, nombrecompleto);
SELECT tmpl.correlativo, tmpl.codmedicamento, m.nombre AS codigogenerado, tmpl.cantidad, tmpl.precio_compra, tmpl.fecvencimiento, tmpl.nombrecompleto FROM detalle_temp_lote tmpl INNER JOIN medicamento m ON tmpl.codmedicamento = m.idmedicamento WHERE tmpl.token_user = token_usermed;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp_traspaso` (IN `token_user` VARCHAR(50), IN `codigomed` INT, IN `cantidadmed` INT, IN `preciocompra` DECIMAL(10,2), IN `precioventa` DECIMAL(10,2), IN `fecven` DATE, IN `descripcion` VARCHAR(100))   BEGIN
INSERT INTO detalle_temp_traspaso(token_user, idmedicamento, fechavencimiento, cantidad, preciocompra, precioventa, nombrecompleto) VALUES (token_user, codigomed, fecven, cantidadmed, preciocompra, precioventa, descripcion);
SELECT correlativo, idmedicamento, fechavencimiento, cantidad, preciocompra, precioventa, nombrecompleto FROM detalle_temp_traspaso WHERE token_user = token_user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp_venta` (IN `token_user` VARCHAR(50), IN `codmed` INT, IN `cantidadmed` INT, IN `precioventa` DECIMAL(10,2), IN `fecvenmed` DATE, IN `descripcion` VARCHAR(50))   BEGIN
INSERT INTO detalle_tempf(token_user, codmedicamento, fechavencimiento, cantidad, precio_venta, descripcion) VALUES (token_user, codmed, fecvenmed, cantidadmed, precioventa, descripcion);
SELECT correlativo, codmedicamento, cantidad, fechavencimiento, precio_venta, descripcion
FROM detalle_tempf
WHERE token_user = token_user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `data` ()   BEGIN
DECLARE lotes int;
DECLARE suc int;
DECLARE medicamentos int;
DECLARE usuarios int;
DECLARE clientes int;
DECLARE proveedores int;
DECLARE productos int;
DECLARE ventas int;
SELECT COUNT(*) INTO lotes FROM lote;
SELECT COUNT(*) INTO medicamentos FROM medicamento;
SELECT COUNT(*) INTO suc FROM sucursal;
SELECT COUNT(*) INTO usuarios FROM usuario;
SELECT COUNT(*) INTO clientes FROM cliente;
SELECT COUNT(*) INTO proveedores FROM proveedor;
SELECT COUNT(*) INTO productos FROM producto;
SELECT COUNT(*) INTO ventas FROM facturas;

SELECT lotes, medicamentos, suc, usuarios, clientes, proveedores, productos, ventas;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `dataAlertas` ()   BEGIN
DECLARE bajaex int;
SELECT COUNT(*) INTO bajaex FROM medicamento
WHERE stock < 50;
SELECT bajaex;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (`id_detalle` INT, `token` VARCHAR(50))   BEGIN
DELETE FROM detalle_temp WHERE correlativo = id_detalle;
SELECT tmp.correlativo, tmp.codproducto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp INNER JOIN producto p ON tmp.codproducto = p.codproducto WHERE tmp.token_user = token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp_lote` (IN `Id_detalle` INT, IN `token` VARCHAR(50))   BEGIN
DELETE FROM detalle_temp_lote WHERE correlativo = id_detalle;
SELECT tmpl.correlativo, tmpl.codmedicamento, tmpl.nombrecompleto, tmpl.cantidad, tmpl.fecvencimiento, tmpl.precio_compra, tmpl.token_user FROM detalle_temp_lote tmpl INNER JOIN medicamento m ON tmpl.codmedicamento = m.idmedicamento WHERE token_user = token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp_st` (IN `Id_detalle` INT, IN `token` VARCHAR(50))   BEGIN
DELETE FROM detalle_temp_traspaso WHERE correlativo = id_detalle;
SELECT correlativo, idmedicamento, fechavencimiento, cantidad, preciocompra, precioventa, nombrecompleto FROM detalle_temp_traspaso WHERE token_user = token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp_ventas` (IN `Id_detalle` INT, IN `token` VARCHAR(50))   BEGIN
DELETE FROM detalle_tempf WHERE correlativo = id_detalle;
SELECT correlativo, codmedicamento, fechavencimiento, cantidad, precio_venta, descripcion FROM detalle_tempf WHERE token_user = token;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_reglote` (IN `cod_usuario` INT, IN `cod_proveedor` INT, IN `token` VARCHAR(50), IN `descripcion_lote` VARCHAR(100), IN `estadolote` INT)   BEGIN
DECLARE lote INT;
DECLARE registros INT;
DECLARE total DECIMAL(10,2);
DECLARE nueva_existencia int;
DECLARE existencia_actual int;

DECLARE tmp_cod_medicamento int;
DECLARE tmp_cant_medicamento int;
DECLARE a int;
SET a = 1;

CREATE TEMPORARY TABLE tbl_tmp_tokenuser_lote(
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_med BIGINT,
    cant_med int);
SET registros = (SELECT COUNT(*) FROM detalle_temp_lote WHERE token_user = token);
IF registros > 0 THEN
INSERT INTO tbl_tmp_tokenuser_lote(cod_med, cant_med) SELECT codmedicamento, cantidad FROM detalle_temp_lote WHERE token_user = token;
INSERT INTO lote (usuario,codproveedor, descripcion, estado) VALUES (cod_usuario, cod_proveedor, descripcion_lote, estadolote);
SET lote = LAST_INSERT_ID();

INSERT INTO detallelote(nolote,codmedicamento,cantidad,precio_compra, fecvencimiento, nombrecompleto) SELECT (lote) AS nolote, codmedicamento, cantidad,precio_compra, fecvencimiento, nombrecompleto FROM detalle_temp_lote WHERE token_user = token;
WHILE a <= registros DO
SELECT cod_med, cant_med INTO tmp_cod_medicamento,tmp_cant_medicamento FROM tbl_tmp_tokenuser_lote WHERE id = a;
    SELECT stock INTO existencia_actual FROM medicamento WHERE idmedicamento = tmp_cod_medicamento;
    SET nueva_existencia = existencia_actual + tmp_cant_medicamento;
    UPDATE medicamento SET stock = nueva_existencia WHERE idmedicamento = tmp_cod_medicamento;
    SET a=a+1;
END WHILE;
SET total = (SELECT SUM(cantidad * precio_compra) FROM detalle_temp_lote WHERE token_user = token);
UPDATE lote SET totalcompra = total WHERE nolote = lote;
DELETE FROM detalle_temp_lote WHERE token_user = token;
TRUNCATE TABLE tbl_tmp_tokenuser_lote;
SELECT * FROM lote WHERE nolote = lote;
ELSE
SELECT 0;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_traspason1` (IN `cod_usuario` INT, IN `token` VARCHAR(50), IN `tipotraspasot` INT, IN `sucorigent` INT, IN `sucdestinot` INT, IN `idproveedort` INT, IN `descripciont` VARCHAR(50))   BEGIN
DECLARE traspaso INT;
DECLARE registros INT;
DECLARE total DECIMAL(10,2);
DECLARE nueva_existencia int;
DECLARE existencia_actual int;

DECLARE tmp_cod_producto int;
DECLARE tmp_cant_producto int;
DECLARE tmp_fec_ven date;
DECLARE a int;
SET a = 1;

CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_prod BIGINT,
    cant_prod int,
    fec_ven DATE);
SET registros = (SELECT COUNT(*) FROM detalle_temp_traspaso WHERE token_user = token);
IF registros > 0 THEN
INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod, fec_ven) SELECT idmedicamento, cantidad, fechavencimiento FROM detalle_temp_traspaso WHERE token_user = token;
INSERT INTO traspaso (iduser,idtipomov, notatraspaso, idsucorigen, idsucdestino, idproveedor) VALUES (cod_usuario, tipotraspasot, descripciont, sucorigent, sucdestinot, idproveedort);
SET traspaso = LAST_INSERT_ID();

INSERT INTO detalletraspaso(notraspaso,idmedicamento,fechavencimiento, cantidad,preciocompra, precioventa) SELECT (traspaso) AS notraspaso, idmedicamento, fechavencimiento ,cantidad,preciocompra, precioventa FROM detalle_temp_traspaso WHERE token_user = token;
WHILE a <= registros DO
	SELECT cod_prod, cant_prod, fec_ven INTO tmp_cod_producto,tmp_cant_producto, tmp_fec_ven FROM tbl_tmp_tokenuser WHERE id = a;

    SELECT stock INTO existencia_actual FROM medicamento WHERE idmedicamento = tmp_cod_producto;
    SET nueva_existencia = existencia_actual - tmp_cant_producto;
    UPDATE medicamento SET stock = nueva_existencia WHERE idmedicamento = tmp_cod_producto;    
    SET a=a+1;

END WHILE;
SET total = (SELECT SUM(cantidad * precioventa) FROM detalle_temp_traspaso WHERE token_user = token);
UPDATE traspaso SET capital = total WHERE notraspaso = traspaso;
DELETE FROM detalle_temp_traspaso WHERE token_user = token;
TRUNCATE TABLE tbl_tmp_tokenuser;
SELECT * FROM traspaso WHERE notraspaso = traspaso;
ELSE
SELECT 0;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_traspason2` (IN `cod_usuario` INT, IN `token` VARCHAR(50), IN `tipotraspasot` INT, IN `sucorigent` INT, IN `sucdestinot` INT, IN `descripciont` VARCHAR(50))   BEGIN
DECLARE dev INT;
DECLARE registros INT;
DECLARE total DECIMAL(10,2);
DECLARE nueva_existencia int;
DECLARE existencia_actual int;
DECLARE tmp_cod_producto int;
DECLARE tmp_cant_producto int;
DECLARE a int;
SET a = 1;

CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_prod BIGINT,
    cant_prod int);
SET registros = (SELECT COUNT(*) FROM detalle_temp_traspaso WHERE token_user = token);
IF registros > 0 THEN
INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod) SELECT idmedicamento, cantidad FROM detalle_temp_traspaso WHERE token_user = token;
INSERT INTO devolucion_suc (iduser,idtipomov, notadevolucion, idsucorigen, idsucdestino) VALUES (cod_usuario, tipotraspasot, descripciont, sucorigent, sucdestinot);
SET dev = LAST_INSERT_ID();

INSERT INTO detalledevolucionsuc(nodevsuc,idmedicamento,fechavencimiento, cantidad,preciocompra, precioventa) SELECT (dev) AS notraspaso, idmedicamento, fechavencimiento ,cantidad,preciocompra, precioventa FROM detalle_temp_traspaso WHERE token_user = token;
WHILE a <= registros DO
	SELECT cod_prod, cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;

    SELECT stock INTO existencia_actual FROM medicamento WHERE idmedicamento = tmp_cod_producto;
    SET nueva_existencia = existencia_actual + tmp_cant_producto;
    UPDATE medicamento SET stock = nueva_existencia WHERE idmedicamento = tmp_cod_producto;    
    SET a=a+1;

END WHILE;
SET total = (SELECT SUM(cantidad * precioventa) FROM detalle_temp_traspaso WHERE token_user = token);
UPDATE devolucion_suc SET capital = total WHERE nodevsuc = dev;
DELETE FROM detalle_temp_traspaso WHERE token_user = token;
TRUNCATE TABLE tbl_tmp_tokenuser;
SELECT * FROM devolucion_suc WHERE nodevsuc = dev;
ELSE
SELECT 0;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `cod_usuario` INT, IN `cod_cliente` INT, IN `token` VARCHAR(50))   BEGIN
DECLARE factura INT;
DECLARE registros INT;
DECLARE total DECIMAL(10,2);
DECLARE nueva_existencia int;
DECLARE existencia_actual int;

DECLARE tmp_cod_producto int;
DECLARE tmp_cant_producto int;
DECLARE a int;
SET a = 1;

CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_prod BIGINT,
    cant_prod int);
SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
IF registros > 0 THEN
INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod) SELECT codproducto, cantidad FROM detalle_temp WHERE token_user = token;
INSERT INTO factura (usuario,codcliente) VALUES (cod_usuario, cod_cliente);
SET factura = LAST_INSERT_ID();

INSERT INTO detallefactura(nofactura,codproducto,cantidad,precio_venta) SELECT (factura) AS nofactura, codproducto, cantidad,precio_venta FROM detalle_temp WHERE token_user = token;
WHILE a <= registros DO
	SELECT cod_prod, cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
    SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = tmp_cod_producto;
    SET nueva_existencia = existencia_actual - tmp_cant_producto;
    UPDATE producto SET existencia = nueva_existencia WHERE codproducto = tmp_cod_producto;
    SET a=a+1;
END WHILE;
SET total = (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
UPDATE factura SET totalfactura = total WHERE nofactura = factura;
DELETE FROM detalle_temp WHERE token_user = token;
TRUNCATE TABLE tbl_tmp_tokenuser;
SELECT * FROM factura WHERE nofactura = factura;
ELSE
SELECT 0;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_ventas` (IN `cod_usuario` INT, IN `cod_cliente` INT, IN `token` VARCHAR(50))   BEGIN
DECLARE factura INT;
DECLARE registros INT;
DECLARE total DECIMAL(10,2);
DECLARE nueva_existencia int;
DECLARE existencia_actual int;

DECLARE tmp_cod_producto int;
DECLARE tmp_cant_producto int;
DECLARE a int;


CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_prod BIGINT,
    cant_prod int);
SET registros = (SELECT COUNT(*) FROM detalle_tempf WHERE token_user = token);
IF registros > 0 THEN
INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod) SELECT codmedicamento, cantidad FROM detalle_tempf WHERE token_user = token;
INSERT INTO facturas (usuario,codcliente) VALUES (cod_usuario, cod_cliente);
SET factura = LAST_INSERT_ID();

INSERT INTO detallefacturas(nofactura,codmedicamento,fechavencimiento, cantidad,precio_venta) SELECT (factura) AS nofactura, codmedicamento, fechavencimiento, cantidad, precio_venta FROM detalle_tempf WHERE token_user = token;

SET total = (SELECT SUM(cantidad * precio_venta) FROM detalle_tempf WHERE token_user = token);
UPDATE facturas SET totalfactura = total WHERE nofactura = factura;
DELETE FROM detalle_tempf WHERE token_user = token;
TRUNCATE TABLE tbl_tmp_tokenuser;
SELECT * FROM facturas WHERE nofactura = factura;
ELSE
SELECT 0;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `prueba` ()   BEGIN
DECLARE ingresos int;
DECLARE salidas int;
DECLARE total int;
SET ingresos = (SELECT SUM(cantidad) FROM kardex WHERE (idmedicamento = 1 AND fechavencimiento = '2024-12-31') AND (idtipomov = 1 OR idtipomov = 3 OR idtipomov = 5)) ;
SET salidas = (SELECT SUM(cantidad) FROM kardex WHERE (idmedicamento = 1 AND fechavencimiento = '2024-12-31') AND (idtipomov = 2 OR idtipomov = 4 OR idtipomov = 6));
SET total = ingresos - salidas;
SELECT ingresos, salidas, total;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accionterapeutica`
--

CREATE TABLE `accionterapeutica` (
  `idaccion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `accionterapeutica`
--

INSERT INTO `accionterapeutica` (`idaccion`, `nombre`) VALUES
(17, 'Adsorbente de Lípidos'),
(8, 'Anabolizante'),
(1, 'Analgésico'),
(23, 'Analgésico - Antiinflamatorio '),
(28, 'Anestésico antiséptico analgésico local'),
(27, 'Anestético analgésico local'),
(4, 'Antiandrógeno'),
(7, 'Antianémico'),
(3, 'Antibiótico'),
(26, 'Antibiótico bactericida de amplio espectro'),
(21, 'Antibiótico macrólido de amplio espectro'),
(24, 'Antidepresivo'),
(2, 'Antiinflamatorio'),
(5, 'Antineoplásico'),
(19, 'Antioxidante'),
(30, 'Antiprotozoario Antimalárico Antirreumático'),
(29, 'Coadyuvante en la reconstitución de la flora instestinal'),
(31, 'Colagogo'),
(20, 'Emoliente'),
(9, 'Energético'),
(10, 'Estimulante'),
(14, 'Exfoliante'),
(22, 'Farmacoterápia del acné'),
(16, 'Fotoinmunoprotector'),
(15, 'Hidratante'),
(13, 'Hipocalcémico'),
(18, 'Sustituto de Plasma'),
(25, 'Tratamiento de la disfunción eréctil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id` int(11) NOT NULL,
  `nombrecaja` varchar(50) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 2,
  `usuarioreg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id`, `nombrecaja`, `idusuario`, `idsucursal`, `estado`, `usuarioreg`) VALUES
(1, 'Caja Central Nº 1', 1, 2, 2, 1),
(5, 'Caja Central Nº 2', 3, 2, 2, 1),
(6, 'Caja Central Nº 3', 4, 2, 2, 1),
(7, 'Caja Central Nº 4', 6, 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` bigint(20) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `responsable` varchar(50) NOT NULL,
  `apertura` decimal(10,2) NOT NULL,
  `ventas` decimal(10,2) DEFAULT NULL,
  `egresos` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `efectivo` decimal(10,2) DEFAULT NULL,
  `diferencia` decimal(10,2) DEFAULT NULL,
  `observaciones` varchar(50) DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  `idestado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` bigint(20) NOT NULL,
  `nit_ci` int(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` int(15) DEFAULT NULL,
  `direccion` varchar(200) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nit_ci`, `nombre`, `telefono`, `direccion`, `usuario_id`) VALUES
(1, 0, 'SIN NOMBRE', 0, 'SIN DIRECCION', 1),
(2, 6155832, 'RIVEROS', 2785454, 'SIN DIRECCION', 1),
(8, 10202454, 'LOPEZ', 0, 'SIN DIRECCION', 3),
(9, 6112151, 'todo', 0, 'SIN DIRECCION', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nit` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` text NOT NULL,
  `iva` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nit`, `nombre`, `razon_social`, `telefono`, `email`, `direccion`, `iva`) VALUES
(1, 1024102810, 'FARMACIA REYNA ROUSE', 'FARMACIA REYNA ROUSE', 69945679, 'info@farmasantaclara.com.bo', 'La Paz - Bolivia', 0.13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalledevolucionsuc`
--

CREATE TABLE `detalledevolucionsuc` (
  `correlativo` int(11) NOT NULL,
  `nodevsuc` int(11) NOT NULL,
  `idmedicamento` int(11) NOT NULL,
  `fechavencimiento` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `preciocompra` decimal(10,2) NOT NULL,
  `precioventa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefacturas`
--

CREATE TABLE `detallefacturas` (
  `correlativo` bigint(20) NOT NULL,
  `nofactura` bigint(20) NOT NULL,
  `codmedicamento` int(11) NOT NULL,
  `fechavencimiento` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallelote`
--

CREATE TABLE `detallelote` (
  `correlativo` int(11) NOT NULL,
  `nolote` bigint(20) NOT NULL,
  `codmedicamento` bigint(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `fecvencimiento` date NOT NULL,
  `nombrecompleto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detallelote`
--

INSERT INTO `detallelote` (`correlativo`, `nolote`, `codmedicamento`, `cantidad`, `precio_compra`, `fecvencimiento`, `nombrecompleto`) VALUES
(1, 1, 1, 100, 7.50, '2024-12-31', 'ANDROSTAT 50 - CIPROTERONA (ACETATO) Comprimido 50mg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalletraspaso`
--

CREATE TABLE `detalletraspaso` (
  `correlativo` int(11) NOT NULL,
  `notraspaso` int(11) NOT NULL,
  `idmedicamento` int(11) NOT NULL,
  `fechavencimiento` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `preciocompra` decimal(10,2) NOT NULL,
  `precioventa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_tempf`
--

CREATE TABLE `detalle_tempf` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `codmedicamento` int(11) NOT NULL,
  `fechavencimiento` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp_lote`
--

CREATE TABLE `detalle_temp_lote` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `codmedicamento` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `fecvencimiento` date NOT NULL,
  `nombrecompleto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp_traspaso`
--

CREATE TABLE `detalle_temp_traspaso` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `idmedicamento` int(11) NOT NULL,
  `fechavencimiento` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `preciocompra` decimal(10,2) NOT NULL,
  `precioventa` decimal(10,2) NOT NULL,
  `nombrecompleto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion_suc`
--

CREATE TABLE `devolucion_suc` (
  `nodevsuc` int(11) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `idtipomov` int(11) NOT NULL,
  `notadevolucion` varchar(50) NOT NULL,
  `idsucorigen` int(11) NOT NULL,
  `idsucdestino` int(11) NOT NULL,
  `capital` int(11) NOT NULL,
  `iduser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` bigint(20) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `monto` decimal(10,2) NOT NULL,
  `motivo` varchar(50) NOT NULL,
  `nota` varchar(80) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_caja`
--

CREATE TABLE `estado_caja` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estado_caja`
--

INSERT INTO `estado_caja` (`id`, `descripcion`) VALUES
(1, 'ABIERTA'),
(2, 'CERRADA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_lote`
--

CREATE TABLE `estado_lote` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estado_lote`
--

INSERT INTO `estado_lote` (`id`, `descripcion`) VALUES
(1, 'PAGADO'),
(2, 'PENDIENTE'),
(3, 'PAGO PARCIAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_traspaso`
--

CREATE TABLE `estado_traspaso` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estado_traspaso`
--

INSERT INTO `estado_traspaso` (`id`, `descripcion`) VALUES
(1, 'ENVIADO'),
(2, 'RECEPCIONADO'),
(3, 'CONFIRMADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `nofactura` int(11) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) NOT NULL,
  `codcliente` int(11) NOT NULL,
  `totalfactura` decimal(10,2) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forma`
--

CREATE TABLE `forma` (
  `idforma` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `forma`
--

INSERT INTO `forma` (`idforma`, `nombre`) VALUES
(47, 'Aereosol'),
(51, 'Ampolla'),
(1, 'Cápsula'),
(2, 'Cápsula Blanda'),
(3, 'Cartucho Dental'),
(4, 'Comprimido'),
(5, 'Comprimido Ranurado'),
(49, 'Comprimido Recubierto'),
(6, 'Comprimido Sublingual'),
(7, 'Comprimido Vaginal'),
(8, 'Crema'),
(46, 'Crema Dérmica'),
(55, 'Crema facial'),
(48, 'Crema Vaginal'),
(9, 'Emulsión Inyectable'),
(10, 'Emulsión Oral'),
(11, 'Gas'),
(12, 'Gel'),
(13, 'Gotas'),
(14, 'Gotas Óticas'),
(15, 'Granulado'),
(16, 'Implante Subdérmico'),
(17, 'Inyectable'),
(19, 'Jalea'),
(18, 'Jarabe'),
(54, 'Líquido'),
(20, 'Loción'),
(21, 'Óvulo'),
(22, 'Pasta'),
(57, 'Pastillas'),
(23, 'Polvo'),
(50, 'Polvo Liofilizado'),
(24, 'Polvo Oral'),
(25, 'Polvo para Enema'),
(26, 'Polvo para Inyectable'),
(27, 'Polvo para Solución Oral'),
(28, 'Polvo para Suspensión Oral'),
(53, 'Pomada'),
(29, 'Pomada Oftálmica'),
(30, 'Solución'),
(31, 'Solución Acuosa'),
(32, 'Solución Hidroalcohólica'),
(52, 'Solución Inyectable'),
(33, 'Solución Nasal'),
(34, 'Solución Oftálmica'),
(35, 'Solución Oral'),
(36, 'Solución Oral Gotas'),
(37, 'Solución para Atomización'),
(38, 'Solución para Nebulización'),
(39, 'Solución Parental de gran Volúmen'),
(40, 'Solución Tópica'),
(41, 'Supositorio'),
(42, 'Suspensión'),
(43, 'Suspensión Oral'),
(56, 'Tableta'),
(44, 'Unguento'),
(45, 'Unguento Oftálmico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

CREATE TABLE `kardex` (
  `idmov` int(11) NOT NULL,
  `fechamovimiento` date NOT NULL,
  `idtipomov` int(11) NOT NULL,
  `nodoc` bigint(20) NOT NULL,
  `idproveedor` int(11) DEFAULT NULL,
  `idmedicamento` int(11) NOT NULL,
  `fechavencimiento` date NOT NULL,
  `idsucorigen` int(11) DEFAULT NULL,
  `idsucdestino` int(11) DEFAULT NULL,
  `preciocompra` decimal(10,2) DEFAULT NULL,
  `precioventa` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`idmov`, `fechamovimiento`, `idtipomov`, `nodoc`, `idproveedor`, `idmedicamento`, `fechavencimiento`, `idsucorigen`, `idsucdestino`, `preciocompra`, `precioventa`, `cantidad`, `idusuario`) VALUES
(1, '2023-06-03', 1, 1, 1, 1, '2024-12-31', NULL, 1, 7.50, NULL, 100, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `nolote` bigint(20) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) NOT NULL,
  `codproveedor` int(11) NOT NULL,
  `totalcompra` decimal(10,2) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `lote`
--

INSERT INTO `lote` (`nolote`, `fecha`, `usuario`, `codproveedor`, `totalcompra`, `descripcion`, `estado`) VALUES
(1, '2023-06-03', 1, 1, 750.00, 'SDAF', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamento`
--

CREATE TABLE `medicamento` (
  `idmedicamento` bigint(20) NOT NULL,
  `codmedicamento` varchar(100) NOT NULL,
  `laboratorio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `idpactivo` bigint(20) NOT NULL,
  `idforma` bigint(20) NOT NULL,
  `idacciont` bigint(20) NOT NULL,
  `concentracion` text NOT NULL,
  `idunidad` bigint(20) NOT NULL,
  `presentacion` varchar(100) DEFAULT NULL,
  `formula` varchar(100) DEFAULT NULL,
  `indicaciones` mediumtext DEFAULT NULL,
  `posologia` longtext DEFAULT NULL,
  `contradicciones` longtext DEFAULT NULL,
  `preciounitario` decimal(10,2) NOT NULL,
  `cantidadminima` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `medicamento`
--

INSERT INTO `medicamento` (`idmedicamento`, `codmedicamento`, `laboratorio`, `nombre`, `idpactivo`, `idforma`, `idacciont`, `concentracion`, `idunidad`, `presentacion`, `formula`, `indicaciones`, `posologia`, `contradicciones`, `preciounitario`, `cantidadminima`, `stock`) VALUES
(1, 'MED-00001', 3, 'ANDROSTAT 50', 107, 4, 4, '50', 3, 'Envase conteniendo 50 comprimidos.', 'Cada comprimido contiene: Ciproterona Acetato 50 mg.', 'Tratamiento de carcinoma de próstata, desviaciones sexuales, pubertad precoz en el hombre y en la mujer en manifestaciones de androgenización de grado severo, hirsutismo grave, alopecia androgénica, acné y seborrea.', 'En carcinoma de próstata los pacientes orquiectomizados 2 comprimidos 1 a 2 veces al día. \r\nEn pacientes no orquiectomizados 2 comprimidos 2 a 3 veces al día. \r\nEn desviaciones sexuales 1 comprimido 2 veces al día. Puede ser necesario aumentar la dosis hasta 2 e incluso 3 tomas diarias de 2 comprimidos c/u. \r\nUna vez conseguido un resultado satisfactorio, se intenta mantener el efecto terapéutico con una dosis menor, siendo a menudo suficiente tomar ½ comprimido 2 veces al día.', 'Embarazo, período de lactancia, hepatopatías, ictericia, o prurito severo durante algún embarazo anterior, antecedentes de herpes gravídico, síndrome de Dubin-Johnson y de Rotor, depresiones crónicas graves, procesos tromboembólicos o antecedentes de los mismos, anemia de células falciformes. En el tratamiento del carcinoma de próstata, algunas contraindicaciones, como la anemia de células falciformes o los antecedentes tromboembólicos, podrán ser relativizadas si el balance riesgo/ beneficio lo justifican.', 7.50, 0, 100),
(2, 'MED-00002', 3, 'BIDROSTAT', 68, 49, 4, '50', 3, 'Envase conteniendo 28 comprimidos recubiertos.', 'Cada comprimido contiene: Bicalutamida 50 mg.', 'Tratamiento de cáncer avanzado de próstata en combinación con un análogo LHRH o castración quirúrgica.', 'Hombres adultos incluyendo ancianos: Un comprimido una vez al día. El tratamiento deberá comenzar al mismo tiempo que el tratamiento análogo LHRH o castración quirúrgica. Niños: Está contraindicado en niños. Ajuste de dosis: No se requieren en caso de insuficiencia renal, ni en caso de insuficiencia hepática moderada,. Podrá ocurrir una leve acumulación en pacientes con deterioro hepático moderado a severo.', 'BIDROSTAT está contraindicado en mujeres y niños. No se debe administrar BIDROSTAT a pacientes que presenten hipersensibilidad a la droga.', 8.00, 0, 0),
(3, 'MED-00003', 3, 'DONATAXEL', 167, 51, 5, '80/4', 5, 'Estuche conteniendo: 1 frasco/1 ampolla.', 'Cada frasco ampolla contiene: Docetaxel 80 mg.\r\nCada ampolla solvente contiene: Alcohol 95% 764,40 m', 'Es un agente antineoplásico en el tratamiento de pacientes con cáncer de mama local avanzado o metastásico que han progresado a tratamientos quimioterápicos previos. Tambien esta indicado en el tratamiento de pacientes con carcinoma de pulmón no de pequeñas células local avanzado o metastásico, para quienes la terapia en base a platino no ha resultado.', 'La dosis recomendada es de 100 mg/m2. Se deberá administrar por vía intravenosa en una infusión de una hora, cada tres semanas. Se sugiere que los pacientes que reciban Docetaxel reciban corticoides (dexametasona 16 mg/día) durante 3 días, recibiendo la primera dosis antes de la aplicación quimioterápica.\r\n Esta práctica se sugiere a fin de reducir la incidencia y severidad de la retención de líquidos y las reacciones de hipersensibilidad. Se deberá disminuir la dosis de Docetaxel en un 25% en los pacientes que presentaron neutropenia grave (neutrófilos menos de 500/mm3 por más de una semana), reacciones cutáneas graves o neutropenia periférica grave. \r\nSi estas reacciones persisten, la dosis deberá disminuirse a 55 mg/m2 o se deberá suspender el tratamiento. Pacientes con deficiencia hepática leve: \r\nNo deberán recibir Docetaxel aquellos pacientes con bilirrubina por encima de los valores normales, como así también los que tengan un aumento en las transaminasas x 1,5 y FAL x 2,5 por encima de los valores normales superiores.', 'Esta contraindicado en pacientes con historia previa de hipersensibilidad severa al DOCETAXEL o a cualquier compuesto que en su formulación contenga Polisorbato 80. DONATAXEL no deberá utilizarse en pacientes con recuento de neutrófilos menores de 1.500 células/mm3.', 1250.00, 0, 0),
(4, 'MED-00004', 3, 'ERITORGEN', 188, 30, 10, '4000', 10, 'Envase contenido 1 frasco ampolla.', 'Cada frasco ampolla contiene: Eritropoyetina 4.000 UI.', 'Tratamiento de anemia en los pacientes con falla renal crónica. Tratamiento de la anemia secundaria a la zidovudina en los pacientes infectados con VIH. Tratamiento de la anemia en pacientes con cáncer con quimioterapia. Reduccion de las transfusiones de sangre alogenica en pacientes quirúrgicos', 'En pacientes con falla renal crónica se recomienda una dosis de inicio entre 50 a 100 UI/kg tres veces por semana, la dosis deberá reducirse si el hematocrito aumenta hasta 36% o si hay aumento de 4 puntos en 2 semanas. Se recomienda aumentar la dosis cuando el hematocrito no aumente entre 5 a 6 puntos luego de ocho semanas de tratamiento, o el hematocrito está por debajo de rango sugerido. En pacientes con infecciones por VIH, dosis de inicio en niveles de eritropoyetina menores o iguales a 500 mU/ml que reciben AZT menor o igual a 4.200 mg/semana, la dosis recomendada es de 100 UI/kg en forma subcutánea o intravenosa tres veces por semana durante 8 semanas.Si la respuesta no es satisfactoria la dosis se deberá aumentar de 300 UI/kg tres veces por semana, la posibilidad que respondan con dosis mayores es poco frecuente. En pacientes con cáncer en tratamiento con quimioterapia la dosis inicial es de 150 UI/kg en forma subcutánea 3 veces por semana. Si la respuesta no es adecuada luego de las ocho semanas se deberá aumentar la dosis a 300 UI/kg tres veces por semana son ocasionales. En pacientes sometidos a cirugía la dosis recomendada es de 300 UI/kg/día en forma subcutánea los 10 días previos a cirugía, el día de la cirugia y los cuatro días posteriores a la misma.Un esquema alternativo es 600 UI/ kg subcutánea una vez por semana y una cuarta dosis el día de la cirugía.', 'Hipertensión no controlada. Hipersensibilidad conocida a alguno de los componentes de la formulación.', 55.00, 0, 0),
(5, 'MED-00005', 3, 'ERITROGEN', 188, 30, 10, '10000', 10, 'Envase conteniendo 1 frasco ampolla.', 'Cada frasco ampolla contiene: Eritropoyetina 10.000 UI.', 'Tratamiento de anemia en los pacientes con falla renal crónica. Tratamiento de la anemia secundaria a la zidovudina en los pacientes infectados con VIH. Tratamiento de la anemia en pacientes con cáncer con quimioterapia. Reduccion de las transfusiones de sangre alogenica en pacientes quirúrgicos', 'En pacientes con falla renal crónica se recomienda una dosis de inicio entre 50 a 100 UI/kg tres veces por semana, la dosis deberá reducirse si el hematocrito aumenta hasta 36% o si hay aumento de 4 puntos en 2 semanas. Se recomienda aumentar la dosis cuando el hematocrito no aumente entre 5 a 6 puntos luego de ocho semanas de tratamiento, o el hematocrito está por debajo de rango sugerido. En pacientes con infecciones por VIH, dosis de inicio en niveles de eritropoyetina menores o iguales a 500 mU/ml que reciben AZT menor o igual a 4.200 mg/semana, la dosis recomendada es de 100 UI/kg en forma subcutánea o intravenosa tres veces por semana durante 8 semanas.Si la respuesta no es satisfactoria la dosis se deberá aumentar de 300 UI/kg tres veces por semana, la posibilidad que respondan con dosis mayores es poco frecuente. En pacientes con cáncer en tratamiento con quimioterapia la dosis inicial es de 150 UI/kg en forma subcutánea 3 veces por semana. Si la respuesta no es adecuada luego de las ocho semanas se deberá aumentar la dosis a 300 UI/kg tres veces por semana son ocasionales. En pacientes sometidos a cirugía la dosis recomendada es de 300 UI/kg/día en forma subcutánea los 10 días previos a cirugía, el día de la cirugia y los cuatro días posteriores a la misma.Un esquema alternativo es 600 UI/ kg subcutánea una vez por semana y una cuarta dosis el día de la cirugía.', 'Hipertensión no controlada. Hipersensibilidad conocida a alguno de los componentes de la formulación.', 105.00, 0, 0),
(6, 'MED-00006', 3, 'FILGEN', 214, 52, 10, '300', 4, 'Estuche conteniendo 1 frasco ampolla.', 'Cada frasco ampolla con solución inyectable contiene: Filgrastim 300 ug.', 'Pacientes con cáncer que reciben quimioterapia mielosupresiva. El FILGEN está indicado para disminuir la incidencia de infección, (neutropenia febril), en pacientes con neoplasias no mielógenas sometidos a tratamiento quimioterápico, con una elevada incidencia de neutropenia severa asociada con fiebre; como así también la duración de la neutropenia.', 'Pacientes con cáncer y tratamiento de quimioterapia mielosupresiva la dosis recomendada es de 5 ug/kg/día administrada por vía SC o en forma de infusión IV corta de 15 a 30 minutos, diluida en una solución de dextrosa al 5% una vez por día. Se puede incrementar la dosis en 5 ug/kg, por cada ciclo de quimioterapia, de acuerdo a la duración y la severidad del nadir del recuento absoluto de neutrofilos. En pacientes sometidos a transplantes de medula osea la dosis es de 10 ug/kg/día en forma de infusión IV de 4 a 24 hrs, o como infusión continua SC de 24 horas. Se puede administrar dentro de las primeras 24 horas de efectuado el procedimiento. Si el recuento absoluto de neutrófilos disminuye a menos de 1000 mm3 en cualquier momento durante la dosis de 5 ug/kg/día, el filgastrim puede ser aumentado a 10 ug/kg/día. La dosis para la movilización de progenitores de médula ósea en sangre es de 10 ug/kg/día, por vía SC, tanto sea en bolo como en infusión continua. Se recomienda que el FILGRASTIM se aplique por lo menos durante 4 días antes de la primera leucaféresis y continue hasta la última. En neutropenia congenita la dosis recomendada de inicio es de 6 ug/kg SC, dos veces al día en forma diaria. En neutropenia cíclica o idiopática dosis recomendada de inicio es de 5 ug/kg/día en forma SC. La dosis debe ser ajustada para cada paciente en forma individual, de acuerdo al curso clínico.', 'FILGRASTIM esta contraindicado en pacientes con hipersensibilidad conocida a proteínas derivadas de la E.coli, al FILGRASTIM mismo, o cualquier componente de la formulación.', 205.00, 0, 0),
(7, 'MED-00007', 3, 'FLUTRAX', 227, 4, 4, '250', 3, 'Envase conteniendo 60 comprimidos.', 'Cada comprimido contiene: Flutamida 250 mg.', 'Está indicado en el tratamiento del carcinoma de próstata. Está indicado en combinación con análogos de la LH-RH, para el tratamiento del carcinoma metastásico de próstata.', 'La dosis recomendada es de 1 comprimido cada 8 horas por vía oral. Dosis total diaria de 750 mg.', 'Hipersensibilidad a cualquiera de los componentes de la fórmula. Insuficiencia hepática grave.', 6.00, 0, 0),
(8, 'MED-00008', 3, 'GEMBIO', 233, 50, 5, '200', 3, 'Envase conteniendo 1 frasco ampolla.', 'Cada frasco ampolla GEMBIO 200 mg contiene: Gemcitabina 200 mg.', 'Está indicado en Cáncer de Ovario en combinación con carboplatino. Cáncer de mama en combinación con paclitaxel. Cáncer de Pulmón a células No pequeñas en combinación con Cisplatino. Cáncer de Páncreas como monodroga. Cáncer de vejiga en combinación cisplatino.', 'Esta indicado para el uso IV únicamente. Cáncer de ovario 1.000 mg/m2 en 30 minutos, día 1 y 8, cada 21 días. Cáncer de Mama 1.250 mg/m2 en 30 minutos, día 1 y 8, cada 21 días. Cáncer de Pulmón a Células No Pequeñas: esquema de 4 semanas: 1.000 mg/m2 en 30 minutos, día 1, 8 y 15, cada 28 días; esquema de 3 semanas: 1.250 mg/m2 en 30 minutos, día 1 y 8 cada 21 días. Cáncer de Páncreas: 1.000 mg en 30 minutos 1 vez por semana durante 7 semanas, seguida de 1 semana de descanso. Los ciclos subsecuentes se aplicaron en forma semanal durante 3 semanas, cada 4 semanas. Cáncer de Vejiga: 1.000 mg/m2 en 30 minutos, día 1, 8 y 15 cada 28 días. La disminución de ladosis debería basarse según las toxicidades.', 'Está contraindicado en pacientes con hipersensibilidad a la gemcitabina.', 300.00, 0, 0),
(9, 'MED-000009', 3, 'GEMBIO', 233, 50, 5, '1000', 3, 'Envase conteniendo 1 frasco ampolla.', 'Cada frasco ampolla GEMBIO 1 g contiene: Gemcitabina 1000 mg.', 'Está indicado en Cáncer de Ovario en combinación con carboplatino. Cáncer de mama en combinación con paclitaxel. Cáncer de Pulmón a células No pequeñas en combinación con Cisplatino. Cáncer de Páncreas como monodroga. Cáncer de vejiga en combinación cisplatino.', 'Esta indicado para el uso IV únicamente. Cáncer de ovario 1.000 mg/m2 en 30 minutos, día 1 y 8, cada 21 días. Cáncer de Mama 1.250 mg/m2 en 30 minutos, día 1 y 8, cada 21 días. Cáncer de Pulmón a Células No Pequeñas: esquema de 4 semanas: 1.000 mg/m2 en 30 minutos, día 1, 8 y 15, cada 28 días; esquema de 3 semanas: 1.250 mg/m2 en 30 minutos, día 1 y 8 cada 21 días. Cáncer de Páncreas: 1.000 mg en 30 minutos 1 vez por semana durante 7 semanas, seguida de 1 semana de descanso. Los ciclos subsecuentes se aplicaron en forma semanal durante 3 semanas, cada 4 semanas. Cáncer de Vejiga: 1.000 mg/m2 en 30 minutos, día 1, 8 y 15 cada 28 días. La disminución de ladosis debería basarse según las toxicidades.', 'Está contraindicado en pacientes con hipersensibilidad a la gemcitabina.', 900.00, 0, 0),
(10, 'MED-00010', 3, 'IDELARA 2.5', 299, 49, 5, '2.5', 3, 'Envase conteniendo 30 comprimidos recubiertos.', 'Cada comprimido contiene: Letrozol 2,5 mg.', 'Está indicado en la terapia adyuvante en mujeres posmenopaúsicas en tratamiento de cáncer de mama temprano con receptores hormonales positivos.', 'La dosis recomendada de Idelara es un comprimido diario de 2,5 mg. Se puede administrar en aunas o con las comidas. En los pacientes con enfermedad avanzada, la terapia con Idelara debería continuar hasta la progresión de la enfermedad sea evidente.', 'Idelara está contraindicado en aquellas pacientes con hipersensibilidad al letrozol o a algunos de los excipientes de la formulación. Esta contraindicado también en mujeres premenopaúsicas.', 11.00, 0, 0),
(11, 'MED-00011', 3, 'IRINOGEN', 281, 52, 5, '100', 3, 'Envase conteniendo 1 frasco ampolla.', 'Cada frasco ampolla contiene: Irinotecan 100 mg.', 'Carcinoma metastásico de cólon y recto, con enfermedad progresiva o recurrente luego del tratamiento con 5 fluorouracilo.', 'El contenido de la ampolla debe ser diluido previamente a su infusión. Dosis inicial: 125 mg/m2 infusión intravenosa en 90 minutos, por semana durante cuatro semanas, seguido de un descanso de dos semanas. El tratamiento puede repetirse cada seis semanas. Dosis subsiguientes deberán ajustarse hasta 150 mg/m2 o hasta 50 mg/m2 en modificaciones de 25 a 50 mg/m2 de acuerdo con la tolerancia individual del paciente a la terapia. Si no aparecen signos de intolerancia a irinotecan clorhidrato, el tratamiento puede continuarse con cursos adicionales en forma indefinida, tanto para quienes responden como aquellos con enfermedad estable.', 'Diarrea: Los pacientes con diarrea severa serán controlados en forma cuidadosa y se les administrará loperamida, líquidos y electrolitos. En caso de continuar con más de diez deposiciones por día se recomienda suspender el tratamiento y luego de la recuperación del paciente continuar con la administración de IRINOTECAN CLORHIDRATO pero en dosis menores. Mielosupresión: Se debe interrumpir la terapia en caso de neutropenia febril o si el recuento de neutrófilos cae a menos de 500/mm3. También debe disminuirse la dosis si se produce un descenso significativo del recuento de blancos (<2000/mm3), de neutrófilos (<1000/mm3), de hemoglobina (<8 g/dl) o de plaquetas (<100000/mm3). Emesis: Puesto que es un fármaco emético, se aconseja la premedicación con antieméticos, por lo menos 30 minutos antes de su administración.', 865.00, 0, 0),
(12, 'MED-00012', 3, 'MITOTIE', 345, 51, 5, '20', 3, 'Envase con un frasco ampolla.', 'Cada frasco ampolla contiene: Mitomicina C 20 mg.', 'La Mitomicina no está indicada como monoterapia. Se ha demostrado su utilidad en el tratamiento del cáncer gástrico avanzado, como así también para el cáncer de páncreas, en esquemas de poliquimioterapia (en esquemas con drogas de utilidad demostrada en el tratamiento de esta patología). No se recomienda el uso de la Mitomicina para reemplazar a la cirugía o la radioterapia.', 'Deberá aplicarse únicamente por vía intravenosa. Se recomienda una dosis de 20 mg/m2 con un intervalo interdosis de seis a ocho semanas. Los pacientes deben ser monitoreados en forma exhaustiva desde el punto de vista hematológico habida cuenta de la mielosupresión acumulativa.', 'MITOTIE está contraindicada en pacientes con antecedentes de hipersensibilidad o reacciones idiosincrásicas. También, está contraindicada en pacientes con trombocitopenia, desórdenes de la coagulación o un aumento en la incidencia de sangrado debido a otras causas.', 490.00, 0, 0),
(13, 'MED-00013', 3, 'OXALTIE', 376, 51, 5, '100', 3, 'Envase con 1 frasco ampolla', 'Cada frasco ampolla contiene: Oxaliplatino 100 mg.', 'Tratamiento del carcinoma colorrectal avanzado (metastásico) a continuación de la etapa previa con fluoropirimidinas, tanto en forma de monoquimioterapia o poliquimioterapia (asociado a otros citostáticos de actividad sinérgica).', 'En monoquimioterapia o en combinación, la dosis recomendada es de 130 mg/m2 repetida cada tres semanas en ausencia de manifestaciones significativas de toxicidad importante. El Oxaliplatino se administra en infusión corta de 2 a 6 horas diluido en una solución de glucosa al 5% con un volumen variable de 250 a 500 ml. La dosis puede ser modificada en función de la tolerancia, particularmente neurológica. No administrar directamente por vía intravenosa sin diluir. No mezclar con ningún otro medicamento.', 'Hipersensibilidad a los derivados del platino. Embarazo, lactancia, depresión medular o trastornos neurológicos severos.', 540.00, 0, 0),
(14, 'MED-00014', 3, 'PANATAXEL', 380, 52, 5, '30/5', 5, 'PANATAXEL 30 mg. conteniendo un frasco ampolla.', 'Cada frasco ampolla contiene: Paclitaxel 30 mg.', 'Está indicado en primera línea en combinación con cisplatino para el tratamiento del cáncer de ovario. Paclitaxel está indicado en combinación con cisplatino en primera línea para el tratamiento del cáncer de pulmón a células no pequeñas, en pacientes que no son candidatos a una potencial cirugía curativa y/o radioterapia. Paclitaxel está indicado en segunda línea de tratamiento en el sarcoma de Kaposi relacionado al SIDA. Paclitaxel está indicado en el tratamiento del carcinoma de mama metastásico tras el fracaso de la quimioterapia combinada o en recaídas aparecidas dentro de los 6 meses subsiguientes a la quimioterapia adyuvante. Paclitaxel está indicado en adyuvancia para el tratamiento del cáncer de mama con axila positiva, administrado en forma secuencial con esquemas de quimioterapia con doxorrubicina.', 'Cáncer de ovario Pacientes vírgenes se recomienda una dosis de: a) 175 mg/m2 en forma intravenosa a pasar en tres horas, seguido por cisplatino 75 mg/m2 o b) 135 mg/m2 en infusión continua de 24 horas, seguido por cisplatino 75 mg/m2. Pacientes previamente tratadas la dosis y esquemas pueden ser varios. La dosis recomendada es de 135 a 175 mg/m2 en forma intravenosa, a pasar en tres horas cada tres semanas. Cáncer de pulmón a no células pequeñas Se recomienda una dosis de 135 mg/m2 en infusión de 24 horas, seguido de Cisplatino 75 mg/m2. Este esquema se repetirá cada tres semanas. Sarcoma de Kaposi relacionado al SIDA. Se recomienda una dosis de 135 mg/m2 a pasar en tres horas en forma intravenosa cada tres semanas 100 mg/m2 con un mismo tiempo de infusión, pero cada dos semanas. Cáncer de mama Adyuvancia: la dosis recomendada es de 175 mg/m2 en forma intravenosa a pasar en tres horas cada tres semanas, por cuatro cursos. Estos deben apllicarse en forma secuencial luego de esquema de combinación que contenga doxorrubicina. Enfermedad metastásica: se recomienda una dosis de 175 mg/m2 administrado en forma intravenosa en tres horas, cada tres semanas.', 'PANATAXEL está contraindicado para los pacientes con antecedentes de reacciones de hipersensibilidad a PACLITAXEL o a otras drogas formuladas en Cremophor (aceite de castor polioxietilado). No deberá utilizarse en pacientes con neutropenia < 1.500 células/mm3. Embarazo. Lactancia.', 140.00, 0, 0),
(15, 'MED-00015', 3, 'PANATAXEL', 380, 52, 5, '300/50', 5, 'PANATAXEL 300 mg. conteniendo un frasco ampolla.', 'Cada frasco ampolla contiene: Paclitaxel 300 mg.', 'Está indicado en primera línea en combinación con cisplatino para el tratamiento del cáncer de ovario. Paclitaxel está indicado en combinación con cisplatino en primera línea para el tratamiento del cáncer de pulmón a células no pequeñas, en pacientes que no son candidatos a una potencial cirugía curativa y/o radioterapia. Paclitaxel está indicado en segunda línea de tratamiento en el sarcoma de Kaposi relacionado al SIDA. Paclitaxel está indicado en el tratamiento del carcinoma de mama metastásico tras el fracaso de la quimioterapia combinada o en recaídas aparecidas dentro de los 6 meses subsiguientes a la quimioterapia adyuvante. Paclitaxel está indicado en adyuvancia para el tratamiento del cáncer de mama con axila positiva, administrado en forma secuencial con esquemas de quimioterapia con doxorrubicina', 'Cáncer de ovario Pacientes vírgenes se recomienda una dosis de: a) 175 mg/m2 en forma intravenosa a pasar en tres horas, seguido por cisplatino 75 mg/m2 o b) 135 mg/m2 en infusión continua de 24 horas, seguido por cisplatino 75 mg/m2. Pacientes previamente tratadas la dosis y esquemas pueden ser varios. La dosis recomendada es de 135 a 175 mg/m2 en forma intravenosa, a pasar en tres horas cada tres semanas. Cáncer de pulmón a no células pequeñas Se recomienda una dosis de 135 mg/m2 en infusión de 24 horas, seguido de Cisplatino 75 mg/m2. Este esquema se repetirá cada tres semanas. Sarcoma de Kaposi relacionado al SIDA. Se recomienda una dosis de 135 mg/m2 a pasar en tres horas en forma intravenosa cada tres semanas 100 mg/m2 con un mismo tiempo de infusión, pero cada dos semanas. Cáncer de mama Adyuvancia: la dosis recomendada es de 175 mg/m2 en forma intravenosa a pasar en tres horas cada tres semanas, por cuatro cursos. Estos deben apllicarse en forma secuencial luego de esquema de combinación que contenga doxorrubicina. Enfermedad metastásica: se recomienda una dosis de 175 mg/m2 administrado en forma intravenosa en tres horas, cada tres semanas.', 'PANATAXEL está contraindicado para los pacientes con antecedentes de reacciones de hipersensibilidad a PACLITAXEL o a otras drogas formuladas en Cremophor (aceite de castor polioxietilado). No deberá utilizarse en pacientes con neutropenia < 1.500 células/mm3. Embarazo. Lactancia.', 900.00, 0, 0),
(16, 'MED-00016', 3, 'SINRESOR', 21, 50, 13, '4', 3, 'Envase conteniendo un frasco ampolla con polvo liofilizado acompañado con una apolla de solvente', 'Cada frasco ampolla contiene: Acido Zoledrónico 4 mg.', 'Hipercalcemia relacionada a enfermedades malignas.', 'La dosis máxima recomendada de SINRESOR en el tratamiento de la HIT es de 4 mg. Los valores de calcio sérico corregidos por albúmina tienen que ser ≥ 12 mg/dl ó 3,0 mmol/l. Se debe administrar ésta dosis en una infusión no menor a 15 minutos. Antes de la aplicación de SINRESOR los pacientes deben contar con un análisis de creatinina. Mieloma múltiple y metástasis óseas de tumores sólidos La dosis recomendada de SINRESOR en los pacientes con mieloma múltiple y metástasis óseas de tumores sólidos (CrCl > 60 ml/min.) es de 4 mg a infundir en un tiempo no menor a 15 minutos, cada 3 a 4 semanas. Se desconoce la duración óptima del tratamiento.', 'SINRESOR está contraindicado en pacientes con hipersensibilidad al ácido zoledrónico o cualquier excipiente de la formulación.', 640.00, 0, 0),
(17, 'MED-00017', 3, 'TAMOXIS 20', 467, 4, 5, '20', 3, 'Caja conteniendo 30 comprimidos', 'Cada comprimido contiene: Tamoxifeno 20 mg', 'En carcinoma metastásico en varones y mujeres.', 'Carcinoma mamario, nódulo positivo o negativo en mujeres: 10 mg, 2 veces por día (por la mañana y por la tarde). En carcinoma metastásico en varones y mujeres: 10 a 20 mg, 2 veces por día (por la mañana y por la tarde).', 'En pacientes con hipersensibilidad al tamoxifeno o a cualquiera de los componentes de la formulación.', 2.00, 0, 0),
(18, 'MED-00018', 3, 'BIRETIX TRI ACTIVE GEL', 526, 12, 14, '50', 7, 'Envase formato de 50 ml.', 'RetinSphere Technology (hidroxipinacolona retinoato y glicosferas de retinol).\r\nBIOPEP 15.\r\n2% Ácido', 'Lesiones activas y marcas en adultos con tendencia acneica.', 'Anti-imperfecciones.\r\nReduce las hiperpigmentaciones y marcas.\r\nElimina las impurezas y el exceso de grasa.\r\nPermite la renovación de la capa más superficial de la piel.\r\nTestado bajo control dermatológico. Agitar antes de usar. Aplicar una o dos veces al día en las zonas interesadas, sobre piel previamente limpia.', 'Conservar a temperatura ambiente, evitando temperaturas extremas. Uso externo. Evitar el contacto con los ojos, si esto ocurre aclarar con agua. Es normal un ligero escozor en las primeras aplicaciones, durante los primeros minutos tras la aplicación. En caso de irritación continuada, debe retirarse el producto y suspenderse la aplicación. Antes de la exposición al sol es conveniente el uso de un fotoprotector de alta protección. Mantener fuera del alcance de los niños.', 270.00, 0, 0),
(19, 'MED-00019', 3, 'ENDOCARE AQUAFOAM', 527, 53, 15, '125', 7, 'ENDOCARE AQUAFOAM: Pomo x 125 ml.', 'Maris Aqua: agua pura y no contaminada proveniente del fondo marino del Mar Cantábrico, rica en mine', 'Limpieza diaria de todo tipo de piel, incluyendo pieles sensibles e intolerantes. Por la noche para quitar el maquillaje, restos de contaminación y excesos de sebo. Por la mañana para eliminar el exceso de sebo secretado durante la noche', 'La limpieza diaria de la piel grasa con tendencia acneica.\r\nLimpieza de la piel bajo tratamiento o procedimientos dermatológicos.', '', 290.00, 0, 0),
(20, 'MED-00020', 3, 'ENDOCARE TENSAGE AMPOLLAS', 527, 51, 15, '10/2', 5, 'ENDOCARE TENSAGE AMPOLLAS: Ampolla x 10 de 2 ml.', 'Regenerador SCA 50%.\r\n– Reafirmante Tensderm, Hexapéptidos: Argirelina 5 %.\r\n– Efecto similar al bot', 'Regenerador SCA 50%.\r\n– Reafirmante Tensderm, Hexapéptidos: Argirelina 5 %.\r\n– Efecto similar al botox, bloquea la contracción muscular reduciendo así la profundidad de las arrugas, Seriseline 5 % estimula la síntesis de hemidesmosomas y las proteínas de adhesión laminina-5 e integrina-6, aumentando la adhesión entre dermis y epidermis y aumentando la densidad de la dermis.\r\n– Carácter iluminador: ALBATIN Inhibidor no tirosinásico de la melanogénesis: contrasta la polimerización oxidativa de DOPAchrome que lleva a la formación de eumelanina, ALISTIN Péptido antiglicante natural, potencia la acción iluminadora del Albatin, NATRLQUEST Agente quelante de cobre y hierro, la actividad antitirosinásica.\r\n– Carácter hidratante: HYDROSYSTEM para aumentar la hidratación intra y extracelular: DERMAFLUX Aumenta la hidratación intracelular, HYDROMANIL Captura y retiene agua de la superficie cutánea, liberándola gradualmente a la epidermis.', '', '', 345.00, 0, 0),
(21, 'MED-00021', 3, 'ENDOCARE TENSAGE CREMA', 527, 53, 15, '25', 7, 'ENDOCARE TENSAGE CREMA: Pomo x 25 ml.', 'Regenerador: SCA 6 %.\r\n– Reafirmante: Acción reafirmante intensa: Tensderm.\r\n– Hidratante. Gran capa', 'ENDOCARE TENSAGE CREMA\r\nCrema diaria reafirmante e hidratante para pieles normales/secas. Aplicar por la mañana y/o por la noche después de la limpieza. Puede aplicarse solo o después de Endocare Tensage serum.\r\n\r\nENDOCARE TENSAGE SERUM\r\nAplicar por la mañana y/o por la noche después de la limpieza.', '', '', 295.00, 0, 0),
(22, 'MED-00022', 3, 'ENDOCARE TENSAGE SERUM', 527, 53, 15, '30', 7, 'ENDOCARE TENSAGE SERUM: Pomo x 30 ml.', 'Regenerador SCA 15 %.\r\n– Reafirmante Acción reafirmante intensa: Tensderm.\r\n– Antioxidante Acción an', 'Aplicar por la mañana y/o por la noche después de la limpieza.', '', '', 295.00, 0, 0),
(23, 'MED-00023', 3, 'ENDOCARE TENSAGE CONTORNO DE OJOS', 527, 53, 15, '15', 7, 'ENDOCARE TENSAGE CONTORNO DE OJOS: Pomo x 15 ml.', 'Regenerador SCA 10 %.\r\n– Reafirmante Acción reafirmante intensa: Tensderm.\r\n– Antioxidante Acción an', 'Dejar penetrar la fórmula extendiendo el producto de forma cuidadosa desde la parte interna a la parte externa del ojo. Perfectamente compatible con el maquillaje.', '', '', 295.00, 0, 0),
(24, 'MED-00024', 3, 'HELIOCARE 360º GEL OIL FREE ', 528, 12, 16, '50', 7, 'HELIOCARE 360º GEL OIL FREE X 50 ml.', 'FERNBLOCK FC, MELANINA BIOMIMÉTICA, OTZ-10, ROXISOMAS, FILTROS.', 'Fotoprotección en todo tipo de piel.\r\nAlteraciones de la pigmentación.\r\nPiel sensible o reactiva.\r\nPiel sometida a tratamientos fotosensibilizantes.\r\nPiel con antecedentes de cáncer de piel.\r\nPrevención de fotoenvejecimiento.', 'Aplicar 30 minutos antes de la exposición al sol.\r\nReaplicar cada 3 horas.\r\nNiños a partir de los 6 meses.', '', 190.00, 0, 0),
(25, 'MED-00025', 3, 'HELIOCARE 360º FLUID CREAM', 528, 8, 16, '50', 7, 'HELIOCARE 360º FLUID CREAM x 50 ml.', 'FERNBLOCK FC, MELANINA BIOMIMÉTICA, OTZ-10, ROXISOMAS, FILTROS.', 'Fotoprotección en todo tipo de piel.\r\nAlteraciones de la pigmentación.\r\nPiel sensible o reactiva.\r\nPiel sometida a tratamientos fotosensibilizantes.\r\nPiel con antecedentes de cáncer de piel.\r\nPrevención de fotoenvejecimiento.', 'Aplicar 30 minutos antes de la exposición al sol.\r\nReaplicar cada 3 horas.\r\nNiños a partir de los 6 meses.', '', 290.00, 0, 0),
(26, 'MED-00026', 3, 'HELIOCARE 360º MINERAL', 528, 20, 16, '50', 7, 'HELIOCARE 360º MINERAL x 50 ml.', 'FERNBLOCK FC, MELANINA BIOMIMÉTICA, OTZ-10, ROXISOMAS, FILTROS.', 'Fotoprotección en todo tipo de piel.\r\nAlteraciones de la pigmentación.\r\nPiel sensible o reactiva.\r\nPiel sometida a tratamientos fotosensibilizantes.\r\nPiel con antecedentes de cáncer de piel.\r\nPrevención de fotoenvejecimiento.', 'Aplicar 30 minutos antes de la exposición al sol.\r\nReaplicar cada 3 horas.\r\nNiños a partir de los 6 meses.', '', 190.00, 0, 0),
(27, 'MED-00027', 3, 'HELIOCARE AIRGEL', 528, 47, 16, '60', 7, 'HELIOCARE AIRGEL x 60 ml.', 'FERNBLOCK FC, MELANINA BIOMIMÉTICA, OTZ-10, ROXISOMAS, FILTROS.', 'Fotoprotección en todo tipo de piel.\r\nAlteraciones de la pigmentación.\r\nPiel sensible o reactiva.\r\nPiel sometida a tratamientos fotosensibilizantes.\r\nPiel con antecedentes de cáncer de piel.\r\nPrevención de fotoenvejecimiento.', 'Aplicar 30 minutos antes de la exposición al sol.\r\nReaplicar cada 3 horas.\r\nNiños a partir de los 6 meses.', '', 190.00, 0, 0),
(30, 'MED-00030', 3, 'FORMOLINE L112', 529, 4, 17, '50', 3, 'Caja conteniendo 60 comprimidos', 'Ingrediente que ejerce la acción mecánica de adsorción de grasas: Especificación L112 del polímero ß', 'Adsorbente de lípidos como ayuda en:\r\nEl tratamiento del sobrepeso.\r\nEl control del peso a largo plazo.\r\nLa reducción de la absorción del colesterol de los alimentos.', 'Para pérdida de peso: 2 comprimidos dos veces al día. Tomar formoline L112 junto con las dos comidas que tienen mayor contenido de grasa, hacia el final de la comida para un mejor efecto. Para mantención del peso: 1 comprimido dos veces al día. Tomar formoline L112 junto con las dos comidas que tienen mayor contenido de grasa, hacia el final de la comida para un mejor efecto.', 'Sólo tome formoline L112 después de consultar con su médico en caso de: Consumo prolongado de medicamentos. Enfermedades gastrointestinales serias y después de una cirugía en el tracto gastrointestinal. Problemas digestivos crónicos (estreñimiento crónico, inercia colónica, etc.) Niños en crecimiento y Adolescentes Cualquier persona sobre 80 años de edad Ingesta prolongada de medicamentos para reducir la inercia del colón. Formoline L112 no debe ser tomado por las siguientes personas: Bebés y niños hasta tres años de edad Personas que sufren bajo peso (IMC < 18,5) Personas con alergia conocida a los crustáceos o a alguno de los ingredientes. Durante el embarazo o la lactancia no se debería intentar un tratamiento para el sobrepeso o sólo hacerlo bajo supervisión médica.', 215.00, 0, 0),
(32, 'MED-00032', 3, 'ALBUNORM', 26, 17, 18, '200 ', 7, 'Solución Inyectable. caja por un fraco por 50 ml.', 'Solución con 200 g de proteína plasmática/lt con albúmina humana de al menos 96 %.', 'Las indicaciones típicas son: Shock hemorrágico. Shock por perdida de plasma. Otras situaciones acompañadas de shock. Casos de deficiencia de albúmina y/o plasma, antes, durante y despues de cirugías, también en: Quemaduras. Falla hepática. Hepatocirrosis. Nefritis. Síndrome nefrotico Trastornos gastrointestinales Síndrome de Lyell`s. Ascitis. SÌndrome de distress respiratorio adulto Kernicterus en recién nacidos, edema cerebral, procesos tóxicos (toxemia en el embarazo).', 'Cuando se usa la albúmina humana en terapia de reemplazo, la dosis requerida es guiada por los parámetros circulatorios normales.\r\n\r\nEl límite máximo para la caída de la presión osmótica coloidal es de 20 mm Hg (2.7 kpa). En caso de administrar albúmina humana la dosis requerida en gramos se puede estimar usando el siguiente cálculo: [Total proteína requerida (g/l)  proteína total actual en (g/l)] x Volumen de plasma (l) x 2. El volumen de plasma fisiológico puede ser calculado aproximadamente 40ml/kg peso corporal. Como la fórmula en cualquier caso es solo aproximada, un monitoreo de laboratorio de concentración de proteína alcanzado es recomendado.', 'Alergia conocida a albúmina Toda situaciones en que una hipervolemia y sus consecuencias, o una hemodilución puedan representar un riego especial para el paciente.', 330.00, 0, 0),
(33, 'MED-00033', 3, 'ACTRON', 263, 2, 1, '400', 3, 'Caja con 10 capsulas blandas', 'Cada cápsula blanda de gelatina contiene: como ingrediente activo, 400 miligramos de Ibuprofeno; e i', 'Este medicamento está indicado para el alivio sintomático de dolores (de espalda, de cabeza, musculares, articulares, de dientes, menstruales). También alivia los dolores asociados a estados gripales, resfrío común y para bajar la fiebre.', 'Vía de administración oral.\r\nAdultos y mayores de 12 años:\r\n1 cápsula cada 6 u 8 horas. Mientras los síntomas persistan, el intervalo mínimo entre dosis puede ser de 6 horas, sin exceder la dosis máxima de 3 cápsulas de 400 mg (1200 mg/ día).\r\nTomar preferentemente después de las comidas.\r\nNo tomar por más de 5 días para el dolor o 3 para la fiebre. Si los síntomas persisten o empeoran por más de 48 / 72 horas consulte a su médico.\r\nNo usar en niños menores de 12 años.', 'Suspenda su uso y consulte a su médico inmediatamente si:\r\nExperimenta alergias y reacciones serias en la piel, como erupción en la piel y picazón, que puede acompañarse de dificultad para respirar, hinchazón de labios, lengua, garganta o cara.\r\nExperimenta empeoramiento de asma.\r\nExperimenta toxicidad gastrointestinal, sangrado o ulceración.\r\nSi usted consume 3 (tres) o más vasos diarios de bebida alcohólica consulte a su médico antes de tomar este medicamento.\r\nEste medicamento puede producir náuseas, acidez, dolor estomacal, síntomas en el tránsito intestinal. Si ocurre esto o experimenta cualquier otra reacción, consulte a su médico y suspenda su uso.', 1.00, 0, 0),
(34, 'MED-00034', 3, 'ACTRON', 263, 2, 1, '600', 3, 'Caja conteniendo 10 Cápsulas blandas', 'Cada cápsula blanda de gelatina contiene: como ingrediente activo, 400 miligramos de Ibuprofeno; e i', 'Este medicamento está indicado para el alivio sintomático de dolores (de espalda, de cabeza, musculares, articulares, de dientes, menstruales). También alivia los dolores asociados a estados gripales, resfrío común y para bajar la fiebre.', 'Vía de administración oral.\r\nAdultos y mayores de 12 años:\r\n1 cápsula cada 6 u 8 horas. Mientras los síntomas persistan, el intervalo mínimo entre dosis puede ser de 6 horas, sin exceder la dosis máxima de 3 cápsulas de 400 mg (1200 mg/ día).\r\nTomar preferentemente después de las comidas.\r\nNo tomar por más de 5 días para el dolor o 3 para la fiebre. Si los síntomas persisten o empeoran por más de 48 / 72 horas consulte a su médico.\r\nNo usar en niños menores de 12 años', 'Suspenda su uso y consulte a su médico inmediatamente si:\r\nExperimenta alergias y reacciones serias en la piel, como erupción en la piel y picazón, que puede acompañarse de dificultad para respirar, hinchazón de labios, lengua, garganta o cara.\r\nExperimenta empeoramiento de asma.\r\nExperimenta toxicidad gastrointestinal, sangrado o ulceración.\r\nSi usted consume 3 (tres) o más vasos diarios de bebida alcohólica consulte a su médico antes de tomar este medicamento.\r\nEste medicamento puede producir náuseas, acidez, dolor estomacal, síntomas en el tránsito intestinal. Si ocurre esto o experimenta cualquier otra reacción, consulte a su médico y suspenda su uso.', 3.00, 0, 0),
(35, 'MED-00035', 3, 'COLNATUR BEAUTY', 530, 23, 19, '105', 2, 'Suplemento alimenticio en polvo. 105g. Estuche con 30 sobres de 3,5 g', 'Péptidos de colágeno bioactivos VERISOL®(2,5g), extracto seco de açaí 4:1 (Euterpe oleracea Mart.), ', 'VERISOL en Colnatur Beauty es un ingrediente innovador y patentado que cuenta con estudios científicos propios para demostrar su efectividad y los beneficios para mantener la piel sana, reducir la formación de arrugas y aumentar la elasticidad y firmeza de este importante órgano.', 'Tomar diariamente 3,5 gramos (el contenido entero del sobre) disueltos en un mínimo de media taza de agua, zumo, bebidas vegetales, yogurt u otros líquidos. Envase para 30 días.', 'No apto para fenilcetonúricos.\r\n• Este producto no sustituye una dieta equilibrada.\r\n• Se aconseja no sobrepasar la dosis diaria expresamente recomendada.\r\n• El producto debe mantenerse fuera del alcance de los niños.', 9.00, 0, 0),
(36, 'MED-00036', 3, 'COLNATUR CLASSIC - SABOR NEUTRO', 530, 23, 19, '100', 11, 'Colnatur Classic Sabor Neutro: Lata x 300g.', 'Colágeno hidrolizado 100%.', 'Se encuentra indicado para todo público y en especial: Deportistas o quienes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años.', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures, té, infusiones, zumos y agua.', 'No presenta.', 270.00, 0, 0),
(37, 'MED-00037', 3, 'COLNATUR CLASSIC - SABOR FRUTOS DEL BOSQUE', 530, 23, 19, '100', 11, 'Colnatur Classic Sabor Frutos del Bosque: Lata x 315g.', 'Colágeno hidrolizado 100%.', 'Se encuentra indicado para todo público y en especial: Deportistas o quienes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años.', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures, té, infusiones, zumos y agua.', 'No presenta', 270.00, 0, 0),
(38, 'MED-00038', 3, 'COLNATUR CALSSIC - SABOR VAINILLA', 530, 23, 19, '100', 11, 'Colnatur Classic Sabor Vainilla: Lata x 315g.', 'Colágeno hidrolizado 100%.', 'Se encuentra indicado para todo público y en especial: Deportistas o quienes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures, té, infusiones, zumos y agua.', 'No presenta', 270.00, 0, 0),
(39, 'MED-00039', 3, 'COLNATUR COMPLEX - SABOR NEUTRO', 530, 23, 19, '100', 11, 'Colnatur Complex Sabor Neutro: Lata por 330 g.', 'Cada dosis contiene: Colágeno hidrolizado 100% Magnesio 150 mg/dosis Vitamina C 32 mg/dosis ácido hi', 'Se encuentra indicado para todo público y en espcial: Deportistas o quiénes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años.', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures,, té, infusiones, zumos y agua.', 'No presenta', 270.00, 0, 0),
(40, 'MED-00040', 3, 'COLNATUR COMPLEX - SABOR FRUTOS DEL BOSQUE', 530, 23, 19, '100', 11, 'Colnatur Complex Sabor Frutos del Bosque: Lata por 345 g.', 'Cada dosis contiene: Colágeno hidrolizado 100% Magnesio 150 mg/dosis Vitamina C 32 mg/dosis ácido hi', 'Se encuentra indicado para todo público y en espcial: Deportistas o quiénes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años.', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures,, té, infusiones, zumos y agua.', 'No presenta', 270.00, 0, 0),
(41, 'MED-00041', 3, 'COLNATUR COMPLEX - SABOR VAINILLA', 530, 23, 19, '100', 11, 'Colnatur Complex Sabor Vainilla Gourmet: Lata por 330 g.', 'Cada dosis contiene: Colágeno hidrolizado 100% Magnesio 150 mg/dosis Vitamina C 32 mg/dosis ácido hi', 'Se encuentra indicado para todo público y en espcial: Deportistas o quiénes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años.', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures,, té, infusiones, zumos y agua.', 'No presenta', 270.00, 0, 0),
(42, 'MED-00042', 3, 'COLNATUR SPORT - SABOR NEUTRO', 530, 23, 19, '100', 11, 'Colnatur Sport Sabor Neutro: Lata x 330 g.', 'Cada dosis contiene: Colágeno hidrolizado 100% Manganeso 0.8 mg/dosis Vitamina B2 0.6 mg/dosis Vitam', 'Se encuentra indicado para todo público y en espcial: Deportistas o quiénes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años.', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures, té, infusiones, zumos y agua.', 'No presenta', 265.00, 0, 0),
(43, 'MED-00043', 3, 'COLAGENO EXPORT - SABOR LIMÓN', 530, 23, 19, '100', 11, 'Colnatur Sport Sabor Limón: Lata x 345 g.', 'Cada dosis contiene: Colágeno hidrolizado 100% Manganeso 0.8 mg/dosis Vitamina B2 0.6 mg/dosis Vitam', 'Se encuentra indicado para todo público y en espcial: Deportistas o quiénes tienen actividad física regularmente. Cuidado de la piel, cabello y uñas. Personas mayores de 40 años.', 'Tomar diariamente 10 gramos (dosificador) disuelto en un líquido de consumo diario, leche, batidos, yogures, té, infusiones, zumos y agua.', 'No presenta', 265.00, 0, 0),
(44, 'MED-00044', 3, 'LETI AT-4 CHAMPÚ', 483, 54, 20, '250', 7, 'Frasco', 'Emoliente, reparador, protector. Nutriente. \r\nCada frasco contiene: PEG-7 glicerol cocoate, Esfingol', 'Tendencia atópica. Higiene, cuidado y protección del cuero cabelludo del adulto, del niño y del bebé (con o sin costra láctea).', 'Colocar sobre cuero cabelludo unos minutos y luego enjuagar con abundante agua. Puede utilizar diariamente según indicación médica.', 'No presenta', 110.00, 0, 0),
(45, 'MED-00045', 3, 'LETI AT-4 CREMA CORPORAL', 483, 46, 20, '200', 7, 'Tubo por 200 ml.', 'Cada frasco contiene: Alfa glucan oligosacarido, Acetato de tocoferol, Extracto de Linus, Usitatissi', 'Piel atópica', 'Aplicar sobre la piel tantas veces sea necesario.', 'No presenta', 195.00, 0, 0),
(46, 'MED-00046', 3, 'LETI AT-4 CREMA FACIAL', 483, 55, 20, '50', 7, 'Tubo por 50 ml.', 'Cada frasco contiene: Lupimus albus, Linum usitatissimum, Alfa glucan oligosacarido, C-12-20 Acid PE', 'Piel atópica, seca y sensible.', 'Aplicar uniformemente sobre cara y cuello. Utilizar diariamente, después de la higiene matinal y/o antes de salir al exterior.', 'No presenta', 155.00, 0, 0),
(47, 'MED-00047', 3, 'LETI AT-4 CREMA INTENSIVA', 483, 46, 20, '100', 7, 'Tubo por 100 ml.', 'Cada frasco contiene: Alfa glucan oligosacárido, Acrilamida/copolimero de sodio acriloildimetiltaura', 'Coadyuvante del tratamiento de piel atópica con sequedad severa y especialmente indicada en el brote de atopia.', 'Aplicar diariamente sobre la piel tantas veces como sea necesario.', '', 170.00, 0, 0),
(48, 'MED-00048', 3, 'LETI AT-4 GEL DE BAÑO', 531, 12, 20, '200', 7, 'Frasco por 200 ml.', 'Cada frasco contiene: Polidocanol, Bisabolol, PEG-7 Glyceyl Cocoate, Decyl Glucoside, Alfa-Glucan ol', 'Piel atópica, sencible y seca.', 'Colocar sobre la piel, usando agua templada inferior a 32 grados centígrados, el baño no debe prolongarse mas de 10 minutos, el secado debe ser suave y sin producir fricción.', 'No presenta', 110.00, 0, 0),
(49, 'MED-00049', 3, 'LETI AT-4 LECHE CORPORAL', 483, 54, 20, '250', 7, 'Frasco por 250 ml.', 'Cada frasco contiene: Alfa glucan oligosacárido, Linum usitatissimum, Acetato tocoferol, Fosfolípido', 'Coadyuvante dermatitis atópica. Piel atópica.', 'Aplicar diariamente en la piel, preferente- mente después del baño, realizando un suave masaje hasta su total absorción.', 'No presenta', 170.00, 0, 0),
(50, 'MED-00050', 3, 'LETIXER S CREMA', 532, 8, 20, '200', 7, 'Crema tubo por 200 ml.', 'NMF Like, Pantenol, Fosfolípidos, Esfingolípidos, Glicerina, di glicerina, Cera de Olea Europea, Ace', 'Piel seca, crema corporal de alto poder emoliente e hidratante. Indicada en casos de Xerosis cutánea.', 'Aplicar diariamente sobre la piel limpia y seca cuantas veces sea necesario.', 'No presenta', 170.00, 0, 0),
(51, 'MED-00051', 3, 'LETIXER D CREMA', 532, 8, 20, '200', 7, 'Tubo por 200 ml.', 'NMF Like, Pantenol, Fosfolípidos, Esfingolípidos, Glicerina, di glicerina, Cera de Olea Europea, Ace', 'Piel muy seca ñ descamativa, crema corporal de alto poder emoliente, hidratante y alisante. Normaliza el proceso natural de descamación de la piel. Indicada en caso de sequedad severa.', 'Aplicar diariamente sobre la piel limpia y seca cuantas veces sea necesario.', 'No presenta', 170.00, 0, 0),
(52, 'MED-00052', 3, 'LETIXER Q CREMA', 532, 8, 20, '100', 7, 'Tubo por 100 ml.', 'NMF Like, Pantenol, Fosfolípidos, Esfingolípidos, Glicerina, di glicerina, Cera de Olea Europea, Ace', 'Hiperqueratosis, zonas con extrema sequedad. Crema Queratoreguladora de alto poder emoliente, hidratante y suavizante. Elimina el engrosamiento cutáneo y normaliza el proceso natural de descamación de la piel. Repara las grietas. Indicada para el cuidado de zonas propensas a las durezas producto de la sequedad extrema (talones, codos y rodillas).', 'Aplicar diariamente sobre la piel limpia y seca cuantas veces sea necesario.', 'No presenta', 170.00, 0, 0),
(53, 'MED-00053', 3, 'ABRAMAX 500', 111, 49, 21, '500', 3, 'Estuche de 14 comprmidos recubiertos', 'Claritromicina 500 mg.\r\nExcipientes c.s', 'Abramax está indicado en el tratamiento de infecciones de leves a moderadas.\r\n\r\nAdultos:\r\n– Faringitis y amigdalitis producidas por Streptococcus pyogenes.\r\n– Sinusitis maxilar aguda producida por Haemophilus influenzae, Moraxella catarrhalis o Streptococcus pneumoniae.\r\n– Bronquitis aguda como complicación de una bronquitis crónica producida por Haemophilus influenzae, Moraxella catarrhalis o Streptococcus pneumoniae.\r\n– Neumonía producida por Mycoplasma pneumoniae, Streptococcus pneumoniae, Chlamydia pneumoniae (TWAR).\r\n– Infecciones de la piel no complicadas producidas por Staphylococcus aureus o Streptococcus pyogenes (los abscesos requieren, por lo general, una solución quirúrgica).\r\n– Infecciones micobacterianas diseminadas debidas a Mycobacterium avium o Mycobacterium intracellulare.\r\n– En combinación con omeprazol o ranitidina y amoxicilina o citrato de bismuto está indicado para el tratamiento de pacientes con úlcera duodenal activa asociada a infección por Helicobacter pylori. La erradicación del Helicobacter pylori ha demostrado reducir las recidivas de la úlcera duodenal. En casos de resistencia demostrada se recomienda el empleo de terapias alternativas.', '', 'Hipersensibilidad a los antibióticos macrólidos, anomalías cardiacas preexistentes, disturbios electrolíticos, pacientes que reciben terapéutica concomitante con terfenadina, cisapride y pimozida.', 20.00, 0, 0),
(54, 'MED-00054', 3, 'ACNOTIN 10', 533, 2, 22, '10', 3, 'Estuche de 30 comprimidos recubiertos', 'Excipientes: Cera blanca de abejas, butilhidroxianisol, edetato disódico, aceite vegetal parcialment', 'Tratamiento del acné en sus formas severas en aquellos pacientes que no responden al tratamiento convencional.', 'El médico debe indicar la posología y el tiempo de tratamiento apropiados a su caso particular. No obstante la dosis usual recomendada en el adulto y adolescentes es de 0,5 a 1 mg /Kg /día, dividida en 2 tomas, durante 15 a 20 semanas. Dosis bajas pueden administrarse una vez al día.\r\nSiga exactamente las instrucciones de uso.\r\nNo administre dosis mayores o con mayor frecuencia que lo indicado por el médico.\r\nTome el medicamento con un vaso lleno de agua, junto con los alimentos.', 'EMBARAZO Y LACTANCIA\r\n– No se debe tomar isotretinoína si se está embarazada o se planea estarlo.\r\n– No se debe tomar este medicamento durante la lactancia\r\nExiste un riesgo extremadamente alto de deformaciones en el feto si el embarazo ocurre mientras se está tomando isotretinoína en cualquier dosis, incluso por períodos breves.\r\n\r\nIsotretinoína está contraindicada también en insuficiencia renal y hepática; hipervitaminosis A; pacientes con valores excesivamente elevados de lípidos sanguíneos y aquellos hipersensibles a la isotretinoína o a cualquier componente de la formulación.\r\nEste producto esta contraindicado en pacientes con acné vulgaris leve a moderado, cuya afección puede ser controlada con medicamentos o productos tópicos para el acné o antibióticos sistémicos.', 7.50, 0, 0);
INSERT INTO `medicamento` (`idmedicamento`, `codmedicamento`, `laboratorio`, `nombre`, `idpactivo`, `idforma`, `idacciont`, `concentracion`, `idunidad`, `presentacion`, `formula`, `indicaciones`, `posologia`, `contradicciones`, `preciounitario`, `cantidadminima`, `stock`) VALUES
(55, 'MED-00055', 3, 'ACNOTIN 20', 533, 2, 22, '20', 3, 'Estuche 30 comprimidos recubiertos.', 'Excipientes: Cera blanca de abejas, butilhidroxianisol, edetato disódico, aceite vegetal parcialment', 'Tratamiento del acné en sus formas severas en aquellos pacientes que no responden al tratamiento convencional.', 'El médico debe indicar la posología y el tiempo de tratamiento apropiados a su caso particular. No obstante la dosis usual recomendada en el adulto y adolescentes es de 0,5 a 1 mg /Kg /día, dividida en 2 tomas, durante 15 a 20 semanas. Dosis bajas pueden administrarse una vez al día.\r\nSiga exactamente las instrucciones de uso.\r\nNo administre dosis mayores o con mayor frecuencia que lo indicado por el médico.\r\nTome el medicamento con un vaso lleno de agua, junto con los alimentos.', 'EMBARAZO Y LACTANCIA\r\n– No se debe tomar isotretinoína si se está embarazada o se planea estarlo.\r\n– No se debe tomar este medicamento durante la lactancia\r\nExiste un riesgo extremadamente alto de deformaciones en el feto si el embarazo ocurre mientras se está tomando isotretinoína en cualquier dosis, incluso por períodos breves.\r\n\r\nIsotretinoína está contraindicada también en insuficiencia renal y hepática; hipervitaminosis A; pacientes con valores excesivamente elevados de lípidos sanguíneos y aquellos hipersensibles a la isotretinoína o a cualquier componente de la formulación.\r\nEste producto esta contraindicado en pacientes con acné vulgaris leve a moderado, cuya afección puede ser controlada con medicamentos o productos tópicos para el acné o antibióticos sistémicos.', 14.00, 0, 0),
(56, 'MED-00056', 3, 'ANAFLEX MUJER', 381, 49, 23, '500', 3, 'Estuche conteniendo 30 comprimidos recubiertos', 'Paracetamol 500 mg.\r\nDiclofenaco sódico 50 mg.\r\nCafeína 30 mg.\r\nExcipientes: Punzó 4R, Otros c.s.', 'Indicado en dismenorrea, dolor de intensidad leve a moderado, cefalea asociada a migrañas y dolor muscular.', 'La dosis se adaptará de acuerdo a criterio médico, como posología media de orientación se aconseja:\r\nSíndrome premenstrual: Dosis inicial 2 comprimidos y luego continuar con 1 comprimido cada 8 a 12 horas.\r\nOtras indicaciones: Adultos y niñas mayores de 12 años 1 comprimido cada 8 a 12 horas.', 'Antecedentes de hipersensibilidad a los principios activos, pacientes con trastornos funcionales renales o hepáticos, pacientes asmáticos, embarazo y lactancia.', 3.50, 0, 0),
(57, 'MED-00057', 3, 'AVATAR', 534, 56, 24, '20', 3, 'Estuche conteniendo 30 comprimidos recubiertos', 'Cada comprimido recubierto contiene: Paroxetina (clorhidrato) 20 mg.', 'La dosis se adaptará según criterio médico y el cuadro clínico del paciente.', 'La dosis recomendada es de 20 mg al día, pudiendo incrementarse de 10 a 20 mg La dosis se adaptará según criterio médico y el cuadro clínico del paciente. hasta alcanzar 50 mg por día.', 'Hipersensibilidad al principio activo, pacientes con antecedentes de manía, fase aguda de infarto de miocardio, insuficiencia renal severa, embarazo, lactancia y niños menores de 18 años de edad.', 12.00, 0, 0),
(58, 'MED-00058', 3, 'AXION', 535, 49, 25, '20', 3, 'Estuche conteniendo 4 comprimidos recubiertos', 'Tadalafilo 20 mg.\r\nExcipientes c.s.', 'Tratamiento de la disfunción eréctil masculina.', 'La dosis se adaptará de acuerdo a criterio médico, como posología media de orientación se aconseja:\r\n1 comprimido (20 mg), 30 minutos antes de la actividad sexual e independiente de la ingesta de alimentos.\r\nLa frecuencia máxima de dosificación es de un comprimido de 20 mg al día.\r\nDosis máxima: 20 mg por día.\r\n\r\n ', 'Hipersensibilidad al fármaco, en aquellos que se encuentren bajo tratamiento con cualquier forma de nitrato orgánico y en menores de 18 años.', 10.00, 0, 0),
(59, 'MED-00059', 3, 'BACTICEL', 144, 42, 26, '40/5 200/5', 5, 'Estuche contenido 1 frasco con 100 ml de suspencion', 'Cada cucharadita (5 ml) de suspensión contiene:\r\nTrimetoprima 40 mg.\r\nSulfametoxazol 200 mg.\r\nExcipi', 'Infecciones del tracto respiratorio, infecciones del tracto digestivo, infecciones del tracto urinario, otitis media aguda, otitis recidivante, neumonía por Pneumocystis carinii, gastroenteritis aguda, profilaxis de las infecciones urinarias recurrentes.', 'La dosis se adaptará de acuerdo al criterio médico, como posología media de orientación se aconseja:\r\nSuspensión: En general la dosis recomendada para niños, es de 8 mg de Trimetoprima y 40 mg de Sulfametoxazol por kg de peso por día, repartidos en 2 tomas.', 'Hipersensibilidad a los componentes del medicamento. Lactancia, niños menores de 2 meses.', 45.00, 0, 0),
(60, 'MED-00060', 3, 'BACTICEL FORTE', 144, 49, 26, '160 - 800', 3, 'Estuche 10 comprimidos recubiertos ranurados', 'Trimetoprima 160 mg.\r\n\r\nSulfametoxazol 800 mg.\r\n\r\nExcipientes c.s.', 'Infecciones del tracto respiratorio, infecciones del tracto digestivo, infecciones del tracto urinario, otitis media aguda, otitis recidivante, neumonía por Pneumocystis carinii, gastroenteritis aguda, profilaxis de las infecciones urinarias recurrentes.', 'Comprimidos recubiertos.\r\nAdultos y niños mayores de 12 años: 1 comprimido cada 12 horas.\r\nNiños de 6 a 12 años: 1/2 comprimido cada 12 horas\r\nEn infecciones agudas, administrar Bacticel o Bacticel forte en forma continuada durante 5 días como mínimo y hasta la desaparición de los síntomas.', 'Hipersensibilidad a los componentes del medicamento. Lactancia, niños menores de 2 meses.', 5.00, 0, 0),
(61, 'MED-00061', 3, 'BACTICEL FORTE SUSPENSION', 144, 42, 26, '80/5 400/5', 5, 'Estuche conteniendo 1 frasco con 100 ml de suspension', 'Cada cucharadita (5 ml) de suspensión contiene:\r\nTrimetoprima 80 mg.\r\nSulfametoxazol 400 mg.\r\nExcipi', 'Infecciones del tracto respiratorio, infecciones del tracto digestivo, infecciones del tracto urinario, otitis media aguda, otitis recidivante, neumonía por Pneumocystis carinii, gastroenteritis aguda, profilaxis de las infecciones urinarias recurrentes.', 'La dosis se adaptará de acuerdo al criterio médico, como posología media de orientación se aconseja:\r\nSuspensión:\r\nEn general la dosis recomendada para niños, es de 8 mg de Trimetoprima y 40 mg de Sulfametoxazol por kg de peso por día, repartidos en 2 tomas', 'Hipersensibilidad a los componentes del medicamento. Lactancia, niños menores de 2 meses.', 70.00, 0, 0),
(62, 'MED-00062', 3, 'BACTIFREN 500', 304, 49, 3, '500', 7, 'Estuche 7 comprimidos recubiertos', 'Levofloxacina (como hemihidrato) 500 mg.\r\nExcipientes: Rojo punzó 4R, Otros c.s.', 'BACTIFREN es un antibacteriano quinolónico indicado en el tratamiento de las siguientes infecciones cuando son debidas a microorganismos sensibles a la Levofloxacina:\r\n– Infecciones del tracto respiratorio superior e inferior, incluyendo sinusitis, exacerbación aguda de bronquitis crónica, neumonía de la comunidad y hospitalaria.\r\n– Infecciones del tracto urinario, incluyendo pielonefritis aguda.\r\n– Infecciones de la piel y partes blandas como impétigo, abscesos, forunculosis, celulitis y erisipela.\r\n– Osteomielitis. Artritis séptica.\r\n– Espectro antibacteriano: BACTIFREN® (Levofloxacina) es un antibiótico de amplio espectro, activo contra los siguientes organismos:\r\nAerobios gram-positivos: Enterococcus (Streptococcus) faecalis, Staphylococcus aureus, Staphylococcus epidermidis, Staphylococcus saprophyticus,\r\nStreptococcus agalactiae, Streptococcus pneumoniae (incluyendo S. pneumoniae penicilinorresistente), Streptococcus pyogenes.\r\nAerobios gram-negativos: Citrobacter freundii, Enterobacter cloacae, Escherichia coli, Haemophilus influenzae, Haemophilus parainfluenzae, Klebsiella oxytoca, Klebsiella pneumoniae, Legionella pneumophila, Moraxella catarrhalis, Proteus mirabilis, Pseudomonas aeruginosa. Otros microorganismos: Chlamydia pneumoniae, Mycoplasma pneumoniae', 'Los comprimidos de BACTIFREN se administran uno por día. La dosis depende del tipo, la gravedad de la infección y de la sensibilidad del probable agente patógeno causal:\r\nDuración del tratamiento: La duración varía de acuerdo a la evolución de la enfermedad, con una duración máxima de tratamiento de 14 días.\r\nForma de administración: Los comprimidos recubiertos deben tragarse sin masticar, con una cantidad de líquido suficiente. Pueden tomarse durante o entre las comidas. Los comprimidos recubiertos deben tomarse como mínimo dos horas antes o después de la administración de sales de hierro, antiácidos o sucralfato, ya que podría reducirse su absorción.', 'Hipersensibilidad al principio activo, otras quinolonas o a cualquiera de los componentes de formulación.\r\nEpilepsia.\r\nAntecedentes de complicaciones tendinosas vinculadas con la administración de fluoroquinolonas.\r\nEmbarazo.\r\nLactancia.\r\nNiños y adolescentes en desarrollo.', 20.00, 0, 0),
(63, 'MED-00063', 3, 'BACTIFREN 750', 304, 49, 3, '750', 3, 'Estuche 5 comprimidos recubiertos', 'Levofloxacina (como hemihidrato) 750 mg.\r\nExcipientes: Amarillo ocaso, Otros c.s.', 'BACTIFREN es un antibacteriano quinolónico indicado en el tratamiento de las siguientes infecciones cuando son debidas a microorganismos sensibles a la Levofloxacina:\r\n– Infecciones del tracto respiratorio superior e inferior, incluyendo sinusitis, exacerbación aguda de bronquitis crónica, neumonía de la comunidad y hospitalaria.\r\n– Infecciones del tracto urinario, incluyendo pielonefritis aguda.\r\n– Infecciones de la piel y partes blandas como impétigo, abscesos, forunculosis, celulitis y erisipela.\r\n– Osteomielitis. Artritis séptica.\r\n– Espectro antibacteriano: BACTIFREN® (Levofloxacina) es un antibiótico de amplio espectro, activo contra los siguientes organismos:\r\nAerobios gram-positivos: Enterococcus (Streptococcus) faecalis, Staphylococcus aureus, Staphylococcus epidermidis, Staphylococcus saprophyticus,\r\nStreptococcus agalactiae, Streptococcus pneumoniae (incluyendo S. pneumoniae penicilinorresistente), Streptococcus pyogenes.\r\nAerobios gram-negativos: Citrobacter freundii, Enterobacter cloacae, Escherichia coli, Haemophilus influenzae, Haemophilus parainfluenzae, Klebsiella\r\noxytoca, Klebsiella pneumoniae, Legionella pneumophila, Moraxella catarrhalis, Proteus mirabilis, Pseudomonas aeruginosa.\r\nOtros microorganismos: Chlamydia pneumoniae, Mycoplasma pneumoniae.', 'Los comprimidos de BACTIFREN se administran uno por día. La dosis depende del tipo, la gravedad de la infección y de la sensibilidad del probable agente patógeno causal:\r\n\r\nDuración del tratamiento: La duración varía de acuerdo a la evolución de la enfermedad, con una duración máxima de tratamiento de 14 días.\r\n\r\nForma de administración: Los comprimidos recubiertos deben tragarse sin masticar, con una cantidad de líquido suficiente. Pueden tomarse durante o entre las comidas. Los comprimidos recubiertos deben tomarse como mínimo dos horas antes o después de la administración de sales de hierro, antiácidos o sucralfato, ya que podría reducirse su absorción.', 'Hipersensibilidad al principio activo, otras quinolonas o a cualquiera de los componentes de formulación.\r\nEpilepsia.\r\nAntecedentes de complicaciones tendinosas vinculadas con la administración de fluoroquinolonas.\r\nEmbarazo.\r\nLactancia.\r\nNiños y adolescentes en desarrollo.', 25.00, 0, 0),
(64, 'MED-00064', 3, 'BAGOCILETAS - NARANJA', 536, 57, 27, '6', 3, 'Dispenser de 100 pastillas sabor naranja', 'Cada pastilla sabor naranja mentol contiene:\r\nBenzocaína 6 mg\r\nExcipientes: Glucosa, sacarosa, ácido', 'Tratamiento sintomático de procesos inflamatorios de la mucosa orofaríngea.', 'Como posología media de orientación se aconseja:\r\nAdultos y niños mayores de 5 años: Disolver en la boca 1 pastilla cada 2 horas, sin masticar ni tragar.\r\nDosis máxima: 12 pastillas por día. No exceder la dosis recomendada.\r\nNiños menores de 5 años consultar a su médico.', 'No presenta', 9.00, 0, 0),
(65, 'MED-00065', 3, 'BAGOCILETAS - MIEL LIMÓN', 536, 57, 27, '6', 3, 'Dispenser de 100 pastillas sabor miel y limon', 'Cada pastilla sabor miel y limón contiene:\r\nBenzocaína 6 mg\r\nExcipientes: Glucosa, sacarosa, ácido', 'Tratamiento sintomático de procesos inflamatorios de la mucosa orofaríngea.', 'Como posología media de orientación se aconseja:\r\nAdultos y niños mayores de 5 años: Disolver en la boca 1 pastilla cada 2 horas, sin masticar ni tragar.\r\nDosis máxima: 12 pastillas por día. No exceder la dosis recomendada.\r\nNiños menores de 5 años consultar a su médico.', 'No presenta', 10.00, 0, 0),
(66, 'MED-00066', 3, 'BAGOCILETAS - FRAMBUESA', 536, 57, 27, '6', 3, 'Dispenser de 100 pastillas sabor frambuesa', 'Cada pastilla sabor frambuesa mentol contiene:\r\nBenzocaína 6 mg\r\nExcipientes: Glucosa, sacarosa, áci', 'Tratamiento sintomático de procesos inflamatorios de la mucosa orofaríngea.', 'Como posología media de orientación se aconseja:\r\nAdultos y niños mayores de 5 años: Disolver en la boca 1 pastilla cada 2 horas, sin masticar ni tragar.\r\nDosis máxima: 12 pastillas por día. No exceder la dosis recomendada.\r\nNiños menores de 5 años consultar a su médico.', 'No presenta', 10.00, 0, 0),
(67, 'MED-00067', 3, 'BAGOCILETAS PLUS - NARANJA', 536, 57, 28, '6', 3, 'Dispenser de 100 pastillas sabor naranja', 'Cada pastilla sabor naranja contiene:\r\nBenzocaína 6 mg\r\nTirotricina 1 mg\r\nExcipientes: Glucosa, saca', 'Tratamiento sintomático de procesos inflamatorios bucofaríngeos tales como: Dolor de garganta, faringitis y aftas.', 'Como posología media de orientación se aconseja:\r\nAdultos y niños mayores de 12 años: Disolver en la boca 1 pastilla cada 2 horas, sin masticar ni tragar.\r\nDosis máxima: 12 pastillas por día. No exceder la dosis recomendada.\r\nNiños menores de 12 años consultar a su médico.', 'No presenta', 10.00, 0, 0),
(68, 'MED-00068', 3, 'BAGOCILETAS PLUS - MIEL LIMÓN', 536, 57, 28, '6', 3, 'Dispenser de 100 pastillas sabor miel y limón', 'Cada pastilla sabor miel y linón contiene:\r\nBenzocaína 6 mg\r\nTirotricina 1 mg\r\nExcipientes: Glucosa,', 'Tratamiento sintomático de procesos inflamatorios bucofaríngeos tales como: Dolor de garganta, faringitis y aftas.', 'Como posología media de orientación se aconseja:\r\nAdultos y niños mayores de 12 años: Disolver en la boca 1 pastilla cada 2 horas, sin masticar ni tragar.\r\nDosis máxima: 12 pastillas por día. No exceder la dosis recomendada.\r\nNiños menores de 12 años consultar a su médico.', 'No presenta', 10.00, 0, 0),
(69, 'MED-00069', 3, 'BAGOCILETAS PLUS - FRAMBUESA', 536, 57, 28, '6', 3, 'Dispenser de 100 pastillas sabor frambuesa', 'Cada pastilla sabor frambuesa contiene:\r\nBenzocaína 6 mg\r\nTirotricina 1 mg\r\nExcipientes: Glucosa, sa', 'Tratamiento sintomático de procesos inflamatorios bucofaríngeos tales como: Dolor de garganta, faringitis y aftas.', 'Como posología media de orientación se aconseja:\r\nAdultos y niños mayores de 12 años: Disolver en la boca 1 pastilla cada 2 horas, sin masticar ni tragar.\r\nDosis máxima: 12 pastillas por día. No exceder la dosis recomendada.\r\nNiños menores de 12 años consultar a su médico.', 'No presenta', 10.00, 0, 0),
(70, 'MED-00070', 3, 'BAGOMICINA 100', 343, 49, 3, '100', 3, 'Estuche conteniendo 12 comprimidos recubiertos', 'Bagomicina 100 mg.\r\nCada comprimido recubierto contiene: Minociclina (como Minociclina clorhidrato)1', 'Bagomicina se usa para el tratamiento del acné vulgaris; rosácea; infecciones de piel, tejidos blandos, del tracto respiratorio y otras infecciones ocasionadas por gérmenes sensibles.', 'Según prescripción médica.\r\nSiga exactamente las instrucciones de uso. No administre dosis mayores o con mayor frecuencia que lo indicado por el médico. Tome cada dosis por vía oral con un vaso lleno de agua (250 ml), de preferencia con el estómago vacío. No tome suplementos de hierro, complejos de multivitaminas, suplementos de calcio, antiácidos o laxantes en un período de 2 horas después de tomar Minociclina. Estos medicamentos pueden reducir la eficiencia de este producto. Complete el tratamiento que le hayan prescrito aunque los síntomas desaparezcan después de algunos días. Sus síntomas pueden comenzar a mejorar antes de terminar el tratamiento completo de la infección. Deseche este medicamento si ha expirado o cuando ya no lo necesite.\r\n\r\nNo lo tome después de la fecha de expiración impresa en el blister y el estuche. Minociclina vencida puede causar un síndrome peligroso resultando en una lesión en los riñones.', 'No usar en:\r\nEmbarazo, madres que amamantan, niños menores de 8 años, salvo expresa indicación médica. Pacientes alérgicos a Minociclina u otras tetraciclinas.', 9.10, 0, 0),
(71, 'MED-00071', 3, 'BAGOMICINA 50', 343, 49, 3, '50', 3, 'Estuche conteniendo 12 comprimidos recubiertos', 'Cada comprimido recubierto contiene:\r\nMinociclina (como Minociclina clorhidrato) 50 mg\r\nExcipientes ', 'Bagomicina se usa para el tratamiento del acné vulgaris; rosácea; infecciones de piel, tejidos blandos, del tracto respiratorio y otras infecciones ocasionadas por gérmenes sensibles.', 'Según prescripción médica.\r\nSiga exactamente las instrucciones de uso. No administre dosis mayores o con mayor frecuencia que lo indicado por el médico. Tome cada dosis por vía oral con un vaso lleno de agua (250 ml), de preferencia con el estómago vacío. No tome suplementos de hierro, complejos de multivitaminas, suplementos de calcio, antiácidos o laxantes en un período de 2 horas después de tomar Minociclina. Estos medicamentos pueden reducir la eficiencia de este producto. Complete el tratamiento que le hayan prescrito aunque los síntomas desaparezcan después de algunos días. Sus síntomas pueden comenzar a mejorar antes de terminar el tratamiento completo de la infección. Deseche este medicamento si ha expirado o cuando ya no lo necesite.\r\n\r\nNo lo tome después de la fecha de expiración impresa en el blister y el estuche. Minociclina vencida puede causar un síndrome peligroso resultando en una lesión en los riñones.', 'No usar en:\r\nEmbarazo, madres que amamantan, niños menores de 8 años, salvo expresa indicación médica. Pacientes alérgicos a Minociclina u otras tetraciclinas.', 5.10, 0, 0),
(72, 'MED-00072', 3, 'BAGOVITAL - DIGEST', 537, 23, 29, '2', 2, 'Estuche de 10 sobres', 'Cada sobre con 2 g de polvo contiene:\r\nLactobacillus rhamnosus 1.32 x 109 UFC\r\nBifidobacterium infan', 'Bagovital Digest está indicado en la reconstitución de la flora intestinal.\r\nDiarreas infecciosas (virales, bacterianas).\r\nPrevención y/o manejo de diarreas por tratamiento antibiótico.\r\nPrevención de diarreas ocasionales inespecíficas.\r\nEquilibrio del pH intestinal.\r\nReducción de cólicos y dolor abdominal.', 'La dosis se adecuará al criterio médico y al cuadro clínico del paciente.\r\nComo posología media de orientación se aconseja:\r\nAdultos y niños: Un sobre 1 ó 2 veces al día.\r\n\r\nModo de empleo:\r\nAdministrar el contenido de un sobre disuelto en 50 ml de agua u otro líquido a temperatura ambiente.\r\nEs necesario separar la administración de Bagovital Digest por lo menos 2 horas de la administración de antibióticos.', 'Hipersensibilidad a cualquiera de los componentes.', 12.00, 0, 0),
(73, 'MED-00073', 3, 'BAGOVITAL - INMUNE', 537, 23, 29, '2', 2, 'Estuche de 10 sobres', 'Cada sobre con 1.5 g de polvo contiene:\r\nProbióticos\r\nLactobacillus acidophilus Rosell-52\r\nBifidobac', 'Bagovital Inmune está indicado en la reconstitución de la flora intestinal.\r\nEstimulación del sistema inmune.\r\nDiarreas infecciosas (virales, bacterianas).\r\nPrevención y/o manejo de diarreas por tratamiento antibiótico.\r\nPrevención de diarreas ocasionales inespecíficas.\r\nEquilibrio del pH intestinal.\r\nReducción de cólicos y dolor abdominal.', 'La dosis se adecuará al criterio médico y al cuadro clínico del paciente.\r\nComo posología media de orientación se aconseja:\r\nAdultos y niños: Un sobre 1 ó 2 veces al día\r\nModo de empleo\r\nAdministrar el contenido de un sobre disuelto en 50 ml de agua u otro líquido a temperatura ambiente.\r\nEs necesario separar la administración de Bagovital Inmune® por lo menos 2 horas de la administración de antibióticos.', 'Hipersensibilidad a cualquiera de los componentes.', 12.00, 0, 0),
(74, 'MED-00074', 3, 'BATADINA 200', 257, 49, 30, '200', 3, 'Estuche de 30 comprimidos recubiertos', 'Cada comprimido recubierto contiene:\r\nHidroxicloroquina (como sulfato) 200 mg.\r\nExcipientes c.s.', 'Inmunomodulador.\r\nPaludismo causado por Plasmodium vivax, Plasmodium ovale y cepas sensibles a Plasmodium falciparum.\r\nTratamiento de artritis reumatoidea.\r\nArtritis crónica juvenil.\r\nLupus eritematoso sistémico y discoide.', 'La dosis se adaptará a criterio médico, como posología media de orientación se aconseja:\r\nTratamiento como Inmunomodulador:\r\nDosis Adultos: 400 mg cada 12 horas.\r\nDosis Pediátrica: Usual: 13 mg/kg peso/día, fraccionada cada 12 horas.\r\nTratamiento de la malaria en los ataques agudos:\r\nAdultos y adolescentes: La dosis inicial recomendada es de 800 mg, seguida de 400 mg cada 6-8 horas, segundo y tercer día 400 mg una vez al día.\r\nNiños: la dosis recomendada es de 13 mg/kg, seguida de 6,5 mg/kg cada 6 horas, segundo y tercer día 6,5 mg/kg peso día.\r\nTratamiento de la artritis reumatoide y el lupus eritematoso discoide:\r\nDosis Adultos: 5-7 mg/kg peso/día, fraccionada cada 12 horas.', 'Hipersensibilidad conocida al medicamento o a compuestos relacionados a la 4-amino-quinoleína.\r\nMaculopatía ocular preexistente.\r\nEmbarazo y lactancia.', 5.50, 30, 0),
(75, 'MED-00075', 3, 'BATADINA 400', 257, 49, 30, '200', 3, 'Estuche de 30 comprimidos recubiertos', 'Cada comprimido recubierto contiene:\r\nHidroxicloroquina (como sulfato) 200 mg.\r\nExcipientes c.s.', 'Inmunomodulador.\r\nPaludismo causado por Plasmodium vivax, Plasmodium ovale y cepas sensibles a Plasmodium falciparum.\r\nTratamiento de artritis reumatoidea.\r\nArtritis crónica juvenil.\r\nLupus eritematoso sistémico y discoide.', 'La dosis se adaptará a criterio médico, como posología media de orientación se aconseja:\r\nTratamiento como Inmunomodulador:\r\nDosis Adultos: 400 mg cada 12 horas.\r\nDosis Pediátrica: Usual: 13 mg/kg peso/día, fraccionada cada 12 horas.\r\nTratamiento de la malaria en los ataques agudos:\r\nAdultos y adolescentes: La dosis inicial recomendada es de 800 mg, seguida de 400 mg cada 6-8 horas, segundo y tercer día 400 mg una vez al día.\r\nNiños: la dosis recomendada es de 13 mg/kg, seguida de 6,5 mg/kg cada 6 horas, segundo y tercer día 6,5 mg/kg peso día.\r\nTratamiento de la artritis reumatoide y el lupus eritematoso discoide:\r\nDosis Adultos: 5-7 mg/kg peso/día, fraccionada cada 12 horas.', 'Hipersensibilidad conocida al medicamento o a compuestos relacionados a la 4-amino-quinoleína.\r\nMaculopatía ocular preexistente.\r\nEmbarazo y lactancia.', 5.50, 30, 0),
(76, 'MED-00076', 3, 'BIL 13', 538, 49, 31, '100', 3, 'Dispenser Farmacias 150 comprimidos recubiertos. Estuche 30 comprimidos recubiertos', 'Cada comprimido recubierto contiene:\r\nOrotato de colina 100 mg.\r\nÁcido dehidrocólico 100 mg.\r\nÁcido ', 'Dispepsias.', 'La dosis se adaptará al criterio médico, como posología media de orientación se aconseja:\r\nEn dispepsias hepatobiliares:\r\n1-2 comprimidos, antes de las comidas.\r\nEn estreñimiento crónico:\r\n2 comprimidos por la mañana, en ayunas.\r\nDosis máxima en 24 horas: 4 comprimidos por día.', 'A las dosis terapéuticas recomendadas, no presenta.', 3.50, 75, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pactivo`
--

CREATE TABLE `pactivo` (
  `id` int(11) NOT NULL,
  `medicamento` varchar(100) NOT NULL,
  `uso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pactivo`
--

INSERT INTO `pactivo` (`id`, `medicamento`, `uso`) VALUES
(1, 'ABACAVIR', 2),
(2, 'ABIRATERONA ACETATO', 1),
(3, 'ACEITE MINERAL', 2),
(4, 'ACEITE VITAMINADO (ALIMENTO)', 2),
(5, 'ACETAZOLAMIDA', 2),
(6, 'ACETIL CISTEINA', 2),
(7, 'ACICLOVIR', 2),
(8, 'ÁCIDO ACÉTICO (ÁCIDO TRICLOROACÉTICO)', 2),
(9, 'ÁCIDO ACETIL SALICÍLICO', 2),
(10, 'ÁCIDO ALENDRÓNICO (ALENDRONATO)', 2),
(11, 'ÁCIDO AMINOCAPRÓICO', 2),
(12, 'ÁCIDO ASCORBICO (VITAMINA C)', 2),
(13, 'ÁCIDO ASCÓRBICO (VITAMINA C)', 2),
(14, 'ÁCIDO FÓLICO', 2),
(15, 'ÁCIDO NALIDÍXICO', 2),
(16, 'ACIDO P-AMINOSALICILICO', 2),
(17, 'ÁCIDO SALICÍLICO', 2),
(18, 'ÁCIDO TRANEXÁMICO', 1),
(19, 'ÁCIDO URSODEOXICÓLICO', 2),
(20, 'ÁCIDO VALPRÓICO Ó VALPROATO SÓDICO', 2),
(21, 'ÁCIDO ZOLEDRÓNICO', 1),
(22, 'ADENOSINA', 2),
(23, 'AGENTES CON GELATINA', 2),
(24, 'AGUA PARA INYECCIÓN', 2),
(25, 'ALBENDAZOL', 2),
(26, 'ALBÚMINA HUMANA', 2),
(27, 'ALCOHOL ETÍLICO (ETANOL)', 2),
(28, 'ALOPURINOL', 2),
(29, 'ALPRAZOLAM', 2),
(30, 'AMFOTERICINA B', 2),
(31, 'AMIKACINA', 2),
(32, 'AMINOÁCIDOS', 2),
(33, 'AMINOFILINA', 2),
(34, 'AMIODARONA (CLORHIDRATO)', 2),
(35, 'AMITRIPTILINA', 2),
(36, 'AMLODIPINA', 2),
(37, 'AMOXICILINA', 2),
(38, 'AMOXICILINA   +   INHIBIDOR   BETALACTA- MASA', 2),
(39, 'AMPICILINA', 2),
(40, 'ANASTROZOL', 2),
(41, 'ANTIGRIPAL  (PARACETAMOL  + ANTIHISTA- MÍNICO  +  VASOCONSTRICTOR  CON  O  SIN CAFEÍNA)', 2),
(42, 'ANTITÓXINA TETÁNICA', 2),
(43, 'ARIPIPRAZOL', 1),
(44, 'ARTEMETER + LUMEFANTRINA', 2),
(45, 'ARTESUNATO', 2),
(46, 'ARTESUNATO + MEFLOQUINA', 2),
(47, 'ASPARAGINASA', 2),
(48, 'ATAZANAVIR (SULFATO)', 1),
(49, 'ATAZANAVIR + RITONAVIR', 2),
(50, 'ATENOLOL', 2),
(51, 'ATORVASTATINA', 2),
(52, 'ATRACURIO BESILATO', 2),
(53, 'ATROPINA SULFATO', 2),
(54, 'AZATIOPRINA', 2),
(55, 'AZITROMICINA', 2),
(56, 'BACITRACINA + NEOMICINA SULFATO', 2),
(57, 'BASILIXIMAB', 1),
(58, 'BECLOMETASONA DIPROPIONATO', 2),
(59, 'BEDAQUILINA', 2),
(60, 'BENCILPENICILINA BENZATÍNICA', 2),
(61, 'BENCILPENICILINA PROCAÍNICA', 2),
(62, 'BENCILPENICILINA SÓDICA', 2),
(63, 'BENZNIDAZOL', 2),
(64, 'BENZOATO DE BENCILO', 2),
(65, 'BETAMETASONA (FOSFATO)', 2),
(66, 'BETAMETASONA (VALERATO)', 2),
(67, 'BEVACIZUMAB', 2),
(68, 'BICALUTAMIDA', 1),
(69, 'BICARBONATO DE SODIO', 2),
(70, 'BICARBONATO DE SODIO P/HEMODIÁLISIS', 2),
(71, 'BIPERIDENO CLORHIDRATO', 2),
(72, 'BISACODILO', 2),
(73, 'BISOPROLOL', 2),
(74, 'BLEOMICINA', 2),
(75, 'BORTEZOMIB', 1),
(76, 'BROMOCRIPTINA', 2),
(77, 'BUDESONIDA', 2),
(78, 'BUPIVACAINA CLORHIDRATO', 2),
(79, 'BUPIVACAINA CLORHIDRATO (PESADA)', 2),
(80, 'BUPIVACAINA CLORHIDRATO CON EPINEFRI- NA SIN CONSERVANTE', 2),
(81, 'BUSULFANO', 2),
(82, 'BUTILBROMURO DE HIOSCINA (BUTILESCO- POLAMINA)', 2),
(83, 'CABERGOLINA', 2),
(84, 'CAFEÍNA CITRATO', 2),
(85, 'CALCIO (CARBONATO O CITRATO)', 2),
(86, 'CALCIO + VITAMINA D', 2),
(87, 'CAPECITABINE', 2),
(88, 'CAPREOMICINA', 2),
(89, 'CARBAMAZEPINA', 2),
(90, 'CARBETOCINA', 1),
(91, 'CARBÓN MEDICINAL ACTIVADO', 2),
(92, 'CARBOPLATINO', 2),
(93, 'CARVEDILOL', 2),
(94, 'CEFAZOLINA', 2),
(95, 'CEFEPIMA', 2),
(96, 'CEFIXIMA', 1),
(97, 'CEFOTAXIMA', 2),
(98, 'CEFRADINA', 2),
(99, 'CEFTAZIDIMA', 2),
(100, 'CEFTRIAXONA', 2),
(101, 'CETIRIZINA', 2),
(102, 'CIANOCOBALAMINA (VITAMINA B12)', 2),
(103, 'CICLOFOSFAMIDA', 2),
(104, 'CICLOSERINA', 2),
(105, 'CICLOSPORINA', 2),
(106, 'CIPROFLOXACINA', 2),
(107, 'CIPROTERONA (ACETATO)', 2),
(108, 'CIPROTERONA   ACETATO   +   ESTRADIOL VALERATO', 2),
(109, 'CISPLATINO', 2),
(110, 'CITARABINA', 2),
(111, 'CLARITROMICINA', 1),
(112, 'CLINDAMICINA', 1),
(113, 'CLOBETASOL', 2),
(114, 'CLOFAZIMINA', 2),
(115, 'CLOMIFENO CITRATO', 2),
(116, 'CLOMIPRAMINA', 2),
(117, 'CLOMIPRAMINA (CLORHIDRATO)', 2),
(118, 'CLONAZEPAM', 2),
(119, 'CLOPIDOGREL', 1),
(120, 'CLORANFENICOL', 2),
(121, 'CLORANFENICOL SUCCINATO SÓDICO', 2),
(122, 'CLORFENAMINA (CLORFENIRAMINA)', 2),
(123, 'CLORHEXIDINA GLUCONATO', 2),
(124, 'CLOROQUINA FOSFATO', 2),
(125, 'CLORPROMAZINA', 2),
(126, 'CLORURO DE POTASIO', 2),
(127, 'CLORURO DE SODIO', 2),
(128, 'CLOTRIMAZOL', 2),
(129, 'CLOXACILINA', 2),
(130, 'CODEÍNA', 2),
(131, 'COLCHICINA', 2),
(132, 'COLECALCIFEROL (VITAMINA D3)', 2),
(133, 'COLISTINA', 1),
(134, 'COMPLEJO  B (B1 + B6 + B12)', 2),
(135, 'COMPLEJO  DE  VITAMINAS  Y  MINERALES (USO PEDIATRÍA) CMV', 2),
(136, 'COMPLEMENTO NUTRICIONAL', 2),
(137, 'COMPLEMENTO NUTRICIONAL (CARMELO)', 2),
(138, 'COMPLEMENTO NUTRICIONAL (DIABÉTICO)', 2),
(139, 'COMPLEMENTO NUTRICIONAL (NUTRIBEBÉ)', 2),
(140, 'CONTRASTE IODADO', 2),
(141, 'CORTICOIDE + ANESTÉSICO', 2),
(142, 'CORTICOIDE + ANTIINFECCIOSO DE ACCION TÓPICA', 2),
(143, 'CORTICOTROFINA (ACTH)', 2),
(144, 'COTRIMOXAZOL (SULFAMETOXAZOL + TRI- METOPRIMA)', 2),
(145, 'DACARBAZINA', 2),
(146, 'DACLATASVIR', 2),
(147, 'DACTINOMICINA', 2),
(148, 'DAPSONA', 2),
(149, 'DARUNAVIR', 1),
(150, 'DASATINIB', 1),
(151, 'DAUNORRUBICINA', 2),
(152, 'DEFEROXAMINA MESILATO', 2),
(153, 'DELAMANID', 2),
(154, 'DESMOPRESINA ACETATO', 2),
(155, 'DEXAMETASONA', 2),
(156, 'DEXMEDETOMIDINA', 2),
(157, 'DEXTRÁN 70', 2),
(158, 'DEXTROMETORFANO BROMHIDRATO', 2),
(159, 'DIAZEPAM', 2),
(160, 'DICLOFENACO SÓDICO', 2),
(161, 'DICLOXACILINA SÓDICA', 2),
(162, 'DIGOXINA', 2),
(163, 'DIMENHIDRINATO', 2),
(164, 'DIMERCAPROL (B.A.L.)', 2),
(165, 'DINITRATO  DE  ISOSORBIDA  (ISOSORBIDE DINITRATO)', 2),
(166, 'DOBUTAMINA CLORHIDRATO', 2),
(167, 'DOCETAXEL', 2),
(168, 'DOMPERIDONA', 2),
(169, 'DOPAMINA CLORHIDRATO', 2),
(170, 'DORZOLAMIDA', 2),
(171, 'DOXICICLINA', 2),
(172, 'DOXORRUBICINA LIPOSOMAL PEGILADA', 1),
(173, 'DOXORUBICINA  CLORHIDRATO  (ADRIAMICI- NA CLORH.)', 2),
(174, 'DROPERIDOL', 2),
(175, 'DULOXETINA', 1),
(176, 'EDETATO SÓDICO DE CALCIO (EDTA)', 2),
(177, 'EFAVIRENZ', 2),
(178, 'EMULSIÓN DE LÍPIDOS', 2),
(179, 'ENALAPRIL MALEATO', 2),
(180, 'ENZALUTAMIDA', 1),
(181, 'ENZIMAS PANCREÁTICAS(LIPASA, PROTEASA Y AMILASA EN COMBINACIÓN)', 2),
(182, 'EPINEFRINA (ADRENALINA)', 2),
(183, 'ERGOMETRINA MALEATO', 2),
(184, 'ERGOTAMINA TARTRATO + CAFEÍNA', 2),
(185, 'ERITROMICINA', 2),
(186, 'ERITROMICINA (ESTEARATO)', 2),
(187, 'ERITROMICINA (ETILSUCCINATO)', 2),
(188, 'ERITROPOYETINA', 2),
(189, 'ERLOTINIB', 1),
(190, 'ESPIRAMICINA', 2),
(191, 'ESPIRONOLACTONA', 2),
(192, 'ESTRADIOL + NORETISTERONA ACETATO', 2),
(193, 'ESTRADIOL VALERIANATO + NORGESTREL', 2),
(194, 'ESTRADIOL  VALERIANATO  +  PRASTERONA ENANTATO', 2),
(195, 'ESTREPTOMICINA SULFATO', 2),
(196, 'ESTREPTOQUINASA', 2),
(197, 'ESTRÓGENOS CONJUGADOS', 2),
(198, 'ETAMBUTOL', 1),
(199, 'ETAMSILATO', 1),
(200, 'ETER ALIFÁTICO DIETILAMINO ETANOL', 2),
(201, 'ETILEFRINA', 2),
(202, 'ETIONAMIDA', 2),
(203, 'ETOPÓSIDO', 2),
(204, 'FACTOR IX DE LA COAGULACIÓN', 1),
(205, 'FACTOR VIII DE LA COAGULACIÓN', 1),
(206, 'FAVIPIRAVIR', 1),
(207, 'FENITOÍNA', 2),
(208, 'FENOBARBITAL', 2),
(209, 'FENOFIBRATO', 2),
(210, 'FENTANILO CON CONSERVANTE', 2),
(211, 'FENTANILO SIN CONSERVANTE', 2),
(212, 'FIBRA NATURAL', 2),
(213, 'FIBRINÓGENO HUMANO', 1),
(214, 'FILGRASTRIM', 2),
(215, 'FINASTERIDA', 2),
(216, 'FINGOLIMOD', 1),
(217, 'FITOMENADIONA  (VITAMINA K1)', 2),
(218, 'FLUCONAZOL', 2),
(219, 'FLUDARABINA', 1),
(220, 'FLUMAZENIL', 2),
(221, 'FLUNARIZINA', 2),
(222, 'FLUORESCEINA', 2),
(223, 'FLUORESCEINA (SAL SÓDICA)', 2),
(224, 'FLUOROURACILO', 2),
(225, 'FLUORURO DE SODIO', 2),
(226, 'FLUOXETINA', 2),
(227, 'FLUTAMIDA', 2),
(228, 'FORMALDEHIDO', 2),
(229, 'FUROSEMIDA', 2),
(230, 'GADODIAMIDA', 2),
(231, 'GADOPENTATO DE DIMEGLUMINA', 2),
(232, 'GADOVERSETAMIDA', 1),
(233, 'GEMCITABINA', 2),
(234, 'GEMFIBROZILO', 2),
(235, 'GENTAMICINA', 2),
(236, 'GENTAMICINA SULFATO', 2),
(237, 'GLIBENCLAMIDA', 2),
(238, 'GLICERINA + CARBONATO DE SODIO', 2),
(239, 'GLICEROL (GLICERINA BIDESTILADA)', 2),
(240, 'GLICEROL (GLICERINA)', 2),
(241, 'GLOBULINA ANTI-TIMOCITO', 1),
(242, 'GLOBULINA ANTI-TIMOCITO (CONEJO)', 1),
(243, 'GLUCONATO   CÁLCICO   (CALCIO   GLUCO- NATO)', 2),
(244, 'GONADOTROFINA CORIÓNICA', 2),
(245, 'GRISEOFULVINA', 2),
(246, 'HALOPERIDOL', 2),
(247, 'HALOPERIDOL DECANOATO', 2),
(248, 'HALOTANO', 2),
(249, 'HEPARINA DE BAJO PESO MOLECULAR', 2),
(250, 'HEPARINA SÓDICA', 2),
(251, 'HIDRALAZINA CLORHIDRATO', 2),
(252, 'HIDROCLOROTIAZIDA', 2),
(253, 'HIDROCLOROTIAZIDA + AMILORIDA', 2),
(254, 'HIDROCORTISONA ACETATO', 2),
(255, 'HIDROCORTISONA SUCCINATO SÓDICO', 2),
(256, 'HIDROQUINONA', 2),
(257, 'HIDROXICLOROQUINA SULFATO', 2),
(258, 'HIDRÓXIDO DE ALUMINIO Y MAGNESIO', 2),
(259, 'HIDROXIUREA', 2),
(260, 'HIERRO', 2),
(261, 'HIERRO (COMO BISGLICINA QUELATO)', 2),
(262, 'HIPOCLORITO DE SODIO', 2),
(263, 'IBUPROFENO', 2),
(264, 'IFOSFAMIDA', 2),
(265, 'IMATINIB', 1),
(266, 'IMIPENEM + CILASTATINA', 1),
(267, 'IMIPRAMINA CLORHIDRATO', 2),
(268, 'INDOMETACINA', 2),
(269, 'INMUNOGLOBULINA ANTI D (RH +)', 2),
(270, 'INMUNOGLOBULINA HUMANA NORMAL', 2),
(271, 'INSULINA GLARGINA', 2),
(272, 'INSULINA GLULISINA', 2),
(273, 'INSULINA RECOMBINANTE HUMANA NPH', 2),
(274, 'INSULINA ZINC CRISTALINA  RECOMBINANTE HUMANA', 2),
(275, 'INTERFERON ALFA 2 B RECOMBINANTE', 2),
(276, 'INTERFERON BETA', 2),
(277, 'IODO (YODO)', 2),
(278, 'IODO POVIDONA (YODOPOVIDONA)', 2),
(279, 'IPECACUANA', 2),
(280, 'IPRATROPIO BROMURO', 2),
(281, 'IRINOTECAN', 1),
(282, 'ISONIAZIDA (INH)', 1),
(283, 'ISOSORBIDA MONONITRATO', 2),
(284, 'IVERMECTINA', 2),
(285, 'KANAMICINA', 2),
(286, 'KETAMINA (CETAMINA)', 2),
(287, 'KETOPROFENO', 2),
(288, 'KETOROLACO', 2),
(289, 'KETOTIFENO', 2),
(290, 'LABETALOL', 1),
(291, 'LACTULOSA', 2),
(292, 'LÁGRIMAS ARTIFICIALES', 2),
(293, 'LAMIVUDINA', 2),
(294, 'LAMOTRIGINA', 1),
(295, 'LATANOPROST', 2),
(296, 'LEDIPASVIR + SOFOSBUVIR', 1),
(297, 'LEFLUNOMIDA', 1),
(298, 'LENALIDOMIDA', 1),
(299, 'LETROZOL', 2),
(300, 'LEUCOVORINA', 2),
(301, 'LEUCOVORINA (FOLINATO DE CALCIO)', 2),
(302, 'LEUPROLIDE', 1),
(303, 'LEVODOPA + CARBIDOPA', 2),
(304, 'LEVOFLOXACINA', 1),
(305, 'LEVONORGESTREL', 2),
(306, 'LEVONORGESTREL + ETINILESTRADIOL', 2),
(307, 'LEVOTIROXINA  SÒDICA', 2),
(308, 'LEVOTIROXINA SÓDICA', 2),
(309, 'LIDOCAÍNA', 2),
(310, 'LIDOCAÍNA CLORHIDRATO', 2),
(311, 'LIDOCAÍNA CLORHIDRATO + EPINEFRINA', 2),
(312, 'LIDOCAÍNA   CLORHIDRATO   SIN   CONSER- VANTE', 2),
(313, 'LINEZOLID', 1),
(314, 'LITIO CARBONATO', 2),
(315, 'LOPERAMIDA', 2),
(316, 'LOPINAVIR + RITONAVIR', 2),
(317, 'LOSARTÁN', 2),
(318, 'MEBENDAZOL', 2),
(319, 'MEDROXIPROGESTERONA ACETATO', 2),
(320, 'MEFLOQUINA (CLORHIDRATO)', 2),
(321, 'MEGLUMINA ANTIMONIATO', 2),
(322, 'MEGLUMINA DIATRIZOATO', 2),
(323, 'MELFALÁN', 2),
(324, 'MELOXICAM', 2),
(325, 'MERCAPTOPURINA', 2),
(326, 'MEROPENEM', 1),
(327, 'MESALAZINA', 2),
(328, 'MESNA     (MERCAPTO     ETILSULFONATO SÓDICO)', 2),
(329, 'METADONA', 2),
(330, 'METAMIZOL (DIPIRONA)', 2),
(331, 'METFORMINA', 2),
(332, 'METILDOPA (ALFAMETILDOPA)', 2),
(333, 'METILFENIDATO', 2),
(334, 'METILPREDNISOLONA SUCCINATO SÓDICO', 2),
(335, 'METOCLOPRAMIDA', 2),
(336, 'METOTREXATO', 2),
(337, 'METRONIDAZOL', 2),
(338, 'MICOFENOLATO DE MOFETILO', 1),
(339, 'MICRONUTRIENTES (VIT. C + VIT A + FE\n+ ZN + AC. FÓLICO)  (CHISPITAS NUTRI- CIONALES)', 2),
(340, 'MIDAZOLAM', 2),
(341, 'MIFEPRISTONA', 2),
(342, 'MILRINONA', 1),
(343, 'MINOCICLINA', 1),
(344, 'MISOPROSTOL', 1),
(345, 'MITOMICINA', 2),
(346, 'MIVACURONIO', 2),
(347, 'MONTELUKAST', 2),
(348, 'MORFINA', 2),
(349, 'MORFINA (CON Y SIN CONSERVANTE)', 2),
(350, 'MOXIFLOXACINA', 1),
(351, 'MULTIVITAMINAS', 2),
(352, 'NAFAZOLINA CLORHIDRATO', 2),
(353, 'NALOXONA', 2),
(354, 'NARATRIPTAN', 2),
(355, 'NEOSTIGMINA', 2),
(356, 'NEVIRAPINA', 2),
(357, 'NICLOSAMIDA', 2),
(358, 'NIFEDIPINO', 2),
(359, 'NIFURTIMOX', 2),
(360, 'NIMODIPINA', 2),
(361, 'NIMOTUZUMAB', 1),
(362, 'NISTATINA', 2),
(363, 'NITAZOXANIDA', 2),
(364, 'NITROFURAL (NITROFURAZONA)', 2),
(365, 'NITROFURANTOÍNA', 2),
(366, 'NITROGLICERINA (TRINITRATO DE GLICEROL)', 2),
(367, 'NITROPRUSIATO DE SODIO', 2),
(368, 'NORADRENALINA', 2),
(369, 'NORETISTERONA', 2),
(370, 'NORGESTREL + ETINILESTRADIOL', 2),
(371, 'OFLOXACINA', 1),
(372, 'OLIGOELEMENTOS  PARA  NUTRICION  PA- RENTERAL', 2),
(373, 'OMEPRAZOL', 2),
(374, 'ONDANSETRÓN', 2),
(375, 'OSELTAMIVIR', 1),
(376, 'OXALIPLATINO', 2),
(377, 'OXIDO DE ZINC CON O SIN ACEITE', 2),
(378, 'OXÍGENO', 2),
(379, 'OXITOCINA', 2),
(380, 'PACLITAXEL', 2),
(381, 'PARACETAMOL (ACETAMINOFENO)', 2),
(382, 'PAZOPANIB', 1),
(383, 'PEGASPARGASA', 1),
(384, 'PENICILAMINA', 2),
(385, 'PENTAMIDINA', 2),
(386, 'PERMANGANATO DE POTASIO', 2),
(387, 'PERMETRINA', 2),
(388, 'PERÓXIDO DE BENZOÍLO', 2),
(389, 'PERÓXIDO DE HIDRÓGENO (AGUA OXIGE- NADA)', 2),
(390, 'PERTUZUMAB', 1),
(391, 'PETIDINA (MEPERIDINA)', 2),
(392, 'PIPERACILINA + TAZOBACTAM', 1),
(393, 'PIRANTEL PAMOATO', 2),
(394, 'PIRAZINAMIDA', 1),
(395, 'PIRIDOSTIGMINA', 2),
(396, 'PIRIDOXINA CLORHIDRATO (VITAMINA B6)', 2),
(397, 'PIRIMETAMINA', 2),
(398, 'PRALIDOXIMA', 2),
(399, 'PRAZICUANTEL', 2),
(400, 'PREDNISONA', 2),
(401, 'PREGABALINA', 1),
(402, 'PRIMAQUINA (BASE)', 2),
(403, 'PROGESTERONA', 2),
(404, 'PROPILTIOURACILO', 2),
(405, 'PROPINOXATO', 2),
(406, 'PROPOFOL', 2),
(407, 'PROPRANOLOL', 2),
(408, 'PROTAMINA SULFATO', 2),
(409, 'PROTEINAS  PARA  ALIMENTACIÓN  ENTERAL (ALIMENTO)', 2),
(410, 'PROXIMETACAINA (PROPARACAINA)', 2),
(411, 'QUETIAPINA', 1),
(412, 'QUININA (BISULFATO O SULFATO)', 2),
(413, 'QUININA (DICLORHIDRATO)', 2),
(414, 'RALTEGRAVIR (POTASICO)', 1),
(415, 'RANITIDINA', 2),
(416, 'REMIFENTANILO', 2),
(417, 'RESINA DE PODOFILO (PODOFILINA)', 2),
(418, 'RESORCINOL', 2),
(419, 'RETINOL (VITAMINA A)', 2),
(420, 'RIFAMPICINA', 2),
(421, 'RIFAMPICINA + ISONIAZIDA (INH)', 1),
(422, 'RIFAMPICINA, CLOFAZIMINA, DAPSONA', 1),
(423, 'RIFAMPICINA, DAPSONA', 1),
(424, 'RISPERIDONA', 2),
(425, 'RITODRINA', 2),
(426, 'RITONAVIR', 2),
(427, 'RITUXIMAB', 1),
(428, 'RIVAROXABÁN', 1),
(429, 'ROCURONIO BROMURO', 2),
(430, 'ROSUVASTATINA', 2),
(431, 'SALBUTAMOL', 2),
(432, 'SALES  DE  REHIDRATACIÓN  ORAL  (SRO) BAJA OSMOLARIDAD', 2),
(433, 'SALMETEROL + FLUTICASONA', 2),
(434, 'SEVOFLURANO (TRIFLUOROMETIL ETIL)', 2),
(435, 'SILDENAFILO', 2),
(436, 'SIMETICONA', 2),
(437, 'SOFOSBUVIR', 2),
(438, 'SOLUCION ACIDA', 2),
(439, 'SOLUCIÓN BÁSICA', 2),
(440, 'SOLUCIÓN DE GLUCOSA', 2),
(441, 'SOLUCIÓN DE MANITOL', 2),
(442, 'SOLUCIÓN DE PRESERVACIÓN DE ÓRGANOS SÒLIDOS HTK', 1),
(443, 'SOLUCIÓN FISIOLÓGICA', 2),
(444, 'SOLUCIÓN GLUCOSADA CLORURADA', 2),
(445, 'SOLUCION PARA DIALISIS INTRAPERITONEAL (DE ACUERDO A COMPOSICION APROPIA- DA)', 1),
(446, 'SOLUCIÓN PARA DIALISIS PERITONEAL I', 2),
(447, 'SOLUCIÓN PARA DIALISIS PERITONEAL II', 2),
(448, 'SOLUCIÓN RINGER LACTATO', 2),
(449, 'SOLUCIÓN RINGER NORMAL', 2),
(450, 'SOMATROPINA', 1),
(451, 'SUCRALFATO', 2),
(452, 'SUERO ANTIESCORPIONICO', 2),
(453, 'SUERO ANTILATRODECTUS', 2),
(454, 'SUERO ANTIOFÍDICO BOTRÓPICO CROTÀLICO', 2),
(455, 'SUERO  ANTIOFÍDICO  BOTRÓPICO  LAQUÉ- SICO', 2),
(456, 'SUERO ANTIRRÀBICO (HETERÓLOGO)', 2),
(457, 'SULFADIAZINA DE PLATA', 2),
(458, 'SULFADOXINA + PIRIMETAMINA', 2),
(459, 'SULFATO DE BARIO', 2),
(460, 'SULFATO DE MAGNESIO', 2),
(461, 'SULFATO FERROSO', 2),
(462, 'SULFATO   FERROSO   +   AC.   FÓLICO   + VITAMINA C', 2),
(463, 'SURFACTANTE PULMONAR', 2),
(464, 'SUXAMETONIO (SUCCINIL COLINA)', 2),
(465, 'TACROLIMUS', 1),
(466, 'TALIDOMIDA', 1),
(467, 'TAMOXIFENO', 2),
(468, 'TEMOZOLOMIDA', 1),
(469, 'TENECTEPLASA', 1),
(470, 'TENOFOVIR DISOPROXILO', 2),
(471, 'TENOFOVIR  DISOPROXILO  +  EFAVIRENZ  + LAMIVUDINA', 2),
(472, 'TEOFILINA', 2),
(473, 'TERBINAFINA', 2),
(474, 'TERLIPRESINA', 1),
(475, 'TESTOSTERONA UNDECANOATO', 2),
(476, 'TETRACICLINA', 2),
(477, 'TIABENDAZOL', 2),
(478, 'TIAMAZOL (METIMAZOL)', 2),
(479, 'TIAMINA (VITAMINA B 1)', 2),
(480, 'TIMOLOL MALEATO', 2),
(481, 'TIOPENTAL SÓDICO', 2),
(482, 'TIORIDAZINA', 2),
(483, 'TOCOFEROL (VITAMINA E)', 2),
(484, 'TOLNAFTATO', 2),
(485, 'TOXOIDE TETÁNICO ADSORBIDO', 2),
(486, 'TRAMADOL', 2),
(487, 'TRASTUZUMAB', 1),
(488, 'TRICLABENDAZOL', 2),
(489, 'TRIPTORELINA', 2),
(490, 'TROLAMINA', 1),
(491, 'TROPICAMIDA', 2),
(492, 'UNGÜENTO DÉRMICO EUCALIPTO MENTOL', 2),
(493, 'VACUNA ANTIAMARÍLICA', 2),
(494, 'VACUNA ANTIHEPATITIS B', 2),
(495, 'VACUNA ANTINEUMOCOCO  (13 VALENTE)', 2),
(496, 'VACUNA ANTIPOLIOMIELÍTICA BIVALENTE', 2),
(497, 'VACUNA ANTIPOLIOMIELÍTICA INACTIVADA', 2),
(498, 'VACUNA ANTIROTAVÍRICA', 2),
(499, 'VACUNA ANTIRRÁBICA', 2),
(500, 'VACUNA ANTIRRÁBICA DE USO HUMANO (CULTIVO CELULAR)', 2),
(501, 'VACUNA BCG', 2),
(502, 'VACUNA CONTRA COVID-19', 2),
(503, 'VACUNA CONTRA INFLUENZA (ADULTO)', 2),
(504, 'VACUNA CONTRA INFLUENZA (PEDIÁTRICA)', 2),
(505, 'VACUNA  CUADRIVALENTE  RECOMBINANTE CONTRA EL VIRUS DEL PAPILOMA HUMANO', 2),
(506, 'VACUNA DOBLE DT (DIFTERIA, TÉTANOS)', 2),
(507, 'VACUNA PENTAVALENTE (DPT + HEP. B\n+ H. INFLUENZAE B)', 2),
(508, 'VACUNA SR (SARAMPIÓN, RUBEOLA)', 2),
(509, 'VACUNA  SRP  (SARAMPIÓN,  RUBEOLA; PAPERAS)', 2),
(510, 'VACUNA TRIPLE DPT (DIFTERIA, PERTUSIS, TETANOS)', 2),
(511, 'VALGANCICLOVIR', 1),
(512, 'VANCOMICINA', 1),
(513, 'VASELINA LIQUIDA', 2),
(514, 'VASELINA SÓLIDA', 2),
(515, 'VERAPAMILO', 2),
(516, 'VINBLASTINA', 2),
(517, 'VINCRISTINA', 2),
(518, 'VINORELBINA', 2),
(519, 'VIOLETA DE GENCIANA (CLORURO DE ME- TILROSANILINA)', 2),
(520, 'VORICONAZOL', 1),
(521, 'WARFARINA', 2),
(522, 'ZIDOVUDINA', 2),
(523, 'ZIDOVUDINA + LAMIVUDINA', 2),
(524, 'ZINC (COMO SULFATO)', 2),
(526, 'BIOPEP', 2),
(527, 'PROTEOGLICANOS', 2),
(528, 'MELANINA BIOMIMÉTICA', 2),
(529, 'POLIGLUCOSAMINA', 2),
(530, 'COLAGENO HIDROLIZADO', 2),
(531, 'POLIDOCANOL', 2),
(532, 'PANTENOL', 2),
(533, 'ISOTRETINOÍNA', 1),
(534, 'PAROXETINA (CLORHIDRATO)', 2),
(535, 'TADALAFILO', 1),
(536, 'BENZOCAÍNA', 2),
(537, 'PROBIÓTICO', 2),
(538, 'Ácido dehidrocólico', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `proveedor` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `descripcion`, `proveedor`, `precio`, `existencia`, `usuario_id`) VALUES
(1, 'Laptop lenovo', 1, 1560.00, 47, 2),
(2, 'Televisor', 1, 2500.00, 79, 1),
(6, 'Impresora', 1, 800.00, 0, 1),
(7, 'Gaseosa', 3, 1500.00, 5, 1),
(8, 'PRODUCTO DE PRUEBA', 1, 1000.00, 99, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `codproveedor` int(11) NOT NULL,
  `proveedor` varchar(55) NOT NULL,
  `lab` varchar(30) NOT NULL,
  `contacto` varchar(50) NOT NULL,
  `telefono` int(11) NOT NULL,
  `mail` varchar(35) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `nit` bigint(50) NOT NULL,
  `nocuenta` varchar(20) DEFAULT NULL,
  `banco` varchar(50) DEFAULT '',
  `tipocuenta` varchar(20) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`codproveedor`, `proveedor`, `lab`, `contacto`, `telefono`, `mail`, `direccion`, `nit`, `nocuenta`, `banco`, `tipocuenta`, `usuario_id`) VALUES
(1, 'GRUPO ALCOS S.A.', 'ALCOS', 'Of. Central - Secretaría', 2750075, 'info@grupoalcos.com', 'Calle 7  235 - Obrajes', 1007045023, '', '', '', 1),
(2, 'LABORATORIOS VITA S.A.', 'VITA', 'OF. CENTRAL - LA PAZ', 2788060, 'pablo.collao@vita.com.bo', 'Av. Hector Ormachea 320 - Obrajes', 1020711029, '', '', '', 1),
(3, 'LABORATORIOS BAGÓ DE BOLIVIA S.A.', 'BAGÓ', 'OF. CENTRAL', 2770110, 'bolivia@bago.com.bo', 'Av. Costanera #1000 Edif. Costanera T.1, P.4', 1020503020, '', '', '', 1),
(4, 'LABORATORIOS DE COSMETICA Y FARMOQUIMICA S.A. COFAR', 'COFAR', 'OF. CENTRAL - LA PAZ', 2220352, 'cofar@cofar.com.bo', 'C. Victor Eduardo 2293 - Miraflores', 1020603028, '', '', '', 1),
(5, 'LABORATORIOS IFA S.A.', 'IFA', 'OF. CENTRAL', 33431555, 'info@laboratoriosifa.com', 'Maquina Vieja C/ Moxos #441 Ceca del 2º a.', 1028625022, '', '', '', 1),
(6, 'PHARMATECH BOLIVIANA S.A', 'PHARMA', 'OF. CENTRAL - SANTA CRUZ', 3340150, 'sbahuriet@pharmatech.com.bo', 'Av. San Martin C/9 Oeste N° 15 Equipetrol', 1028387024, '', '', '', 1),
(7, 'TECNOFARMA S.A.', 'TECNOFARMA', 'OF. CENTRAL - SANTA CRUZ', 3393757, 'rolando.aramayo@tecnofarma.com.bo', 'Av. Velarde 2º anillo # 500 - Trompillo', 1020627026, '', '', '', 1),
(8, 'FARMAVAL BOLIVIA S.R.L', 'FARMAVAL', 'OF. CENTRAL - SANTA CRUZ', 3115952, 'rsandoval@savalcorp.com', 'Av. Beni entre 4º y 5º anillo C/M.Castro #28', 1023291025, '', '', '', 1),
(9, 'SOUTH AMERICAN EXPRESS S.A. SAE S.A.', 'SAE', 'OF. CENTRAL - LA PAZ', 2410676, 'saelapaz@saebolivia.com', 'C/ Victor Sanjinez #2608 - Sopocachi', 1020111021, '', '', '', 1),
(10, 'DROGUERIA INTI S.A.', 'INTI', 'OF. CENTRAL LA PAZ', 2176600, 'ronald.reyes@inti.com.bo', 'C/ Lucas Jaimes #1959 - Miraflores', 1020521023, '', '', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Vendedor'),
(3, 'Supervisor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `correlativo` int(11) NOT NULL,
  `idsuc` int(11) NOT NULL,
  `idmedicamento` bigint(20) NOT NULL,
  `fechavencimiento` date NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`correlativo`, `idsuc`, `idmedicamento`, `fechavencimiento`, `saldo`) VALUES
(1, 1, 1, '2024-12-31', 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `idsucursal` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  `ciudad` varchar(11) NOT NULL,
  `almacen` int(11) NOT NULL DEFAULT 2,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`idsucursal`, `nombre`, `direccion`, `telefono`, `ciudad`, `almacen`, `usuario`) VALUES
(1, 'ALMACEN', 'AV. PRINCIPAL 0000', 99999999, 'LA PAZ', 1, 1),
(2, 'SUCURSAL CENTRAL', 'AV. PRINCIPAL 0000', 99999999, 'LA PAZ', 2, 1),
(3, 'SUCURSAL EL ALTO', 'Av. Juan Pablo II #2085 - El Alto', 2865454, 'EL ALTO', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomovimiento`
--

CREATE TABLE `tipomovimiento` (
  `idtipomov` int(11) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `nota` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipomovimiento`
--

INSERT INTO `tipomovimiento` (`idtipomov`, `descripcion`, `nota`) VALUES
(1, 'INGRESO', 'NOTA DE INGRESO'),
(2, 'DEVOLUCIÓN PROVEEDOR', 'NOTA DEVOLUCION PROVEEDOR'),
(3, 'INGRESO POR TRASPASO', 'NOTA DE INGRESO SUCURSAL'),
(4, 'TRASPASO A SUCURSAL', 'NOTA DE REMISION'),
(5, 'INGRESO POR DEVOLUCIÓN', 'NOTA DE INGRESO DEV-SUCURSAL'),
(6, 'DEVOLUCION ALMACEN', 'NOTA DE DEVOLUCION'),
(7, 'VENTA', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposucursal`
--

CREATE TABLE `tiposucursal` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tiposucursal`
--

INSERT INTO `tiposucursal` (`id`, `descripcion`) VALUES
(1, 'ALMACEN'),
(2, 'SUCURSAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traspaso`
--

CREATE TABLE `traspaso` (
  `notraspaso` int(11) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `idtipomov` int(11) NOT NULL,
  `notatraspaso` varchar(50) NOT NULL,
  `idsucorigen` int(11) NOT NULL,
  `idsucdestino` int(11) DEFAULT NULL,
  `idproveedor` int(11) DEFAULT NULL,
  `capital` decimal(10,2) DEFAULT NULL,
  `idestado` int(11) NOT NULL DEFAULT 1,
  `iduser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `idunidad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `abreviatura` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidades`
--

INSERT INTO `unidades` (`idunidad`, `nombre`, `abreviatura`) VALUES
(1, 'Kilogramo', 'Kg'),
(2, 'Gramo', 'g'),
(3, 'Miligramos', 'mg'),
(4, 'Microgramo', 'mcg'),
(5, 'Miligramos / mililitros', 'mg/ml'),
(6, 'Litro', 'l'),
(7, 'Mililitro', 'ml'),
(8, 'Milimol', 'ml'),
(9, 'Miliequivalente', 'mEq'),
(10, 'Unidad Internacional', 'UI'),
(11, 'Porcentaje', '%');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `uso`
--

CREATE TABLE `uso` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `uso`
--

INSERT INTO `uso` (`id`, `descripcion`) VALUES
(1, 'RESTRINGIDO'),
(2, 'LIBRE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `rol` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `idsucursal`) VALUES
(1, 'Administrador', 'admin@farmabol.com.bo', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1),
(3, 'Maria Perez Miranda', 'supervisor@farmabol.com.bo', 'supervisor', 'd947df684dd40733bef0cd787f8ae4e1', 3, 2),
(4, 'Jose Zabaleta', 'vendedor@farmabol.com.bo', 'supervisorea', '22981ee8bebef1155b820e13b18f0e53', 2, 3),
(7, 'Adrian Reynaldo Riveros Vallejos', 'adrianriverosvallejos@gmail.com', 'ariveros', '360883591400af8d9c31518422b82133', 3, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accionterapeutica`
--
ALTER TABLE `accionterapeutica`
  ADD PRIMARY KEY (`idaccion`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombrecaja` (`nombrecaja`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD UNIQUE KEY `nit_ci` (`nit_ci`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalledevolucionsuc`
--
ALTER TABLE `detalledevolucionsuc`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `detallefacturas`
--
ALTER TABLE `detallefacturas`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `detallelote`
--
ALTER TABLE `detallelote`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `detalletraspaso`
--
ALTER TABLE `detalletraspaso`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `detalle_tempf`
--
ALTER TABLE `detalle_tempf`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `detalle_temp_lote`
--
ALTER TABLE `detalle_temp_lote`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `detalle_temp_traspaso`
--
ALTER TABLE `detalle_temp_traspaso`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `devolucion_suc`
--
ALTER TABLE `devolucion_suc`
  ADD PRIMARY KEY (`nodevsuc`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `estado_caja`
--
ALTER TABLE `estado_caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_lote`
--
ALTER TABLE `estado_lote`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_traspaso`
--
ALTER TABLE `estado_traspaso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`nofactura`);

--
-- Indices de la tabla `forma`
--
ALTER TABLE `forma`
  ADD PRIMARY KEY (`idforma`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`idmov`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`nolote`);

--
-- Indices de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  ADD PRIMARY KEY (`idmedicamento`),
  ADD UNIQUE KEY `codmedicamento` (`codmedicamento`);

--
-- Indices de la tabla `pactivo`
--
ALTER TABLE `pactivo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`codproveedor`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`correlativo`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`idsucursal`);

--
-- Indices de la tabla `tipomovimiento`
--
ALTER TABLE `tipomovimiento`
  ADD PRIMARY KEY (`idtipomov`);

--
-- Indices de la tabla `tiposucursal`
--
ALTER TABLE `tiposucursal`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `traspaso`
--
ALTER TABLE `traspaso`
  ADD PRIMARY KEY (`notraspaso`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`idunidad`);

--
-- Indices de la tabla `uso`
--
ALTER TABLE `uso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accionterapeutica`
--
ALTER TABLE `accionterapeutica`
  MODIFY `idaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalledevolucionsuc`
--
ALTER TABLE `detalledevolucionsuc`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallefacturas`
--
ALTER TABLE `detallefacturas`
  MODIFY `correlativo` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallelote`
--
ALTER TABLE `detallelote`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalletraspaso`
--
ALTER TABLE `detalletraspaso`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_tempf`
--
ALTER TABLE `detalle_tempf`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_temp_lote`
--
ALTER TABLE `detalle_temp_lote`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_temp_traspaso`
--
ALTER TABLE `detalle_temp_traspaso`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devolucion_suc`
--
ALTER TABLE `devolucion_suc`
  MODIFY `nodevsuc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_caja`
--
ALTER TABLE `estado_caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_lote`
--
ALTER TABLE `estado_lote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_traspaso`
--
ALTER TABLE `estado_traspaso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `nofactura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `forma`
--
ALTER TABLE `forma`
  MODIFY `idforma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `kardex`
--
ALTER TABLE `kardex`
  MODIFY `idmov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `nolote` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `medicamento`
--
ALTER TABLE `medicamento`
  MODIFY `idmedicamento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `pactivo`
--
ALTER TABLE `pactivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=541;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `codproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `idsucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipomovimiento`
--
ALTER TABLE `tipomovimiento`
  MODIFY `idtipomov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tiposucursal`
--
ALTER TABLE `tiposucursal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `traspaso`
--
ALTER TABLE `traspaso`
  MODIFY `notraspaso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidades`
--
ALTER TABLE `unidades`
  MODIFY `idunidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `uso`
--
ALTER TABLE `uso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
