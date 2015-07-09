
  SET ROLE miltype;

-- -----------------------------------------------------
-- Table "member"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_member";

  CREATE TABLE IF NOT EXISTS  "tbmt_member" (
    "id" serial NOT NULL ,
    "first_name" VARCHAR(80) NOT NULL ,
    "last_name" VARCHAR(80) NOT NULL ,
    "num" serial NOT NULL ,
    "email" VARCHAR(80) NOT NULL ,
    "city" VARCHAR(80) NOT NULL ,
    "country" VARCHAR(80) NOT NULL ,
    "age" smallint not null ,
    "referer_num" INTEGER NULL ,
    "signup_date" timestamp without time zone NOT NULL ,
    "paid" smallint NOT NULL default 0 ,
    "funds_level" smallint NOT NULL default 1,

    "bank_recipient" VARCHAR(120) NOT NULL ,
    "iban" VARCHAR(80) NOT NULL ,
    "bic" VARCHAR(80) NOT NULL ,
    PRIMARY KEY ("id")
  );

  ALTER TABLE ONLY tbmt_member
    ADD CONSTRAINT "member_num_UNIQUE" UNIQUE (num);

  SELECT setval('tbmt_member_num_seq', 1000000);