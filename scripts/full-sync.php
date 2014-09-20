<?php
/**
 * Perform a full synchronisation of users, groups and subsites
 *
 * @package Localbox
 */

// Configure with "main site". Needed so subsite_manager can identify our instance.
$_SERVER["HTTP_HOST"] = "pleio.localhost.nl";

require_once(dirname(dirname(dirname(__FILE__))) . "/../engine/start.php");

if (php_sapi_name() !== 'cli') {
  throw new Exception('This script must be run from the CLI.');
}

function get_user_entities_as_row() {
  global $CONFIG;
  return get_data("SELECT username,name,email from {$CONFIG->dbprefix}users_entity");
}

$users = get_user_entities_as_row();

foreach ($users as $user) {
  $lox = new LoxSyncer();
  $lox->updateUser($user);
}

$sites = subsite_manager_get_subsites(0);
foreach ($sites as $site) {
  $lox = new LoxSyncer();
  $lox->updateGroup($site);

  $members = $site->getMembers(array('limit'=>0));
  foreach ($members as $member) {
    $lox = new LoxSyncer();
    $lox->addUserToGroup($member, $site);
  }

  $groups = elgg_get_entities(array('limit'=>0,'type'=>'group','site_guid'=>$site->guid));
  foreach ($groups as $group) {
    $lox = new LoxSyncer();
    $lox->updateGroup($group);

    $members = $group->getMembers(0);
    foreach ($members as $member) {
      $lox = new LoxSyncer();
      $lox->addUserToGroup($member, $group);
    }
  }
}