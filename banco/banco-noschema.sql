# --------------------------------------------------------
# Host:                         localhost
# Server version:               5.5.8-log
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-12-01 20:02:16
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping database structure for sistema_vendas

# Dumping structure for table sistema_vendas.clientes_sistema
DROP TABLE IF EXISTS `clientes_sistema`;
CREATE TABLE IF NOT EXISTS `clientes_sistema` (
  `codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT '0',
  `nome_fantasia` varchar(50) DEFAULT '0',
  `cnpj_cpf` varchar(15) DEFAULT '0',
  `contato` varchar(30) DEFAULT '0',
  `numero` varchar(10) DEFAULT '0',
  `complemento` varchar(30) DEFAULT '0',
  `codigo_logradouro` int(11) DEFAULT '0',
  `data_cadastro` date DEFAULT NULL,
  `data_update` date DEFAULT NULL,
  `telefone` char(10) DEFAULT NULL,
  `celular` char(10) DEFAULT NULL,
  `observacoes` text,
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `Index 2` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

# Dumping data for table sistema_vendas.clientes_sistema: ~4 rows (approximately)
DELETE FROM `clientes_sistema`;
/*!40000 ALTER TABLE `clientes_sistema` DISABLE KEYS */;
INSERT INTO `clientes_sistema` (`codigo`, `nome`, `nome_fantasia`, `cnpj_cpf`, `contato`, `numero`, `complemento`, `codigo_logradouro`, `data_cadastro`, `data_update`, `telefone`, `celular`, `observacoes`) VALUES
	(1, 'Samsung', 'Samsung', '19898098000198', 'Sueli', '90', 'predio 5', 1, '2011-11-29', '2011-11-29', '1123459000', '9908989908', 'Empresa de eletronicas filial santos'),
	(2, 'LG eletronics LTDA', 'LG', '19898098000198', 'Romolo', '109', '0', 1, '2011-11-29', NULL, '1122899001', NULL, NULL),
	(3, 'Sony do Brasil', 'Sony', '19898098000190', 'Adailto', '2000', '0', 2, '2011-11-29', NULL, '1122895000', NULL, NULL),
	(5, 'Supricoph', 'Supricoph Copias ', '10889188978899', 'Ana Paula', '245', '3 andar', 1, '2011-11-29', '2011-11-29', '1999098890', '', NULL);
/*!40000 ALTER TABLE `clientes_sistema` ENABLE KEYS */;


# Dumping structure for table sistema_vendas.logradouros_sistema
DROP TABLE IF EXISTS `logradouros_sistema`;
CREATE TABLE IF NOT EXISTS `logradouros_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(50) DEFAULT '0',
  `codigo_tipo_logradouro` int(11) NOT NULL,
  `bairro` varchar(50) DEFAULT '0',
  `codigo_cidade` int(11) DEFAULT '0',
  `cep` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

# Dumping data for table sistema_vendas.logradouros_sistema: ~5 rows (approximately)
DELETE FROM `logradouros_sistema`;
/*!40000 ALTER TABLE `logradouros_sistema` DISABLE KEYS */;
INSERT INTO `logradouros_sistema` (`codigo`, `logradouro`, `codigo_tipo_logradouro`, `bairro`, `codigo_cidade`, `cep`) VALUES
	(1, 'Segredo da Luz', 1, 'Jardim Conquista', 1, '08343160'),
	(2, 'Sede dos Marujos', 1, 'Jardim Conquista', 1, '08343150'),
	(3, 'Funchal', 2, 'Vila Olimpia', 1, '09899889'),
	(4, 'Agostinho Gomes ', 1, 'Ipiranga ', 1, NULL),
	(5, 'Pacheco Chaves', 1, 'Mooca', 1, NULL);
/*!40000 ALTER TABLE `logradouros_sistema` ENABLE KEYS */;


# Dumping structure for table sistema_vendas.modulos_sistema
DROP TABLE IF EXISTS `modulos_sistema`;
CREATE TABLE IF NOT EXISTS `modulos_sistema` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `url_link` varchar(100) DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

# Dumping data for table sistema_vendas.modulos_sistema: ~6 rows (approximately)
DELETE FROM `modulos_sistema`;
/*!40000 ALTER TABLE `modulos_sistema` DISABLE KEYS */;
INSERT INTO `modulos_sistema` (`id`, `nome`, `descricao`, `url_link`, `imagem`) VALUES
	(1, 'vendas', NULL, 'vendas', 'web/images/icone_comercial.png'),
	(2, 'financeiro', NULL, 'financeiro', 'web/images/icone_financeiro.png'),
	(3, 'logisitica', NULL, 'logistica', 'web/images/icone_logistica.png'),
	(4, 'relatorios', NULL, 'relatorios', 'web/images/icone_relatorio.png'),
	(5, 'configuracoes', NULL, 'configuracoes', 'web/images/icone_config.png'),
	(6, 'clientes', NULL, 'clientes', 'web/images/icone_clientes.png');
/*!40000 ALTER TABLE `modulos_sistema` ENABLE KEYS */;


# Dumping structure for table sistema_vendas.produtos_sistema
DROP TABLE IF EXISTS `produtos_sistema`;
CREATE TABLE IF NOT EXISTS `produtos_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) DEFAULT NULL,
  `descricao` text,
  `fornecedor` int(11) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  `valor` double(14,3) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

# Dumping data for table sistema_vendas.produtos_sistema: ~9 rows (approximately)
DELETE FROM `produtos_sistema`;
/*!40000 ALTER TABLE `produtos_sistema` DISABLE KEYS */;
INSERT INTO `produtos_sistema` (`codigo`, `nome`, `descricao`, `fornecedor`, `data_cadastro`, `imagem`, `valor`) VALUES
	(1, 'Camera Canon', 'Camera Digtal Canon 15mgpixels', 1, '2011-11-29', 'camera_canon.jpg', 1568.900),
	(2, 'Epson T031', 'Cartucho para impressora deskjet - 4200', 3, '2011-11-29', 'epson_to31.jpg', 59.900),
	(3, 'Samsung - S17530', 'monitor serie s17530', 2, '2011-11-29', 'samsung_1.jpg', 380.000),
	(4, 'Epson T0138', 'cartucho para impressora ', 3, '2011-11-29', 'epson_to138.jpg', 79.800),
	(5, 'Chamex A4 multi', 'Papel Sulfite Chamex A4 - multiuso', 5, '2011-11-29', 'chamex_1.jpg', 11.990),
	(6, 'Pimaco G6189', 'Papel Glossy Pimaco para Fotos', 7, '2011-11-29', 'pimaco_1.jpg', 17.500),
	(7, 'Multilaser A4278', 'Papel Glossy Multilazer Para Fotos', 6, '2011-11-29', 'multilaser_1.jpg', 15.500),
	(8, 'Samsung - T1901', 'Toner Samsung para impressora Laser 12390', 2, '2011-11-29', 'samsung_2.jpg', 250.000),
	(9, 'Samsung - S8989', 'Celular Samsung - Touche', 2, '2011-11-29', 'samsung_3.jpg', 799.900);
/*!40000 ALTER TABLE `produtos_sistema` ENABLE KEYS */;


# Dumping structure for table sistema_vendas.tipo_logradouro_sistema
DROP TABLE IF EXISTS `tipo_logradouro_sistema`;
CREATE TABLE IF NOT EXISTS `tipo_logradouro_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) DEFAULT '0',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

# Dumping data for table sistema_vendas.tipo_logradouro_sistema: ~5 rows (approximately)
DELETE FROM `tipo_logradouro_sistema`;
/*!40000 ALTER TABLE `tipo_logradouro_sistema` DISABLE KEYS */;
INSERT INTO `tipo_logradouro_sistema` (`codigo`, `descricao`) VALUES
	(1, 'Travessa'),
	(2, 'Rua'),
	(3, 'Avenida'),
	(4, 'Rodovia'),
	(5, 'Estrada');
/*!40000 ALTER TABLE `tipo_logradouro_sistema` ENABLE KEYS */;


# Dumping structure for table sistema_vendas.user_sistema
DROP TABLE IF EXISTS `user_sistema`;
CREATE TABLE IF NOT EXISTS `user_sistema` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login_user` varchar(50) DEFAULT '0',
  `login_password` varchar(50) DEFAULT '0',
  `login_cadastro` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

# Dumping data for table sistema_vendas.user_sistema: ~3 rows (approximately)
DELETE FROM `user_sistema`;
/*!40000 ALTER TABLE `user_sistema` DISABLE KEYS */;
INSERT INTO `user_sistema` (`id`, `login_user`, `login_password`, `login_cadastro`) VALUES
	(1, 'quenduk', 'e10adc3949ba59abbe56e057f20f883e', '2011-11-28'),
	(2, 'fabio', 'a53bd0415947807bcb95ceec535820ee', '2011-11-29'),
	(3, 'tarcisio', '83352b898b7bcc99967ae371da7a8400', '2011-12-01');
/*!40000 ALTER TABLE `user_sistema` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
