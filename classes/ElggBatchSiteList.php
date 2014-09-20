<?php
/**
 * Site batch list
 *
 * @package Localbox
 */

class ElggBatchSiteList {

  protected $sites = array();

  public function getAll() {
    global $CONFIG;
    $this->sites = get_data("SELECT * from {$CONFIG->dbprefix}sites_entity");

    return $this->sites;
  }

}