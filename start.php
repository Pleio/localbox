<?php
/**
* Localbox
*
* @package localbox
* @author Bart Jeukendrup
* @link http://www.infty.io/
*/

require_once(dirname(__FILE__) . "/../../vendor/autoload.php");

require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/cron.php");
require_once(dirname(__FILE__) . "/lib/events.php");

require_once(dirname(__FILE__) . "/classes/LoxAMQPChannel.php");
require_once(dirname(__FILE__) . "/classes/LoxSyncer.php");

function localbox_init() {
    // Schedule cron changes
    elgg_register_plugin_hook_handler('cron', 'daily', 'localbox_cron');

    // Watch for user changes
    elgg_register_event_handler("update", "user", "localbox_update_user_event_handler", 100);
    elgg_register_event_handler("delete", "user", "localbox_delete_user_event_handler", 100);

    // Watch for subsite changes
    elgg_register_event_handler("update", "site", "localbox_update_group_event_handler", 100);
    elgg_register_event_handler("delete", "site", "localbox_delete_group_event_handler", 100);

    elgg_register_event_handler("create", "member_of_site", "localbox_join_group_event_handler", 100);
    elgg_register_event_handler("delete", "member_of_site", "localbox_leave_group_event_handler", 100);

    // Watch for group changes
    elgg_register_event_handler("update", "group", "localbox_update_group_event_handler", 100);
    elgg_register_event_handler("delete", "group", "localbox_delete_group_event_handler", 100);

    elgg_register_event_handler("create", "member", "localbox_join_group_event_handler", 100);
    elgg_register_event_handler("delete", "member", "localbox_leave_group_event_handler", 100);

    elgg_register_page_handler("oauth", "localbox_oauth_page_handler");
    elgg_register_page_handler("lox_api", "localbox_api_page_handler");
    elgg_register_page_handler("register_app", "localbox_register_app_handler");
}

elgg_register_event_handler('init', 'system', 'localbox_init');

function localbox_oauth_page_handler($url) {
    $interface = new LoxOAuth2Interface();
    $server = $interface->getServer();

    $request = OAuth2\Request::createFromGlobals();
    $response = new OAuth2\Response();

    if ($url[0] != "v2") {
        return false;
    }

    switch ($url[1]) {
        case "auth":

            if (!elgg_is_logged_in()) {
                $_SESSION['last_forward_from'] = $_SERVER[REQUEST_URI];
                forward('/login');
            }

            if ($_POST['submit']) {
                $response = $server->handleAuthorizeRequest($request, $response, true, elgg_get_logged_in_user_guid());
                $response->send();
            } else {
                echo "<form method=\"POST\" action=\"/oauth/v2/auth\"><input type=\"hidden\" name=\"client_id\" value=\"" . get_input('client_id') . "\"><input type=\"hidden\" name=\"response_type\" value=\"" . get_input('response_type') . "\"><input type=\"hidden\" name=\"redirect_uri\" value=\"" . get_input('redirect_uri') . "\"><input type=\"submit\" name=\"submit\" value=\"OK\"></form>";
            }
            return true;
            break;
        case "token":
            $request = OAuth2\Request::createFromGlobals();
            $response = new OAuth2\Response();

            $server->handleTokenRequest($request)->send();
            return true;
            break;
    }

    return false;
}

function localbox_register_app_handler($url) {

    if (!elgg_is_logged_in()) {
        $_SESSION['last_forward_from'] = $_SERVER[REQUEST_URI];
        forward('/login');
    }

    $api = new LoxJSONApi();
    return $api->getRegisterApp();
}

function localbox_parse_path($path) {
    $path = str_replace('//','/', $path);
    return array_slice(explode('/', $path), 1);
}

function localbox_api_page_handler($url) {
    $interface = new LoxOAuth2Interface();
    $server = $interface->getServer();

    if (!elgg_is_logged_in()) {
        if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            http_response_code(403);
            die;
        }

        $token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());

        $user = get_user($token['user_id']);
        if ($user) {
            login($user);
        } else {
            die;
        }
    }

    $api = new LoxJSONApi();

    switch ($url[0]) {
        case "files":
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $api->getFile($url[1], array_slice($url, 2));
            } else {
                $api->createFile($url[1], array_slice($url, 2));
            }

            break;
        case "meta":
            if (isset($url[1])) {
                $container_guid = $url[1];
            } else {
                $container_guid = 0;
            }

            $api->getMeta($container_guid, array_slice($url,2));
            break;
        case "operations":

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                exit;
            }

            switch ($url[1]) {
                case "create_folder":
                    $path = localbox_parse_path(get_input('path'));
                    $api->createFolder($path[0], array_slice($path, 1));
                    break;
                case "move":
                    $from_path = localbox_parse_path(get_input('from_path'));
                    $to_path = localbox_parse_path(get_input('to_path'));
                    $api->move($from_path[0], array_slice($from_path, 1), $to_path[0], array_slice($to_path, 1));
                case "delete":
                    $path = localbox_parse_path(get_input('path'));
                    $api->delete($path[0], array_slice($path, 1));
            }
            break;
        case "invitations":
            $api->getInvitations();
            break;
        case "user":
            $api->getUser();
            break;
    }

    return true;
}