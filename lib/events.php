<?php
function localbox_create_user_event_handler($event, $object_type, $user) {
  localbox_create_user($user);
}

function localbox_update_user_event_handler($event, $object_type, $user) {
  localbox_update_user($user);
}

function localbox_remove_user_event_handler($event, $object_type, $user) {
  localbox_remove_user($user);
}