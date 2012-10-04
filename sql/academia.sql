SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='';

DROP SCHEMA IF EXISTS `db_academia` ;
CREATE SCHEMA IF NOT EXISTS `db_academia` DEFAULT CHARACTER SET utf8 ;
USE `db_academia` ;

-- -----------------------------------------------------
-- Table `db_academia`.`tipo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`tipo` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`tipo` (
  `id_tipo` TINYINT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `descricao` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_tipo`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`pessoa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`pessoa` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`pessoa` (
  `id_pessoa` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_tipo` TINYINT(1) UNSIGNED NOT NULL ,
  `nome` VARCHAR(45) NOT NULL ,
  `sobrenome` VARCHAR(45) NOT NULL ,
  `sexo` CHAR(1) NOT NULL ,
  `data` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `nascimento` TIMESTAMP NULL ,
  `mae` VARCHAR(100) NOT NULL ,
  `pai` VARCHAR(100) NULL ,
  `cpf` VARCHAR(11) NULL ,
  `rg` VARCHAR(20) NULL ,
  `telefone` BIGINT(11) UNSIGNED NULL ,
  `endereco` VARCHAR(100) NOT NULL ,
  `complemento` VARCHAR(45) NULL ,
  `bairro` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_pessoa`) ,
  CONSTRAINT `fk_pessoa_tipo`
    FOREIGN KEY (`id_tipo` )
    REFERENCES `db_academia`.`tipo` (`id_tipo` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`grau`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`grau` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`grau` (
  `id_grau` SMALLINT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `grau` VARCHAR(20) NOT NULL ,
  `faixa` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id_grau`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`atleta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`atleta` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`atleta` (
  `id_atleta` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_pessoa` INT(1) UNSIGNED NOT NULL ,
  `id_grau` SMALLINT(1) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_atleta`) ,
  CONSTRAINT `fk_atleta_pessoa1`
    FOREIGN KEY (`id_pessoa` )
    REFERENCES `db_academia`.`pessoa` (`id_pessoa` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_atleta_grau1`
    FOREIGN KEY (`id_grau` )
    REFERENCES `db_academia`.`grau` (`id_grau` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`professor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`professor` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`professor` (
  `id_professor` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_atleta` INT(1) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_professor`) ,
  CONSTRAINT `fk_professor_atleta1`
    FOREIGN KEY (`id_atleta` )
    REFERENCES `db_academia`.`atleta` (`id_atleta` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`turma`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`turma` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`turma` (
  `id_turma` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `dercricao` VARCHAR(45) NULL ,
  `id_professor` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_turma`) ,
  CONSTRAINT `fk_turma_professor1`
    FOREIGN KEY (`id_professor` )
    REFERENCES `db_academia`.`professor` (`id_professor` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`aluno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`aluno` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`aluno` (
  `id_aluno` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_atleta` INT(1) UNSIGNED NOT NULL ,
  `id_turma` INT(1) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_aluno`) ,
  CONSTRAINT `fk_aluno_atleta1`
    FOREIGN KEY (`id_atleta` )
    REFERENCES `db_academia`.`atleta` (`id_atleta` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_aluno_turma1`
    FOREIGN KEY (`id_turma` )
    REFERENCES `db_academia`.`turma` (`id_turma` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`nivel_acesso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`nivel_acesso` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`nivel_acesso` (
  `id_nivel_acesso` TINYINT(1) UNSIGNED NOT NULL COMMENT 'Usuario nivel 5, Administrador 9' ,
  `nivel` VARCHAR(45) NOT NULL ,
  `descritivo` VARCHAR(100) NULL ,
  PRIMARY KEY (`id_nivel_acesso`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`usuario` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`usuario` (
  `id_usuario` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_pessoa` INT(1) UNSIGNED NOT NULL ,
  `id_nivel_acesso` TINYINT(1) UNSIGNED NOT NULL ,
  `senha` VARCHAR(255) NOT NULL ,
  `status` TINYINT UNSIGNED NOT NULL ,
  `pergunta` VARCHAR(45) NOT NULL ,
  `resposta` VARCHAR(10) NOT NULL ,
  `login` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id_usuario`) ,
  CONSTRAINT `fk_usuario_nivel_acesso1`
    FOREIGN KEY (`id_nivel_acesso` )
    REFERENCES `db_academia`.`nivel_acesso` (`id_nivel_acesso` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_pessoa1`
    FOREIGN KEY (`id_pessoa` )
    REFERENCES `db_academia`.`pessoa` (`id_pessoa` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`dia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`dia` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`dia` (
  `id_dia` TINYINT(1) UNSIGNED NOT NULL ,
  `abreviatura` CHAR(3) NOT NULL ,
  `dia` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id_dia`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`horario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`horario` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`horario` (
  `id_horario` SMALLINT(1) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `periodo` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_horario`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_academia`.`dia_turma`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `db_academia`.`dia_turma` ;

CREATE  TABLE IF NOT EXISTS `db_academia`.`dia_turma` (
  `id_turma` INT(1) UNSIGNED NOT NULL ,
  `id_dia` TINYINT(1) UNSIGNED NOT NULL ,
  `id_horario` SMALLINT(1) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_turma`, `id_dia`, `id_horario`) ,
  CONSTRAINT `fk_dia_turma_dia1`
    FOREIGN KEY (`id_dia` )
    REFERENCES `db_academia`.`dia` (`id_dia` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_dia_turma_turma1`
    FOREIGN KEY (`id_turma` )
    REFERENCES `db_academia`.`turma` (`id_turma` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_dia_turma_horario1`
    FOREIGN KEY (`id_horario` )
    REFERENCES `db_academia`.`horario` (`id_horario` )
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `db_academia`.`tipo`
-- -----------------------------------------------------
START TRANSACTION;
USE `db_academia`;
INSERT INTO `db_academia`.`tipo` (`id_tipo`, `descricao`) VALUES (1, 'Usuario');
INSERT INTO `db_academia`.`tipo` (`id_tipo`, `descricao`) VALUES (2, 'Professor');
INSERT INTO `db_academia`.`tipo` (`id_tipo`, `descricao`) VALUES (3, 'Aluno');

COMMIT;

-- -----------------------------------------------------
-- Data for table `db_academia`.`pessoa`
-- -----------------------------------------------------
START TRANSACTION;
USE `db_academia`;
INSERT INTO `db_academia`.`pessoa` (`id_pessoa`, `id_tipo`, `nome`, `sobrenome`, `sexo`, `data`, `nascimento`, `mae`, `pai`, `cpf`, `rg`, `telefone`, `endereco`, `complemento`, `bairro`) VALUES (NULL, 1, 'gil cezar', 'correa', 'm', '00-00-0000 00:00:00', NULL, 'joana ', 'aristides', '00000011133', '4445551', 6291475749, 'rua copacabana', 'qd 1 lt 32', 'p atalaia');
INSERT INTO `db_academia`.`pessoa` (`id_pessoa`, `id_tipo`, `nome`, `sobrenome`, `sexo`, `data`, `nascimento`, `mae`, `pai`, `cpf`, `rg`, `telefone`, `endereco`, `complemento`, `bairro`) VALUES (NULL, 1, 'andre', 'rocha', 'm', '00-00-0000 00:00:00', NULL, 'nao sei', 'tambem nao sei', '11122233344', '1112223', 6299945522, 'rua atras do aldeia', 'c sn', 'atras do aldeia');

COMMIT;

-- -----------------------------------------------------
-- Data for table `db_academia`.`nivel_acesso`
-- -----------------------------------------------------
START TRANSACTION;
USE `db_academia`;
INSERT INTO `db_academia`.`nivel_acesso` (`id_nivel_acesso`, `nivel`, `descritivo`) VALUES (9, 'Administrador', 'Administrador do sistema');
INSERT INTO `db_academia`.`nivel_acesso` (`id_nivel_acesso`, `nivel`, `descritivo`) VALUES (5, 'Usuario', 'Usuário administrativo do sistema');

COMMIT;

-- -----------------------------------------------------
-- Data for table `db_academia`.`dia`
-- -----------------------------------------------------
START TRANSACTION;
USE `db_academia`;
INSERT INTO `db_academia`.`dia` (`id_dia`, `abreviatura`, `dia`) VALUES (1, 'SEG', 'Segunda-Feira');
INSERT INTO `db_academia`.`dia` (`id_dia`, `abreviatura`, `dia`) VALUES (2, 'TER', 'Terça-Feira');
INSERT INTO `db_academia`.`dia` (`id_dia`, `abreviatura`, `dia`) VALUES (3, 'QUA', 'Quarta-Feira');
INSERT INTO `db_academia`.`dia` (`id_dia`, `abreviatura`, `dia`) VALUES (4, 'QUI', 'Quinta-Feira');
INSERT INTO `db_academia`.`dia` (`id_dia`, `abreviatura`, `dia`) VALUES (5, 'SEX', 'Sexta-Feira');
INSERT INTO `db_academia`.`dia` (`id_dia`, `abreviatura`, `dia`) VALUES (6, 'SAB', 'Sabado');
INSERT INTO `db_academia`.`dia` (`id_dia`, `abreviatura`, `dia`) VALUES (7, 'DOM', 'Domingo');

COMMIT;

-- -----------------------------------------------------
-- Data for table `db_academia`.`horario`
-- -----------------------------------------------------
START TRANSACTION;
USE `db_academia`;
INSERT INTO `db_academia`.`horario` (`id_horario`, `periodo`) VALUES (1, '09:00 as 10:00');
INSERT INTO `db_academia`.`horario` (`id_horario`, `periodo`) VALUES (2, '10:00 as 11:00');
INSERT INTO `db_academia`.`horario` (`id_horario`, `periodo`) VALUES (3, '15:00 as 16:00');
INSERT INTO `db_academia`.`horario` (`id_horario`, `periodo`) VALUES (4, '16:00 as 17:00');

COMMIT;



select * from nivel_acesso;


INSERT INTO `usuario` (`id_usuario`,`id_pessoa`,`id_nivel_acesso`,`senha`,`status`,`pergunta`,`resposta`,`login`) VALUES (NULL,1,5,'123teste',1,'123teste','123tese','gil.cezar');