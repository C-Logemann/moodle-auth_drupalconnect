<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

function drupalconnect_check($username, $password) {
    global $CFG, $DB;
    $serviceurl = get_config('auth/drupalconnect', 'serviceurl');
    $nodeid = get_config('auth/drupalconnect', 'nodeid');
    $secureswitch = get_config('auth/drupalconnect', 'secureswitch');

    $user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id));

    // data

    $data = array(
        'nid' => $nodeid,
        'moodlename' => $username,
        'pass' => $password,
    );
    //$parameters = http_build_query($data); // error with additional "1"

    $fields_string = '';
    foreach($data as $key => $value) {
        $fields_string .= $key .'='. urlencode($value) . '&';
        }
    rtrim($fields_string, '&');

    $request_url = $serviceurl .'?' . $fields_string;
  // echo $request_url;
    $headers = array(
      'Accept: application/json',
      'Content-Type: application/json',
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $request_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    //echo print_r($response);

    // Check if login was successful
    if ($http_code == 200) {
        // Convert json response as array
        //$logged_user = json_decode($response);
        $drupaluid = 0;
        $response_object = json_decode($response);
        if ($response_object->error == 'none') {
            $drupaluid = $response_object->uid;
        }

      //ToDo: Remove this testcode $secureswitch = 'off';
      if (is_numeric($drupaluid)) {

          if ($secureswitch == 'on') {
              if ($drupaluid == $user->idnumber) {
                  return true;
              }
          }
          else {
              if ($drupaluid > 0) {
                  return true;
              }
          }
      }
      // echo print_r($response_object);
    }
    else {
      // Get error msg
      //$http_message = curl_error($curl);
      //echo print_r($http_message);
    }

    return false;
}
