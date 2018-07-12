-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema foromotos
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema foromotos
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `foromotos` DEFAULT CHARACTER SET utf8 ;
USE `foromotos` ;

-- -----------------------------------------------------
-- Table `foromotos`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `foromotos`.`usuario` ;

CREATE TABLE IF NOT EXISTS `foromotos`.`usuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `apellidos` VARCHAR(45) NOT NULL,
  `clave` VARCHAR(250) NOT NULL,
  `correo` VARCHAR(250) NOT NULL,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_nacimiento` DATE NOT NULL,
  `sexo` TINYINT NOT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE INDEX `correo_UNIQUE` (`correo` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `foromotos`.`tema`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `foromotos`.`tema` ;

CREATE TABLE IF NOT EXISTS `foromotos`.`tema` (
  `idtema` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(150) NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idusuario` INT NOT NULL,
  PRIMARY KEY (`idtema`),
  INDEX `fk_tema_usuario_idx` (`idusuario` ASC),
  INDEX `fecha` (`fecha_creacion` DESC),
  CONSTRAINT `fk_tema_usuario`
    FOREIGN KEY (`idusuario`)
    REFERENCES `foromotos`.`usuario` (`idusuario`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `foromotos`.`post`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `foromotos`.`post` ;

CREATE TABLE IF NOT EXISTS `foromotos`.`post` (
  `idpost` INT NOT NULL AUTO_INCREMENT,
  `contenido` TEXT NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idtema` INT NOT NULL,
  `idusuario` INT NOT NULL,
  PRIMARY KEY (`idpost`),
  INDEX `fk_post_tema1_idx` (`idtema` ASC),
  INDEX `fk_post_usuario1_idx` (`idusuario` ASC),
  INDEX `fecha` (`fecha_creacion` DESC),
  CONSTRAINT `fk_post_tema1`
    FOREIGN KEY (`idtema`)
    REFERENCES `foromotos`.`tema` (`idtema`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_usuario1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `foromotos`.`usuario` (`idusuario`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
