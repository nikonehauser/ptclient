
-----------------------------------------------------------------------
-- tbmt_member
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "tbmt_member" CASCADE;

CREATE TABLE "tbmt_member"
(
    "id" serial NOT NULL,
    "first_name" VARCHAR(80) NOT NULL,
    "last_name" VARCHAR(80) NOT NULL,
    "member_num" serial NOT NULL,
    "email" VARCHAR(80) NOT NULL,
    "referer_member_num" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);
