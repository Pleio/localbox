<?php
  /**
  * Localbox
  *
  * @package localbox
  * @author Bart Jeukendrup
  * @link http://www.infty.io/
  */

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
  elgg_register_event_handler("delete", "user", "localbox_remove_user_event_handler", 100);

  //localbox_sync_all_users();
}
 
elgg_register_event_handler('init', 'system', 'localbox_init');
