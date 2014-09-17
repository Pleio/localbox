<?php
/**
 * Hook to ELGG events
 *
 * @package Localbox
 */

function localbox_update_user_event_handler($event, $object_type, $user) {
  $lox = new LoxSyncer();
  $lox->updateUser($user);
}

function localbox_remove_user_event_handler($event, $object_type, $user) {
  $lox = new LoxSyncer();
  $lox->deleteUser($user);
}