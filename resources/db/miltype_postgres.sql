-- -----------------------------------------------------
-- Table "member"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_member" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_member" (
    "id" serial NOT NULL ,
    "hash" VARCHAR(40) NOT NULL ,
    "first_name" VARCHAR(80) NOT NULL ,
    "last_name" VARCHAR(80) NOT NULL ,
    "num" SERIAL NOT NULL,
    "email" VARCHAR(80) NOT NULL ,
    "phone" VARCHAR(80) NOT NULL DEFAULT '',
    "title" VARCHAR(80) NOT NULL ,
    "street" VARCHAR(180) NOT NULL ,
    "street_add" VARCHAR(180) NOT NULL ,
    "city" VARCHAR(180) NOT NULL ,
    "zip_code" VARCHAR(80) NOT NULL ,
    "country" VARCHAR(180) NOT NULL ,
    "age" smallint not null ,
    "referrer_id" BIGINT NULL , -- this is the never changing member who recruited this member
    "parent_id" BIGINT NULL , -- this is the changing parent who receives provisions for my recruitings
    "signup_date" timestamp with time zone  NOT NULL ,
    "paid_date" timestamp with time zone  NULL ,
    "funds_level" smallint NOT NULL default 1,

    "free_invitation" smallint NOT NULL default 0,

    "bank_recipient" VARCHAR(180) NOT NULL ,
    "iban" VARCHAR(180) NOT NULL ,
    "bic" VARCHAR(100) NOT NULL ,
    "bank_name" VARCHAR(180) NOT NULL ,
    "bank_street" VARCHAR(180) NOT NULL ,
    "bank_zip_code" VARCHAR(180) NOT NULL ,
    "bank_city" VARCHAR(180) NOT NULL ,
    "bank_country" VARCHAR(180) NOT NULL ,

    "type" smallint NOT NULL default 0 ,

    "bonus_ids" TEXT NOT NULL,
    "bonus_level" double precision not null default 0 ,

    "advertised_count" int NOT NULL default 0 ,
    "outstanding_advertised_count" int NOT NULL default 0 ,

    "password" varchar(80) NOT NULL ,

    "deletion_date" timestamp with time zone  NULL ,

    "is_extended" smallint NOT NULL default 0 ,

    "sub_promoter_referral" BIGINT NULL ,

    "profile_version" int not null default 0 ,
    "transfer_freezed" smallint not null default 0 ,

    "hg_week" smallint not null default 1 ,

    "passportfile" VARCHAR(60) NULL ,
    "panfile" VARCHAR(60) NULL ,

    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_member_referrer"
      FOREIGN KEY ("referrer_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_member_parent"
      FOREIGN KEY ("parent_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_member_sub_promoter_referral"
      FOREIGN KEY ("sub_promoter_referral")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_member
    OWNER to miltype;

  ALTER TABLE tbmt_member
    ADD UNIQUE (num);

  ALTER TABLE tbmt_member
    ADD UNIQUE (email);

-- -----------------------------------------------------
-- Table "member_data"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_member_data" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_member_data" (
    "member_id" BIGINT NOT NULL ,
    "fee_reminder_email" smallint not null default 0 ,
    PRIMARY KEY ("member_id") ,
    CONSTRAINT "fk_member_data_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_member_data
    OWNER to miltype;

-- -----------------------------------------------------
-- Table "email_validation"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_email_validation" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_email_validation" (
    "id" serial NOT NULL ,
    "hash" varchar (64) not null ,
    "creationdate" integer not null ,
    "meta" TEXT NOT NULL,
    "accepted_date" timestamp with time zone NULL ,
    "member_id" BIGINT NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_email_validation_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_email_validation
    OWNER to miltype;

  ALTER TABLE tbmt_email_validation
    ADD UNIQUE (hash);


-- -----------------------------------------------------
-- Table "payout"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_payout" CASCADE;

  CREATE TABLE IF NOT EXISTS "tbmt_payout" (
    "id" serial NOT NULL ,
    "creation_date" timestamp with time zone NOT NULL ,
    "typew" varchar(64) NULL ,
    "masspay_file" varchar(200) NULL ,
    "download_count" int NOT NULL default 0 ,
    PRIMARY KEY ("id")
  );

  ALTER TABLE tbmt_payout
    OWNER to miltype;

-- -----------------------------------------------------
-- Table "transfer"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_transfer" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_transfer" (
    "id" serial NOT NULL ,
    "member_id" BIGINT NOT NULL ,
    "currency" varchar(3) not null ,
    "state" smallint not null default 0 ,
    "state_history" text NULL,
    "attempts" smallint not null default 0 ,
    "creation_date" timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "execution_date" timestamp with time zone NULL ,
    "execution_date_history" text NULL ,
    "payout_id" BIGINT NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_transfer_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_transfer_payout"
      FOREIGN KEY ("payout_id")
      REFERENCES "tbmt_payout" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_transfer
    OWNER to miltype;

  CREATE INDEX idx_transfer_state ON "tbmt_transfer" (state);
  CREATE INDEX idx_transfer_currency ON "tbmt_transfer" (currency);


-- -----------------------------------------------------
-- Table "transaction"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_transaction" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_transaction" (
    "id" serial NOT NULL ,
    "transfer_id" BIGINT NOT NULL ,
    "amount" double precision NOT NULL default 0 ,
    "reason" smallint not null default 0 ,
    "purpose" VARCHAR(255) not null default '',
    "related_id" int null ,
    "date" timestamp with time zone  NOT NULL ,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_transaction_transfer"
      FOREIGN KEY ("transfer_id")
      REFERENCES "tbmt_transfer" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_transaction
    OWNER to miltype;

  CREATE INDEX idx_transaction_related_id ON "tbmt_transaction" (related_id);

-- -----------------------------------------------------
-- Table "reserved_paid_event"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_reserved_paid_event" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_reserved_paid_event" (
    "unpaid_id" BIGINT NOT NULL ,
    "paid_id" BIGINT NOT NULL ,
    "is_free_invitation" int NOT NULL default 0,
    "currency" varchar(3) not null ,
    "date" timestamp with time zone  NOT NULL ,
    PRIMARY KEY ("unpaid_id", "paid_id") ,
    CONSTRAINT "fk_tbmt_reserved_paid_event_unpaid_member"
      FOREIGN KEY ("unpaid_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_tbmt_reserved_paid_event_paid_member"
      FOREIGN KEY ("paid_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_reserved_paid_event
    OWNER to miltype;

-- -----------------------------------------------------
-- Table "invitation"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_invitation" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_invitation" (
    "id" serial NOT NULL ,
    "hash" VARCHAR(64) NOT NULL,
    "member_id" BIGINT NOT NULL ,
    "type" smallint NOT NULL ,
    "free_signup" smallint NOT NULL ,
    "lvl2_signup" smallint NOT NULL ,
    "creation_date" timestamp with time zone  NOT NULL ,
    "accepted_date" timestamp with time zone  NULL ,
    "accepted_member_id" BIGINT NULL ,
    "meta" TEXT NOT NULL,
    PRIMARY KEY ("id", "hash") ,
    CONSTRAINT "fk_invitation_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE ,
    CONSTRAINT "fk_invitation_accepted_member"
      FOREIGN KEY ("accepted_member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_invitation
    OWNER to miltype;

  ALTER TABLE tbmt_invitation
    ADD UNIQUE (hash);

-- -----------------------------------------------------
-- Table "system_stats"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_system_stats" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_system_stats" (
    "id" serial NOT NULL ,
    "invitation_incrementer" VARCHAR(10) NOT NULL default '2A15F6',
    "invoice_number" int NOT NULL default 1,
    "signup_count" int NOT NULL default 0,
    "member_count" int NOT NULL default 0,
    "starter_count" int NOT NULL default 0,
    "pm_count" int NOT NULL default 0,
    "ol_count" int NOT NULL default 0,
    "vl_count" int NOT NULL default 0,
    PRIMARY KEY ("id")
  );

  ALTER TABLE tbmt_system_stats
    OWNER to miltype;

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

  ALTER TABLE tbmt_currency
    OWNER to miltype;

  ALTER TABLE tbmt_currency
    ADD UNIQUE (numeric_code);

-- -----------------------------------------------------
-- Table "activity"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_activity" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_activity" (
    "id" serial NOT NULL ,
    "action" smallint NOT NULL,
    "type" SMALLINT NOT NULL ,
    "date" timestamp with time zone  NOT NULL ,
    "member_id" BIGINT NULL DEFAULT NULL,
    "related_id" INT NULL DEFAULT NULL,
    "meta" TEXT NULL,
    "notified" SMALLINT NOT NULL  default 0,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_activity_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_activity
    OWNER to miltype;

  CREATE INDEX idx_activity_related_id ON "tbmt_activity" (member_id);


-- -----------------------------------------------------
-- Table "payment"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_payment" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_payment" (
    "id" serial NOT NULL ,
    "status" int NOT NULL ,
    "type" varchar(128) NOT NULL ,
    "date" timestamp with time zone  NOT NULL ,
    "member_id" BIGINT NOT NULL ,
    "invoice_number" varchar(128) NOT NULL ,
    "gateway_payment_id" varchar(255) ,
    "meta" TEXT NULL,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_payment_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_payment
    OWNER to miltype;

  ALTER TABLE tbmt_payment
    ADD UNIQUE (invoice_number);


-- -----------------------------------------------------
-- Table "payment_import"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_payment_import" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_payment_import" (
    "id" serial NOT NULL ,
    "filename" varchar(128) NOT NULL ,
    "creation_date" timestamp with time zone  NOT NULL ,
    "member_id" BIGINT NOT NULL ,
    "meta" TEXT NULL,
    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_payment_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_payment_import
    OWNER to miltype;



-- -----------------------------------------------------
-- Table "nonce"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_nonce" CASCADE;

  CREATE TABLE IF NOT EXISTS  "tbmt_nonce" (
    "nonce" varchar(255) NOT NULL ,
    "date" timestamp with time zone NOT NULL ,
    "member_id" BIGINT NOT NULL ,
    PRIMARY KEY ("nonce") ,
    CONSTRAINT "fk_nonce_member"
      FOREIGN KEY ("member_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_nonce
    OWNER to miltype;


-- -----------------------------------------------------
-- Table "payment"
-- -----------------------------------------------------
  DROP TABLE IF EXISTS "tbmt_mail" CASCADE;

  CREATE TABLE IF NOT EXISTS "tbmt_mail" (
    "id" serial NOT NULL ,
    "status" int NOT NULL default 0,
    "recipients" text NULL ,
    "froms" text NULL ,
    "reply_tos" text NULL ,
    "subject" text NULL ,
    "body" text NULL ,
    "creation_date" timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "attempts" int NOT NULL default 0,
    "incidents" text NULL ,
    "has_incidents" int NOT NULL default 0,
    "attach_content_as_zip" int NOT NULL default 0,
    "recipient_id" BIGINT NULL ,

    PRIMARY KEY ("id") ,
    CONSTRAINT "fk_recipient_id_member"
      FOREIGN KEY ("recipient_id")
      REFERENCES "tbmt_member" ("id")
      ON DELETE CASCADE
      ON UPDATE CASCADE
  );

  ALTER TABLE tbmt_mail
    OWNER to miltype;
