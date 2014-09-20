<?php
  /**
  * Localbox
  *
  * @package localbox
  * @author Bart Jeukendrup
  * @link http://www.infty.io/
  */

require_once __DIR__ . '/vendor/autoload.php';
require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/cron.php");
require_once(dirname(__FILE__) . "/lib/events.php");

require_once(dirname(__FILE__) . "/classes/LoxAMQPChannel.php");
require_once(dirname(__FILE__) . "/classes/LoxSyncer.php");

function localbox_init() {

  // Schedule cron changes
  elgg_register_plugin_hook_handler('cron', 'daily', 'localbox_cron');

  // Watch for user changes
  elgg_register_event_handler("create", "user", "localbox_update_user_event_handler", 100);
  elgg_register_event_handler("update", "user", "localbox_update_user_event_handler", 100);
  elgg_register_event_handler("delete", "user", "localbox_delete_user_event_handler", 100);

  // Watch for subsite changes
  elgg_register_event_handler("create", "site", "localbox_update_group_event_handler", 100);
  elgg_register_event_handler("update", "site", "localbox_update_group_event_handler", 100);
  elgg_register_event_handler("delete", "site", "localbox_delete_group_event_handler", 100);

  elgg_register_event_handler("create", "member_of_site", "localbox_join_group_event_handler", 100);
  elgg_register_event_handler("delete", "member_of_site", "localbox_leave_group_event_handler", 100);

  // Watch for group changes
  elgg_register_event_handler("create", "group", "localbox_update_group_event_handler", 100);
  elgg_register_event_handler("update", "group", "localbox_update_group_event_handler", 100);
  elgg_register_event_handler("delete", "group", "localbox_delete_group_event_handler", 100);

  elgg_register_event_handler("create", "member", "localbox_join_group_event_handler", 100);
  elgg_register_event_handler("delete", "member", "localbox_leave_group_event_handler", 100);

}
 
elgg_register_event_handler('init', 'system', 'localbox_init');
