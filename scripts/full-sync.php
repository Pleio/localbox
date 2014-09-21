<?php
/**
 * Perform a full synchronisation of users, groups and subsites
 *
 * @package Localbox
 */

// Configure with "main site". Needed so subsite_manager can identify our instance.
$_SERVER["HTTP_HOST"] = "pleio.localhost.nl";

// Fetch entities paginated.
$pageSize = 100;

require_once(dirname(dirname(dirname(__FILE__))) . "/../engine/start.php");

if (php_sapi_name() !== 'cli') {
  throw new Exception('This script must be run from the CLI.');
}

if (!is_plugin_enabled('localbox')) {
  throw new Exception('Plugin localbox has to be enabled');
}

$lox = new LoxSyncer();

$usersCount = get_data("SELECT COUNT(*) FROM {$CONFIG->dbprefix}users_entity");
echo("[Users] Migrating all users...\n");

for ($page = 0; $page <= ceil($usersCount/$pageSize); $page++) {
  $offset = $page*$pageSize;
  $users = get_data("SELECT username,name,email FROM {$CONFIG->dbprefix}users_entity LIMIT $offset, $pageSize");
  foreach ($users as $user) {
    $lox->updateUser($user);
  }
}

$sites = subsite_manager_get_subsites(0);
$sitesCount = count($sites);
$siteCount = 1;

foreach ($sites as $site) {
  echo("[Subsite " . $siteCount . "/" . $sitesCount . "] " . html_entity_decode($site->name, ENT_QUOTES) . "\n");
  $lox->updateGroup($site);

  $membersCount = $site->getMembers(array('count'=>true));

  for ($page = 0; $page <= ceil($membersCount/$pageSize); $page++) {
    $members = $site->getMembers(array('limit'=>$pageSize,'offset'=>$page*$pageSize));
    foreach ($members as $member) {
      $lox->addUserToGroup($member, $site);
    }
  }

  $groups = elgg_get_entities(array('limit'=>0,'type'=>'group','site_guid'=>$site->guid));

  foreach ($groups as $group) {
    $lox->updateGroup($group);

    $membersCount = $group->getMembers(0,0,true);

    for ($page = 0; $page <= ceil($membersCount/$pageSize); $page++) {
      $members = $group->getMembers($pageSize,$page*$pageSize);
      foreach ($members as $member) {
        $lox->addUserToGroup($member, $group);
      }
    }
  }

  $siteCount++;
}
