<?php

/**
 * Package functions
 *
 * @package Localbox
 */

/*
function localbox_sync_all_users() {
  $localboxUsers = localbox_rest_request("GET", "identities");
  $elggUsers = get_user_entities_as_rows();

  // Create new users
  foreach (array_udiff($elggUsers, $localboxUsers, 'localbox_compare_users') as $user) {
    localbox_create_user($user);
    exit();
  }

  // Update existing users on change
  foreach (array_intersect($localboxUsers, $elggUsers) as $user) {
    if ($localboxUsers[$user] != $elggUsers[$user]) {
      //localbox_update_user($user);
    }
  }

  // Remove users, enable this?
  foreach (array_diff($localboxUsers, $elggUsers) as $user) {
    //localbox_remove_user($user);
  }

}

function localbox_compare_users($elggUser, $localboxUser) {
  if ($elggUser->username == $localboxUser->username) {
    return 0;
  } else {
    return 1;
  }
}

function localbox_create_user(ElggUser $user) {

  $content = array(
    'username' => $user->username,
    'realname' => $user->name,
    'email' => $user->email
  );

  return localbox_rest_request("POST", "users", json_encode($content));
}

function localbox_remove_user(ElggUser $user) {
  return localbox_rest_request("DELETE", "users/" . $user->username);
}

function localbox_rest_request($method = "GET", $url, $content = null) {
  $rest = curl_init('http://localbox.jeu/app_dev.php/lox_api/' . $url);
  curl_setopt($rest, CURLOPT_USERPWD, 'api:api');
  curl_setopt($rest, CURLOPT_RETURNTRANSFER, true);

  switch ($method) {
    case "POST":
      curl_setopt($rest, CURLOPT_POST, 1);
      break;
    case "DELETE":
      curl_setopt($rest, CURLOPT_CUSTOMREQUEST, "DELETE");
      break;
    case "PUT":
      curl_setopt($rest, CURLOPT_CUSTOMREQUEST, "PUT");
      break;
  }

  if ($content) {
    curl_setopt($rest, CURLOPT_POSTFIELDS, $content);
  }

  $response = curl_exec($rest);
  curl_close($rest);

  if (!curl_errno($response)) {
    return json_decode($response);
  } else {
    error_log("Localbox error occured: " . curl_errno($response) . " " . $response);
    return false;
  }
}

function get_user_entities_as_rows() {
  global $CONFIG;
  return get_data("SELECT * from {$CONFIG->dbprefix}users_entity");
}
*/