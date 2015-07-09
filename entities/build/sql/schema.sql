
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
    "city" VARCHAR(80) NOT NULL,
    "country" VARCHAR(80) NOT NULL,
    "age" INT2 NOT NULL,
    "referer_num" INTEGER,
    "signup_date" TIMESTAMP NOT NULL,
    "paid" INT2 DEFAULT 0 NOT NULL,
    "funds_level" INT2 DEFAULT 1 NOT NULL,
    "bank_recipient" VARCHAR(120) NOT NULL,
    "iban" VARCHAR(80) NOT NULL,
    "bic" VARCHAR(80) NOT NULL,
    PRIMARY KEY ("id"),
    CONSTRAINT "member_num_UNIQUE" UNIQUE ("num")
);
