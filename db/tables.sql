--
-- This is a SQL file used for organizing our system tables
--


CREATE TABLE items (
  id                  BIGINT            NOT NULL        AUTO_INCREMENT,
  created             TIMESTAMP         NOT NULL        DEFAULT CURRENT_TIMESTAMP,
  abbreviation        VARCHAR(3)        NOT NULL,
  domain              VARCHAR(255)      NOT NULL,
  content0            VARCHAR(255)      NOT NULL,
  content1            VARCHAR(255)      NULL,
  content2            VARCHAR(255)      NULL,
  content3            VARCHAR(255)      NULL,
  UNIQUE(abbreviation,domain),
  UNIQUE(content0, content1, content2, content3, domain),
  INDEX(created),
  PRIMARY KEY(id)
) Engine=InnoDB;

