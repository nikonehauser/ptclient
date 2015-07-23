
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
    "signup_date" timestamp with time zone NOT NULL ,
    "paid_date" timestamp with time zone NULL ,
    "funds_level" smallint NOT NULL default 1,

    "bank_recipient" VARCHAR(120) NOT NULL ,
    "iban" VARCHAR(80) NOT NULL ,
    "bic" VARCHAR(80) NOT NULL ,

    "type" smallint NOT NULL default 0 ,

    "bonus_ids" varchar(80) not null default '',

    "advertised_count" int NOT NULL default 0 ,
    "outstanding_advertised_count" int NOT NULL default 0 ,

    "password" varchar(160) NOT NULL ,

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

-- member numbers start with 1 000 001 (1 million)
  SELECT setval('tbmt_member_num_seq', 1000001);

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
    "execution_date" timestamp with time zone NULL ,
    "processed_date" timestamp with time zone NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_transfer_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE
  );

  CREATE INDEX idx_transfer_state ON "tbmt_transfer" (state);

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
    "date" timestamp with time zone NOT NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_transaction_transfer"
      FOREIGN KEY ("transfer_id")
      REFERENCES "tbmt_transfer" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

-- -----------------------------------------------------
-- Table "reserved_paid_event"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_reserved_paid_event" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_reserved_paid_event" (
    "unpaid_id" int NOT NULL ,
    "paid_id" int NOT NULL ,
    "date" timestamp with time zone NOT NULL ,
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
-- Table "invitation"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_invitation" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_invitation" (
    "id" serial NOT NULL ,
    "hash" VARCHAR(64) NOT NULL,
    "member_id" int NOT NULL ,
    "type" smallint NOT NULL ,
    "free_signup" smallint NOT NULL ,
    "creation_date" timestamp with time zone NOT NULL ,
    "accepted_date" timestamp with time zone NULL ,
    "accepted_member_id" int NULL ,
    PRIMARY KEY ("id", "hash") ,
    CONSTRAINT "fk_invitation_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_invitation_accepted_member"
      FOREIGN KEY ("accepted_member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE
  );

-- -----------------------------------------------------
-- Table "activity"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_activity" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_activity" (
    "id" bigserial NOT NULL ,
    "action" VARCHAR(160) NOT NULL,
    "type" SMALLINT NOT NULL ,
    "date" timestamp with time zone NOT NULL ,
    "related_id" VARCHAR(64) NULL DEFAULT NULL,
    "related_member_num" int NULL DEFAULT NULL,
    "meta" TEXT NULL,
    PRIMARY KEY ("id")
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


-- -----------------------------------------------------
-- View "last_transactions_joined" for maintenance issues
-- -----------------------------------------------------
  CREATE OR REPLACE VIEW "last_transactions_joined" AS
    select
      ta.id,
      ta.transfer_id,
      ta.amount,
      ta.reason,
      ta.related_id,
      ta.date,
      tf.amount AS trfamount,
      m.type
    from tbmt_transaction as ta
    join tbmt_transfer as tf on ta.transfer_id = tf.id
    join tbmt_member as m on tf.member_id = m.id
    LIMIT 500;



