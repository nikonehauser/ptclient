
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
    "zip_code" VARCHAR(80) NOT NULL ,
    "country" VARCHAR(80) NOT NULL ,
    "age" smallint not null ,
    "referrer_id" INTEGER NULL , -- this is the never changing member who recruited this member
    "parent_id" INTEGER NULL , -- this is the changing parent who receives provisions for my recruitings
    "signup_date" timestamp with time zone NOT NULL ,
    "paid_date" timestamp with time zone NULL ,
    "funds_level" smallint NOT NULL default 1,

    "bank_recipient" VARCHAR(120) NOT NULL ,
    "iban" VARCHAR(80) NOT NULL ,
    "bic" VARCHAR(80) NOT NULL ,

    "type" smallint NOT NULL default 0 ,

    "bonus_ids" varchar(80) not null default '',
    "bonus_level" double precision not null default 0 ,

    "advertised_count" int NOT NULL default 0 ,
    "outstanding_advertised_count" int NOT NULL default 0 ,

    "password" varchar(80) NOT NULL ,

    "transferred_total" varchar(255) NOT NULL default '[]' ,
    "outstanding_total" varchar(255) NOT NULL default '[]' ,

    "deletion_date" timestamp with time zone NULL ,

    "sub_promoter_referral" INTEGER NULL ,

    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_member_referrer"
      FOREIGN KEY ("referrer_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_member_parent"
      FOREIGN KEY ("parent_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_member_sub_promoter_referral"
      FOREIGN KEY ("sub_promoter_referral")
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
    "currency" varchar(3) not null ,
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
  CREATE INDEX idx_transfer_currency ON "tbmt_transfer" (currency);

-- -----------------------------------------------------
-- Table "transaction"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_transaction" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_transaction" (
    "id" bigserial NOT NULL ,
    "transfer_id" int NOT NULL ,
    "amount" double precision NOT NULL default 0 ,
    "reason" smallint not null default 0 ,
    "purpose" VARCHAR(255) not null default '',
    "related_id" int null ,
    "date" timestamp with time zone NOT NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_transaction_transfer"
      FOREIGN KEY ("transfer_id")
      REFERENCES "tbmt_transfer" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  CREATE INDEX idx_transaction_related_id ON "tbmt_transaction" (related_id);

-- -----------------------------------------------------
-- Table "reserved_paid_event"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_reserved_paid_event" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_reserved_paid_event" (
    "unpaid_id" int NOT NULL ,
    "paid_id" int NOT NULL ,
    "currency" varchar(3) not null ,
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
    "meta" TEXT NOT NULL DEFAULT '[]',
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
-- Table "system_stats"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_system_stats" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_system_stats" (
    "id" serial NOT NULL ,
    "invitation_incrementer" VARCHAR(10) NOT NULL default "2A15F6",
    "signup_count" int NOT NULL default 0,
    "member_count" int NOT NULL default 0,
    "starter_count" int NOT NULL default 0,
    "pm_count" int NOT NULL default 0,
    "ol_count" int NOT NULL default 0,
    "vl_count" int NOT NULL default 0,
    PRIMARY KEY ("id")
  );

-- -----------------------------------------------------
-- Table "unknow_income"
-- -----------------------------------------------------
  -- DROP TABLE IF EXISTS "tbmt_unknow_income" CASCADE;

  -- CREATE TABLE IF NOT EXISTS  "tbmt_unknow_income" (
  --   "id" bigserial NOT NULL ,
  --   "action" VARCHAR(160) NOT NULL,
  --   "type" SMALLINT NOT NULL ,
  --   "date" timestamp with time zone NOT NULL ,
  --   "related_id" VARCHAR(64) NULL DEFAULT NULL,
  --   "related_member_num" int NULL DEFAULT NULL,
  --   "meta" TEXT NULL,
  --   PRIMARY KEY ("id")
  -- );

-- -----------------------------------------------------
-- Table "currency"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_currency" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_currency" (
    "name" VARCHAR(128) NOT NULL ,
    "alphabetic_code" VARCHAR(3) NOT NULL,
    "numeric_code" VARCHAR(3) NOT NULL,
    "minor_unit" SMALLINT NOT NULL ,
    PRIMARY KEY ("alphabetic_code")
  );

  ALTER TABLE ONLY tbmt_currency
    ADD CONSTRAINT "currency_numeric_code_UNIQUE" UNIQUE (numeric_code);

-- -----------------------------------------------------
-- Table "activity"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_activity" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_activity" (
    "id" bigserial NOT NULL ,
    "action" smallint NOT NULL,
    "type" SMALLINT NOT NULL ,
    "date" timestamp with time zone NOT NULL ,
    "member_id" INT NULL DEFAULT NULL,
    "related_id" INT NULL DEFAULT NULL,
    "meta" TEXT NULL,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_activity_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE set null
      ON UPDATE CASCADE
  );

  CREATE INDEX idx_activity_related_id ON "tbmt_activity" (member_id);

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
      m.type,
      m.id as memId,
      m.num
    from tbmt_transaction as ta
    join tbmt_transfer as tf on ta.transfer_id = tf.id
    join tbmt_member as m on tf.member_id = m.id
    LIMIT 500;



