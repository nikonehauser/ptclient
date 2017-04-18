<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_mail' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Mail extends BaseMail
{
    const STATUS_NONE = 0;
    const STATUS_INCIDENT = 1;
    const STATUS_ERROR = 2;
    const STATUS_SEND = 3;

    public function hasIncidents() {
      return $this->getHasIncidents() > 0;
    }

    public function addIncident($err) {
      $incidents = json_decode($this->getIncidents(), true);
      if ( !$incidents )
        $incidents = [];

      $incidents[] = [time(), $err];

      $this->setIncidents(json_encode($incidents));
      $this->setHasIncidents(count($incidents));
      return $this;
    }
}
