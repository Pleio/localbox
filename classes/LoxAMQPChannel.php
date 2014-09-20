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
    $this->connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
    $this->channel = $this->connection->channel();  
    $this->channel->queue_declare('localbox', false, true, false, false);
  }

  public function __destruct() {
    $this->channel->close();
    $this->connection->close();
  }

  public function publishMessage($data) {
    $message = new AMQPMessage($data);
    return $this->channel->basic_publish($message, '', 'localbox');
  }

}