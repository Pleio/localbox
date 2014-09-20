<?php
/**
 * User Batch 
 *
 * @package Localbox
 */

class ElggBatchUserList {

  protected $users = array();

  public function getAll() {
    global $CONFIG;
    $this->users = get_data("SELECT * from {$CONFIG->dbprefix}users_entity");

    return $this->users;
  }

}