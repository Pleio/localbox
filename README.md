Pleio Localbox synchronisation
==============================
This plugin offers synchronisation of users, groups and subsites to Localbox through a RabbitMQ instance. When the plugin is enabled, deltas are send to Localbox. Also the plugin enables a Localbox widget that allows a user to access his Localbox instance.

Installation
------------
1. Configure RabbitMQ parameters in engine/settings.php:

      $CONFIG->amqp_host = 'localhost';
      $CONFIG->amqp_user = 'guest';
      $CONFIG->amqp_pass = 'guest';
      $CONFIG->amqp_lox_queue = 'localbox';
      $CONFIG->amqp_vhost = 'pleio';

1. Enable the plugin localbox, and make sure the plugin is also enabled for all subsites.
2. Use the script to provision a full update.

Now the plugin automatically sends all deltas into RabbitMQ. Use the Pleio bundle in Localbox to process the queue.