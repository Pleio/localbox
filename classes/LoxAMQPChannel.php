<?php
/**
 * AMQP Channel connection
 *
 * @package Localbox
 */

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class LoxAMQPChannel {

  protected $connection = null;
  protected $channel = null;
  protected $exchange = null;

  public function __construct() {
    global $CONFIG;

    try {
      $this->connection = new AMQPConnection($CONFIG->amqp_host, 5672, $CONFIG->amqp_user, $CONFIG->amqp_pass, $CONFIG->amqp_vhost);
      $this->channel = $this->connection->channel();  
      $this->channel->queue_declare($CONFIG->amqp_queue, false, true, false, false);
    } catch (Exception $exception) {
      $this->connection = false;
    }

  }

  public function __destruct() {
    if ($this->connection) {
      $this->channel->close();
      $this->connection->close();
    }
  }

  public function publishMessage($data) {
    if ($this->connection) {
      $message = new AMQPMessage($data);
      return $this->channel->basic_publish($message, '', 'localbox');      
    }
  }

}