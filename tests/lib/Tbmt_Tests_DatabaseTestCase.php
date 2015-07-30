<?php

abstract class Tbmt_Tests_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase {
    /**
     * @var PropelPDO
     */
    static protected $propelCon = null;

    /**
     * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    static private $testCon = null;

    static public function setUpBeforeClass() {
        $con = Propel::getConnection();
        DbEntityHelper::setCon($con);
    }


    public function setUp() {
        parent::setUp();

        \Tbmt\SystemSetup::setCon(self::$propelCon);
        \Tbmt\SystemSetup::doSetupUnitTests();
    }

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection() {
        if ( self::$testCon === null ) {
            self::$propelCon = Propel::getConnection();

            $propelConfig = Propel::getConfiguration(PropelConfiguration::TYPE_ARRAY);
            $this->assertTrue(isset($propelConfig['datasources'][PROJECT_NAME]['connection']));

            $dbConfig = $propelConfig['datasources'][PROJECT_NAME]['connection'];
            $this->assertTrue(isset($dbConfig['dsn'], $dbConfig['database'], $dbConfig['user'], $dbConfig['password']));

            self::$testCon = $this->createDefaultDBConnection(new PDO(
                $dbConfig['dsn'],
                $dbConfig['user'],
                $dbConfig['password']
            ), $dbConfig['database']);
        }

        return self::$testCon;
    }

    protected function getDataSet() {
        return $this->createArrayDataSet([
            MemberPeer::TABLE_NAME => [],
            TransferPeer::TABLE_NAME => [],
            TransactionPeer::TABLE_NAME => []
        ]);
    }

    /**
     * Returns the database operation executed in test setup.
     *
     * @return PHPUnit_Extensions_Database_Operation_DatabaseOperation
     */
    protected function getSetUpOperation() {
        $op = new PHPUnit_Extensions_Database_Operation_Truncate();
        $op->setCascade();
        return $op;
    }

    protected function setUps() {
        if ( self::$propelCon === null )
            self::$propelCon = Propel::getConnection();
    }
}