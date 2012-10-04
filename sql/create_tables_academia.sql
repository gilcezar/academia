delimiter $$

CREATE TABLE `aluno` (
  `id_aluno` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_atleta` int(1) unsigned NOT NULL,
  `id_turma` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id_aluno`),
  KEY `fk_aluno_atleta1` (`id_atleta`),
  KEY `fk_aluno_turma1` (`id_turma`),
  CONSTRAINT `fk_aluno_atleta1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`id_atleta`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_aluno_turma1` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id_turma`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `atleta` (
  `id_atleta` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(1) unsigned NOT NULL,
  `id_grau` smallint(1) unsigned NOT NULL,
  PRIMARY KEY (`id_atleta`),
  KEY `fk_atleta_pessoa1` (`id_pessoa`),
  KEY `fk_atleta_grau1` (`id_grau`),
  CONSTRAINT `fk_atleta_grau1` FOREIGN KEY (`id_grau`) REFERENCES `grau` (`id_grau`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_atleta_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `dia` (
  `id_dia` tinyint(1) unsigned NOT NULL,
  `abreviatura` char(3) NOT NULL,
  `dia` varchar(20) NOT NULL,
  PRIMARY KEY (`id_dia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `dia_turma` (
  `id_turma` int(1) unsigned NOT NULL,
  `id_dia` tinyint(1) unsigned NOT NULL,
  `id_horario` smallint(1) unsigned NOT NULL,
  PRIMARY KEY (`id_turma`,`id_dia`,`id_horario`),
  KEY `fk_dia_turma_dia1` (`id_dia`),
  KEY `fk_dia_turma_horario1` (`id_horario`),
  CONSTRAINT `fk_dia_turma_dia1` FOREIGN KEY (`id_dia`) REFERENCES `dia` (`id_dia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_dia_turma_horario1` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_dia_turma_turma1` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id_turma`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `grau` (
  `id_grau` smallint(1) unsigned NOT NULL AUTO_INCREMENT,
  `grau` varchar(20) NOT NULL,
  `faixa` varchar(20) NOT NULL,
  PRIMARY KEY (`id_grau`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `horario` (
  `id_horario` smallint(1) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` varchar(45) NOT NULL,
  PRIMARY KEY (`id_horario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `nivel_acesso` (
  `id_nivel_acesso` tinyint(1) unsigned NOT NULL COMMENT 'Usuario nivel 5, Administrador 9',
  `nivel` varchar(45) NOT NULL,
  `descritivo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_nivel_acesso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `pessoa` (
  `id_pessoa` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `id_tipo` tinyint(1) unsigned NOT NULL,
  `nome` varchar(45) NOT NULL,
  `sobrenome` varchar(45) NOT NULL,
  `sexo` char(1) NOT NULL,
  `data` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nascimento` timestamp NULL DEFAULT NULL,
  `mae` varchar(100) NOT NULL,
  `pai` varchar(100) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `telefone` bigint(11) unsigned DEFAULT NULL,
  `endereco` varchar(100) NOT NULL,
  `complemento` varchar(45) DEFAULT NULL,
  `bairro` varchar(45) NOT NULL,
  PRIMARY KEY (`id_pessoa`),
  KEY `fk_pessoa_tipo` (`id_tipo`),
  CONSTRAINT `fk_pessoa_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipo` (`id_tipo`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `professor` (
  `id_professor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_atleta` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id_professor`),
  KEY `fk_professor_atleta1` (`id_atleta`),
  CONSTRAINT `fk_professor_atleta1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`id_atleta`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `tipo` (
  `id_tipo` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `turma` (
  `id_turma` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `dercricao` varchar(45) DEFAULT NULL,
  `id_professor` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_turma`),
  KEY `fk_turma_professor1` (`id_professor`),
  CONSTRAINT `fk_turma_professor1` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id_professor`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


delimiter $$

CREATE TABLE `usuario` (
  `id_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pessoa` int(1) unsigned NOT NULL,
  `id_nivel_acesso` tinyint(1) unsigned NOT NULL,
  `senha` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `pergunta` varchar(45) NOT NULL,
  `resposta` varchar(10) NOT NULL,
  `login` varchar(20) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_nivel_acesso1` (`id_nivel_acesso`),
  KEY `fk_usuario_pessoa1` (`id_pessoa`),
  CONSTRAINT `fk_usuario_nivel_acesso1` FOREIGN KEY (`id_nivel_acesso`) REFERENCES `nivel_acesso` (`id_nivel_acesso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_pessoa1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8$$



