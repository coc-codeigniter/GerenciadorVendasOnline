-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.16-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-09-18 21:36:50
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for sistema_vendas
DROP DATABASE IF EXISTS `sistema_vendas`;
CREATE DATABASE IF NOT EXISTS `sistema_vendas` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sistema_vendas`;


-- Dumping structure for table sistema_vendas.actions_sistema
DROP TABLE IF EXISTS `actions_sistema`;
CREATE TABLE IF NOT EXISTS `actions_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `codigo_method` int(11) DEFAULT NULL,
  KEY `Index 1` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.categorias_sistema
DROP TABLE IF EXISTS `categorias_sistema`;
CREATE TABLE IF NOT EXISTS `categorias_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '0',
  `tipo` char(1) DEFAULT '1',
  `url` varchar(100) DEFAULT NULL,
  `name_filter` varchar(50) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.clientes_sistema
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.grupos_rules_acess
DROP TABLE IF EXISTS `grupos_rules_acess`;
CREATE TABLE IF NOT EXISTS `grupos_rules_acess` (
  `codigo` int(10) DEFAULT NULL,
  `codigo_grupo` int(10) DEFAULT NULL,
  `codigo_action` int(10) DEFAULT NULL,
  `codigo_method` int(10) DEFAULT NULL,
  KEY `Index 1` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.grupos_sistema
DROP TABLE IF EXISTS `grupos_sistema`;
CREATE TABLE IF NOT EXISTS `grupos_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `nome_grupo` varchar(50) DEFAULT NULL,
  KEY `Index 1` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.itens_orcamento
DROP TABLE IF EXISTS `itens_orcamento`;
CREATE TABLE IF NOT EXISTS `itens_orcamento` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_orcamento` int(10) NOT NULL,
  `id_produto` int(10) NOT NULL,
  `qtd` int(10) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  KEY `Index 1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.itens_pedidos_sistema
DROP TABLE IF EXISTS `itens_pedidos_sistema`;
CREATE TABLE IF NOT EXISTS `itens_pedidos_sistema` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `qtd` int(20) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  `subtotal` double NOT NULL DEFAULT '0',
  `id_produto` int(10) NOT NULL DEFAULT '0',
  `id_pedido` int(10) NOT NULL DEFAULT '0',
  KEY `Index 1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.logradouros_sistema
DROP TABLE IF EXISTS `logradouros_sistema`;
CREATE TABLE IF NOT EXISTS `logradouros_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(50) DEFAULT '0',
  `codigo_tipo_logradouro` int(11) NOT NULL,
  `bairro` varchar(50) DEFAULT '0',
  `codigo_cidade` int(11) DEFAULT '0',
  `cep` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.methods_sistema
DROP TABLE IF EXISTS `methods_sistema`;
CREATE TABLE IF NOT EXISTS `methods_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  KEY `Index 1` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.modulos_pagamentos
DROP TABLE IF EXISTS `modulos_pagamentos`;
CREATE TABLE IF NOT EXISTS `modulos_pagamentos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tipoPagamento` int(30) NOT NULL,
  `nomeModulo` varchar(30) NOT NULL,
  `rotuloModulo` varchar(50) NOT NULL COMMENT 'dados para serem exibidos',
  `opcoesModulo` longtext NOT NULL COMMENT 'opcoes do moduloes dados em json',
  KEY `Index 1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.modulos_sistema
DROP TABLE IF EXISTS `modulos_sistema`;
CREATE TABLE IF NOT EXISTS `modulos_sistema` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `url_link` varchar(100) DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.orcamentos_sistema
DROP TABLE IF EXISTS `orcamentos_sistema`;
CREATE TABLE IF NOT EXISTS `orcamentos_sistema` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT '0',
  `id_usuario` int(11) DEFAULT '0',
  `data_orcamento` date DEFAULT NULL,
  `total` double NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A',
  KEY `Index 1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.pedidos_sistema
DROP TABLE IF EXISTS `pedidos_sistema`;
CREATE TABLE IF NOT EXISTS `pedidos_sistema` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `data_pedido` date DEFAULT NULL,
  `total` double NOT NULL,
  `id_cliente` int(11) DEFAULT '0',
  `id_usuario` int(11) DEFAULT '0',
  `desconto` double NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '0',
  `pagamento_tipo` varchar(50) DEFAULT NULL,
  `parcelas` varchar(20) DEFAULT NULL,
  `credor` varchar(50) DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `user_lock` int(11) NOT NULL,
  `entrega_diferente` char(1) NOT NULL DEFAULT 'S',
  `logradouro` varchar(50) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `complemento` varchar(50) NOT NULL,
  `contato` varchar(50) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `uf` char(2) NOT NULL,
  `dados_entrega` text,
  `tipo_entrega` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  KEY `Index 1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.produtos_sistema
DROP TABLE IF EXISTS `produtos_sistema`;
CREATE TABLE IF NOT EXISTS `produtos_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `descricao` text,
  `fornecedor` int(11) DEFAULT NULL,
  `data_cadastro` date DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  `price` double(14,3) DEFAULT NULL,
  `categoria` int(10) DEFAULT NULL,
  `estoque` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.sessions_sistema
DROP TABLE IF EXISTS `sessions_sistema`;
CREATE TABLE IF NOT EXISTS `sessions_sistema` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.sistema_entregasoptions
DROP TABLE IF EXISTS `sistema_entregasoptions`;
CREATE TABLE IF NOT EXISTS `sistema_entregasoptions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `data` text NOT NULL,
  KEY `pkentrega` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.tipos_pagamentos
DROP TABLE IF EXISTS `tipos_pagamentos`;
CREATE TABLE IF NOT EXISTS `tipos_pagamentos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` char(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '2',
  KEY `Index 1` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.tipo_logradouro_sistema
DROP TABLE IF EXISTS `tipo_logradouro_sistema`;
CREATE TABLE IF NOT EXISTS `tipo_logradouro_sistema` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) DEFAULT '0',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table sistema_vendas.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login_user` varchar(50) DEFAULT '0',
  `login_password` varchar(50) DEFAULT '0',
  `login_cadastro` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
