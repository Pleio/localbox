<?php
/**
 * AMQP Channel connection
 *
 * @package Localbox
 */

class LoxAMQPChannel {

  protected $connection = null;
  protected $channel = null;
  protected $exchange = null;

  public function __construct() {
    $connection = new AMQPConnection();
    $connection->setLogin('pleio');
    $connection->setPassword('cDAb6sFp9oC');
    $connection->setVhost('localhost');
    $connection->connect();

    $channel = new AMQPChannel($connection);
    $exchange = new AMQPExchange($channel);

    $exchange->setName('localbox_queue');
    $exchange->setType('fanout');

    $this->connection = $connection;
    $this->channel = $channel;
    $this->exchange = $exchange;
  }

  public function __destruct() {
    $this->connection->close();
  }

  public function publishMessage($message = array()) {
    $this->exchange->publish($message, 'localbox');
  }

}