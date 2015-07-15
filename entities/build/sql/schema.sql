
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
    "advertised_count" INTEGER DEFAULT 0 NOT NULL,
    "outstanding_advertised_count" INTEGER DEFAULT 0 NOT NULL,
    "password" VARCHAR NOT NULL,
    "transferred_total" DOUBLE PRECISION DEFAULT 0 NOT NULL,
    "outstanding_total" DOUBLE PRECISION DEFAULT 0 NOT NULL,
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
    "date" TIMESTAMP NOT NULL,
    PRIMARY KEY ("unpaid_id","paid_id")
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
    "related_id" INTEGER,
    "date" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- tbmt_transfer
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_transfer" CASCADE;

CREATE TABLE "tbmt_transfer"
(
    "id" serial NOT NULL,
    "member_id" INTEGER NOT NULL,
    "amount" DOUBLE PRECISION DEFAULT 0 NOT NULL,
    "state" INT2 DEFAULT 0 NOT NULL,
    "attempts" INT2 DEFAULT 0 NOT NULL,
    "execution_date" TIMESTAMP,
    "processed_date" TIMESTAMP,
    PRIMARY KEY ("id")
);

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
