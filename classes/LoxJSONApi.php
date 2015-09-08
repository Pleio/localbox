<?php
class LoxJSONApi {

    public function getFile() {

    }

    public function getUser() {
        $user = elgg_get_logged_in_user_entity();

        $response = array(
            'name' => $user->username,
            'public_key' => '',
            'private_key' => ''
        );

        return $this->sendResponse($response);
    }

    public function getRegisterApp() {
        $site = elgg_get_site_entity();
        $user = elgg_get_logged_in_user_entity();

        $response = array(
            'BaseUrl' => $site->url,
            'Name' => $site->name,
            'User' => $user->username,
            'LogoUrl' => $site->url . "mod/localbox/_graphics/whitebox.png",
            'BackColor' => '#008BCD',
            'FontColor' => '#999999',
            'pin_cert' => 'MIIE7DCCA9SgAwIBAgISESGh1PAAG6vFhXc/3pEUriBMMA0GCSqGSIb3DQEBCwUAMGAxCzAJBgNVBAYTAkJFMRkwFwYDVQQKExBHbG9iYWxTaWduIG52LXNhMTYwNAYDVQQDEy1HbG9iYWxTaWduIERvbWFpbiBWYWxpZGF0aW9uIENBIC0gU0hBMjU2IC0gRzIwHhcNMTQxMDMwMDg0NzA1WhcNMTYwMzAzMDkzMDMyWjA4MSEwHwYDVQQLExhEb21haW4gQ29udHJvbCBWYWxpZGF0ZWQxEzARBgNVBAMMCioucGxlaW8ubmwwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDQts/ZBdbH0/q4zMPVd9FXNjwlOvM8wPiTNukOE3ZAFuAxdQllM8bG6uXmLsCl60vhqC210YbuOP44I7voXiIYflb+S4dbBhbEtVlbOlFbU9NLwAG/HpTyejWhqouL0SERyFCdkZ3kV3W6n1VzrlRZHkLEE6C+lV9uzOw4XoZ1xZBnLb0LBdYJ8/euSB3lqRxTZvyrFIgsIn3WUjukMI9ewqh6GHiTqadghx638uIxDG0QVbWuiN1frawv1MwJ+Ttxq6TIOSyTfOH/nXLCMg3xjawganSVqRDvuurlJMOZspvFe7G7HyNWR/9AoZ+y8Vwaz2k1Lp24Phuoz6jMuLdVAgMBAAGjggHGMIIBwjAOBgNVHQ8BAf8EBAMCBaAwSQYDVR0gBEIwQDA+BgZngQwBAgEwNDAyBggrBgEFBQcCARYmaHR0cHM6Ly93d3cuZ2xvYmFsc2lnbi5jb20vcmVwb3NpdG9yeS8wHwYDVR0RBBgwFoIKKi5wbGVpby5ubIIIcGxlaW8ubmwwCQYDVR0TBAIwADAdBgNVHSUEFjAUBggrBgEFBQcDAQYIKwYBBQUHAwIwQwYDVR0fBDwwOjA4oDagNIYyaHR0cDovL2NybC5nbG9iYWxzaWduLmNvbS9ncy9nc2RvbWFpbnZhbHNoYTJnMi5jcmwwgZQGCCsGAQUFBwEBBIGHMIGEMEcGCCsGAQUFBzAChjtodHRwOi8vc2VjdXJlLmdsb2JhbHNpZ24uY29tL2NhY2VydC9nc2RvbWFpbnZhbHNoYTJnMnIxLmNydDA5BggrBgEFBQcwAYYtaHR0cDovL29jc3AyLmdsb2JhbHNpZ24uY29tL2dzZG9tYWludmFsc2hhMmcyMB0GA1UdDgQWBBR15BlE5btP83ESgUSfRwfJUPCXcTAfBgNVHSMEGDAWgBTqTnzUgC3lFYGGJoyCbcCYpM+XDzANBgkqhkiG9w0BAQsFAAOCAQEARP9KLzX8a8JNb6gw/TksLhQSbibM2EyYlqCxIO2DNk6Rje2NH/knERP61zn1JPgp2x73qEnS2XhepYuO1ZVvUShlVFObk8EFiCRDZKykIt7VkteU+kbFc/6lIp+73Pda20A6VahnXwGHjeD5Hx1s77itqjoC2AT68Qh578cQfKIdpAI9d05cS0h7wsRFJIWT+UPuoWUYHdwrflUtl344+gN36og1vz0K+BO5smbFqxK8cgQB+xiJtaVF4fASS18MUAY/g7IC1KQWVe5WVVr3f++LyxkOdzTds23n1xupwqPJVlyKprOBUl26ncKxKS97NaTk+lBW+KvXPtuDDM/DFg==',
            'APIKeys' => array(array(
                'Name' => 'LocalBox iOS',
                'Key' => 'testclient',
                'Secret' => 'testpass'

            ), array(
                'Name' => 'Localbox Android',
                'Key' => 'testclient',
                'Secret' => 'testpass'
            ))
        );

        return $this->sendResponse($response);
    }

    public function postFile() {

    }

    public function getOperations() {

    }

    public function getInvitations() {
        // Pleio does not support invitations for folders
        $return = array();
        return $this->sendResponse($return, 200);
    }

    public function getMeta($url = array()) {
        $db_prefix = elgg_get_config('dbprefix');
        $user = elgg_get_logged_in_user_entity();

        if (count($url) === 0) {
            $title = 'Home';
            $children = $user->getGroups("", 50);
            $parent_path = '/';
        } else {
            $guid = array_slice($url, -1)[0];
            $folder = get_entity($guid);
            $parent_path = '/' . implode('/', $url);

            if ($folder instanceof ElggGroup) {
                $title = $folder->name;
                $children = elgg_get_entities(array(
                    'type' => 'object',
                    'subtype' => array('folder', 'file'),
                    'container_guid' => $folder->guid,
                    'joins' => "JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid",
                    'order_by' => 'oe.title ASC',
                    'limit' => false
                ));
            } else {
                $title = $folder->title;
                $children = elgg_get_entities_from_relationship(array(
                    'type' => 'object',
                    'subtype' => array('folder', 'file'),
                    'limit' => false,
                    'relationship' => FILE_TOOLS_RELATIONSHIP,
                    'relationship_guid' => $folder->guid
                ));
            }
        }

        $return = array();
        $return['title'] = $title;
        $return['path'] = $parent_path;
        $return['modified_at'] = date('c');
        $return['is_dir'] = true;
        $return['is_share'] = false;
        $return['has_keys'] = false;
        $return['children'] = array();

        foreach ($children as $child) {
            $attributes = array();

            if ($parent_path == '/') {
                $attributes['path'] = $parent_path . $child->guid;
            } else {
                $attributes['path'] = $parent_path . '/' . $child->guid;
            }

            if (isset($child->name)) {
                $attributes['title'] = $child->name;
            } else {
                $attributes['title'] = $child->title;
            }

            if ($child instanceof ElggFile) {
                $attributes['is_dir'] = false;
                $attributes['is_share'] = false;
                $attributes['size'] = 888055;
                $attributes['revision'] = 1;
                $attributes['icon'] = 'jpg';
            } else {
                $attributes['is_dir'] = true;
                $attributes['is_share'] = true;
                $attributes['has_keys'] = false;
                $attributes['icon'] = 'folder';
            }

            $return['children'][] = $attributes;
        }

        return $this->sendResponse($return, 200);
    }

    public function sendResponse($data, $status = 200) {

        if ($status != 200) {
            http_response_code($status);
        }

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        return true;
    }
}