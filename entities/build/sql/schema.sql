
-----------------------------------------------------------------------
-- tbmt_activity
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_activity" CASCADE;

CREATE TABLE "tbmt_activity"
(
    "id" bigserial NOT NULL,
    "action" VARCHAR(160) NOT NULL,
    "type" INT2 NOT NULL,
    "date" TIMESTAMP NOT NULL,
    "related_id" VARCHAR(64),
    "related_member_num" INTEGER,
    "meta" TEXT,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- tbmt_bonus_transaction
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_bonus_transaction" CASCADE;

CREATE TABLE "tbmt_bonus_transaction"
(
    "id" bigserial NOT NULL,
    "member_id" INTEGER NOT NULL,
    "transaction_id" INTEGER NOT NULL,
    "purpose" VARCHAR(255) NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- tbmt_currency
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_currency" CASCADE;

CREATE TABLE "tbmt_currency"
(
    "name" VARCHAR(128) NOT NULL,
    "alphabetic_code" VARCHAR(3) NOT NULL,
    "numeric_code" VARCHAR(3) NOT NULL,
    "minor_unit" INT2 NOT NULL,
    PRIMARY KEY ("alphabetic_code"),
    CONSTRAINT "currency_numeric_code_UNIQUE" UNIQUE ("numeric_code")
);

-----------------------------------------------------------------------
-- tbmt_invitation
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_invitation" CASCADE;

CREATE TABLE "tbmt_invitation"
(
    "id" serial NOT NULL,
    "hash" VARCHAR(64) NOT NULL,
    "member_id" INTEGER NOT NULL,
    "type" INT2 NOT NULL,
    "free_signup" INT2 NOT NULL,
    "creation_date" TIMESTAMP NOT NULL,
    "accepted_date" TIMESTAMP,
    "accepted_member_id" INTEGER,
    PRIMARY KEY ("id","hash")
);

-----------------------------------------------------------------------
-- tbmt_member
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_member" CASCADE;

CREATE TABLE "tbmt_member"
(
    "id" serial NOT NULL,
    "first_name" VARCHAR(80) NOT NULL,
    "last_name" VARCHAR(80) NOT NULL,
    "num" serial NOT NULL,
    "email" VARCHAR(80) NOT NULL,
    "title" VARCHAR(80) NOT NULL,
    "city" VARCHAR(80) NOT NULL,
    "country" VARCHAR(80) NOT NULL,
    "age" INT2 NOT NULL,
    "referer_id" INTEGER,
    "parent_id" INTEGER,
    "signup_date" TIMESTAMP NOT NULL,
    "paid_date" TIMESTAMP,
    "funds_level" INT2 DEFAULT 1 NOT NULL,
    "bank_recipient" VARCHAR(120) NOT NULL,
    "iban" VARCHAR(80) NOT NULL,
    "bic" VARCHAR(80) NOT NULL,
    "type" INT2 DEFAULT 0 NOT NULL,
    "bonus_ids" VARCHAR(80) DEFAULT '' NOT NULL,
    "advertised_count" INTEGER DEFAULT 0 NOT NULL,
    "outstanding_advertised_count" INTEGER DEFAULT 0 NOT NULL,
    "password" VARCHAR(80) NOT NULL,
    "transferred_total" VARCHAR(255) DEFAULT '[]' NOT NULL,
    "outstanding_total" VARCHAR(255) DEFAULT '[]' NOT NULL,
    "deletion_date" TIMESTAMP,
    PRIMARY KEY ("id"),
    CONSTRAINT "member_num_UNIQUE" UNIQUE ("num")
);

-----------------------------------------------------------------------
-- tbmt_reserved_paid_event
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_reserved_paid_event" CASCADE;

CREATE TABLE "tbmt_reserved_paid_event"
(
    "unpaid_id" INTEGER NOT NULL,
    "paid_id" INTEGER NOT NULL,
    "currency" VARCHAR(3) NOT NULL,
    "date" TIMESTAMP NOT NULL,
    PRIMARY KEY ("unpaid_id","paid_id")
);

-----------------------------------------------------------------------
-- tbmt_system_stats
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_system_stats" CASCADE;

CREATE TABLE "tbmt_system_stats"
(
    "id" serial NOT NULL,
    "signup_count" INTEGER DEFAULT 0 NOT NULL,
    "member_count" INTEGER DEFAULT 0 NOT NULL,
    "starter_count" INTEGER DEFAULT 0 NOT NULL,
    "pm_count" INTEGER DEFAULT 0 NOT NULL,
    "ol_count" INTEGER DEFAULT 0 NOT NULL,
    "vl_count" INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- tbmt_transaction
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_transaction" CASCADE;

CREATE TABLE "tbmt_transaction"
(
    "id" bigserial NOT NULL,
    "transfer_id" INTEGER NOT NULL,
    "amount" DOUBLE PRECISION DEFAULT 0 NOT NULL,
    "reason" INT2 DEFAULT 0 NOT NULL,
    "purpose" VARCHAR(255) DEFAULT '' NOT NULL,
    "related_id" INTEGER,
    "date" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

CREATE INDEX "idx_transaction_related_id" ON "tbmt_transaction" ("related_id");

-----------------------------------------------------------------------
-- tbmt_transfer
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_transfer" CASCADE;

CREATE TABLE "tbmt_transfer"
(
    "id" serial NOT NULL,
    "member_id" INTEGER NOT NULL,
    "amount" DOUBLE PRECISION DEFAULT 0 NOT NULL,
    "currency" VARCHAR(3) NOT NULL,
    "state" INT2 DEFAULT 0 NOT NULL,
    "attempts" INT2 DEFAULT 0 NOT NULL,
    "execution_date" TIMESTAMP,
    "processed_date" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE INDEX "idx_transfer_currency" ON "tbmt_transfer" ("currency");

CREATE INDEX "idx_transfer_state" ON "tbmt_transfer" ("state");

-----------------------------------------------------------------------
-- tbmt_unknow_income
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_unknow_income" CASCADE;

CREATE TABLE "tbmt_unknow_income"
(
    "id" bigserial NOT NULL,
    "action" VARCHAR(160) NOT NULL,
    "type" INT2 NOT NULL,
    "date" TIMESTAMP NOT NULL,
    "related_id" VARCHAR(64),
    "related_member_num" INTEGER,
    "meta" TEXT,
    PRIMARY KEY ("id")
);

ALTER TABLE "tbmt_invitation" ADD CONSTRAINT "fk_invitation_accepted_member"
    FOREIGN KEY ("accepted_member_id")
    REFERENCES "tbmt_member" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE "tbmt_invitation" ADD CONSTRAINT "fk_invitation_member"
    FOREIGN KEY ("member_id")
    REFERENCES "tbmt_member" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE "tbmt_member" ADD CONSTRAINT "fk_member_parent"
    FOREIGN KEY ("parent_id")
    REFERENCES "tbmt_member" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE "tbmt_member" ADD CONSTRAINT "fk_member_referer"
    FOREIGN KEY ("referer_id")
    REFERENCES "tbmt_member" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE "tbmt_reserved_paid_event" ADD CONSTRAINT "fk_reserved_paid_event_paid_member"
    FOREIGN KEY ("paid_id")
    REFERENCES "tbmt_member" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE "tbmt_reserved_paid_event" ADD CONSTRAINT "fk_reserved_paid_event_unpaid_member"
    FOREIGN KEY ("unpaid_id")
    REFERENCES "tbmt_member" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE "tbmt_transaction" ADD CONSTRAINT "fk_transaction_transfer"
    FOREIGN KEY ("transfer_id")
    REFERENCES "tbmt_transfer" ("id")
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE "tbmt_transfer" ADD CONSTRAINT "fk_transfer_member"
    FOREIGN KEY ("member_id")
    REFERENCES "tbmt_member" ("id")
    ON UPDATE CASCADE
    ON DELETE SET NULL;
