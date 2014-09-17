<?php
/**
 * Localbox synchronizer
 *
 * @package Localbox
 */

class LoxSyncer {
  protected $channel = null;

  public function __construct() {
    $this->channel = new LoxAMQPChannel();
  }

  public function updateUser(ElggUser $user) {
    $data = array(
      'type' => 'user',
      'guid' => $user->username . '@www.pleio.nl',
      'name' => $user->name,
      'email' => $user->email,
      'action' => 'update'
    );

    $this->channel->publishMessage(json_encode($data));
  }

  public function deleteUser(ElggUser $user) {
    $data = array(
      'type' => 'user',
      'guid' => $user->username . '@www.pleio.nl',
      'action' => 'delete'
    );

    $this->channel->publishMessage(json_encode($data));
  }

  public function updateGroup(ElggGroup $group) {
    $data = array(
      'type' => 'group',
      'guid' => $group->guid,
      'name' => $group->name,
      'action' => 'update'
    );

    $this->channel->publishMessage(json_encode($data));    
  }

  public function deleteGroup(ElggGroup $group) {
    $data = array(
      'type' => 'group',
      'guid' => $group->guid,
      'action' => 'delete'
    );

    $this->channel->publishMessage(json_encode($data));  
  }

  public function updateSubsite(Subsite $subsite) {
    // @todo: fill in
  }

  public function deleteSubsite(Subsite $subsite) {
    // @todo: fill in
  }

  public function addUserToGroup(ElggUser $user, ElggGroup $group) {
    $data = array( 
      'type' => 'user_group',
      'user_guid' => $user->username . '@www.pleio.nl',
      'group_guid' => $group->guid,
      'action' => 'add'
    );

    $this->channel->publishMessage(json_encode($data));  
  }

  public function deleteUserFromGroup(ElggUser $user, ElggGroup $group) {
    $data = array( 
      'type' => 'user_group',
      'user_guid' => $user->username . '@www.pleio.nl',
      'group_guid' => $group->guid,
      'action' => 'delete'
    );

    $this->channel->publishMessage(json_encode($data));  
  }

}