<?php
/**
 * Hook to ELGG events
 *
 * @package Localbox
 */

function localbox_update_user_event_handler($event, $object_type, $user) {
  $lox = new LoxSyncer();
  return $lox->updateUser($user);
}

function localbox_delete_user_event_handler($event, $object_type, $user) {
  $lox = new LoxSyncer();
  return $lox->deleteUser($user);
}

function localbox_update_group_event_handler($event, $object_type, $group) {
  $lox = new LoxSyncer();
  return $lox->updateGroup($group);
}

function localbox_delete_group_event_handler($event, $object_type, $group) {
  $lox = new LoxSyncer();
  return $lox->deleteGroup($group);
}

function localbox_join_group_event_handler($event, $object_type, $relationship) {

  $from = get_entity($relationship->guid_one);
  $to = get_entity($relationship->guid_two);

  if ($from instanceof ElggUser && ($to instanceof ElggGroup | $to instanceof ElggSite)) {
    $lox = new LoxSyncer();
    return $lox->addUserToGroup($from, $to);
  }
}

function localbox_leave_group_event_handler($event, $object_type, $relationship) {
  $from = get_entity($relationship->guid_one);
  $to = get_entity($relationship->guid_two);

  if ($from instanceof ElggUser && ($to instanceof ElggGroup | $to instanceof ElggSite)) {
    $lox = new LoxSyncer();
    return $lox->deleteUserFromGroup($from, $to);
  }
}