<?php
/**
 * Site batch
 *
 * @package Localbox
 */

class ElggBatchSite {

  protected $groups = array();

  public function getAll() {
    global $CONFIG;
    $this->sites = get_data("SELECT * from {$CONFIG->dbprefix}sites_entity");

    return $this->sites;
  }

}