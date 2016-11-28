SET FOREIGN_KEY_CHECKS=0;

CREATE  TABLE `news` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `news_category_id` INT NOT NULL ,
  `title` VARCHAR(255) NULL ,
  `announce` TEXT NULL ,
  `text` TEXT NULL ,
  `pub_date` DATE NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `pub_date` (`pub_date` ASC) ,
  INDEX `fk_news_news_category` (`news_category_id` ASC) ,
  CONSTRAINT `fk_news_news_category`
    FOREIGN KEY (`news_category_id` )
    REFERENCES  `news_category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE  TABLE `news_category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE  TABLE  `news_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` INT NOT NULL ,
  `url` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `fk_news_link_news1` (`news_id` ASC) ,
  CONSTRAINT `fk_news_link_news1`
    FOREIGN KEY (`news_id` )
    REFERENCES  `news` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;