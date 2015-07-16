
  SET ROLE miltype;

-- -----------------------------------------------------
-- Table "member"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_member" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_member" (
    "id" serial NOT NULL ,
    "first_name" VARCHAR(80) NOT NULL ,
    "last_name" VARCHAR(80) NOT NULL ,
    "num" serial NOT NULL ,
    "email" VARCHAR(80) NOT NULL ,
    "title" VARCHAR(80) NOT NULL ,
    "city" VARCHAR(80) NOT NULL ,
    "country" VARCHAR(80) NOT NULL ,
    "age" smallint not null ,
    "referer_id" INTEGER NULL ,
    "parent_id" INTEGER NULL ,
    "signup_date" timestamp without time zone NOT NULL ,
    "paid_date" timestamp without time zone NULL ,
    "funds_level" smallint NOT NULL default 1,

    "bank_recipient" VARCHAR(120) NOT NULL ,
    "iban" VARCHAR(80) NOT NULL ,
    "bic" VARCHAR(80) NOT NULL ,

    "type" smallint NOT NULL default 0 ,

    "bonus_ids" varchar(80) not null default '',

    "advertised_count" int NOT NULL default 0 ,
    "outstanding_advertised_count" int NOT NULL default 0 ,

    "password" varchar not null ,

    "transferred_total" double precision NOT NULL default 0 ,
    "outstanding_total" double precision NOT NULL default 0 ,

    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_member_referer"
      FOREIGN KEY ("referer_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_member_parent"
      FOREIGN KEY ("parent_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE
  );

  ALTER TABLE ONLY tbmt_member
    ADD CONSTRAINT "member_num_UNIQUE" UNIQUE (num);

  SELECT setval('tbmt_member_num_seq', 1000000);

-- -----------------------------------------------------
-- Table "transfer"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_transfer" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_transfer" (
    "id" serial NOT NULL ,
    "member_id" int NOT NULL ,
    "amount" float default 0 NOT NULL ,
    "state" smallint not null default 0 ,
    "attempts" smallint not null default 0 ,
    "execution_date" timestamp without time zone NULL ,
    "processed_date" timestamp without time zone NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_transfer_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE
  );

-- -----------------------------------------------------
-- Table "transaction"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_transaction" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_transaction" (
    "id" bigserial NOT NULL ,
    "transfer_id" int NOT NULL ,
    "amount" double precision NOT NULL default 0 ,
    "reason" smallint not null default 0 ,
    "related_id" int null ,
    "date" timestamp without time zone NOT NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_transaction_transfer"
      FOREIGN KEY ("transfer_id")
      REFERENCES "tbmt_transfer" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

-- -----------------------------------------------------
-- Table "tbmt_reserved_paid_event"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_reserved_paid_event" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_reserved_paid_event" (
    "unpaid_id" int NOT NULL ,
    "paid_id" int NOT NULL ,
    "date" timestamp without time zone NOT NULL ,
    PRIMARY KEY ("unpaid_id", "paid_id") ,
    CONSTRAINT "fk_tbmt_reserved_paid_event_unpaid_member"
      FOREIGN KEY ("unpaid_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_tbmt_reserved_paid_event_paid_member"
      FOREIGN KEY ("paid_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE
  );

-- -----------------------------------------------------
-- View "last_members" for maintenance issues
-- -----------------------------------------------------
  CREATE OR REPLACE VIEW "last_members_with_transfers" AS
    SELECT
      "m"."id", "m"."num", "m"."type"
    FROM "tbmt_member" "m"
    join "tbmt_transfer" "t" on "t"."member_id" = "m"."id"
    where "t"."amount" > 0
    ORDER BY "m"."id" DESC
    LIMIT 500;