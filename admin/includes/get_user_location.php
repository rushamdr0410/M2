<?php
function getClientIP() {
    $ip = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getUserLocation() {
    $ip = getClientIP();
    
    // For localhost testing, use a default IP
    if ($ip == '127.0.0.1' || $ip == '::1') {
        $ip = '8.8.8.8'; // Default to Google's DNS IP for testing
    }
    
    // Debug output
    error_log("Getting location for IP: " . $ip);
    
    $url = "http://ip-api.com/json/" . $ip;
    
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log("Curl error: " . curl_error($ch));
            curl_close($ch);
            return null;
        }
        
        curl_close($ch);
        
        if ($response) {
            $data = json_decode($response, true);
            error_log("API Response: " . print_r($data, true));
            
            if ($data && isset($data['status']) && $data['status'] == 'success') {
                return [
                    'ip' => $ip,
                    'latitude' => $data['lat'],
                    'longitude' => $data['lon'],
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'region' => $data['regionName'],
                    'zip' => isset($data['zip']) ? $data['zip'] : '',
                    'timezone' => $data['timezone']
                ];
            } else {
                error_log("API returned error status: " . print_r($data, true));
            }
        }
    } catch (Exception $e) {
        error_log("Error getting location: " . $e->getMessage());
    }
    
    return null;
}

// Function to update user location in database
function updateUserLocation($connection, $user_id, $location_data) {
    if ($location_data && $user_id) {
        // Debug output
        error_log("Updating location for user ID: " . $user_id);
        error_log("Location data: " . print_r($location_data, true));
        
        $latitude = mysqli_real_escape_string($connection, $location_data['latitude']);
        $longitude = mysqli_real_escape_string($connection, $location_data['longitude']);
        $city = mysqli_real_escape_string($connection, $location_data['city']);
        $country = mysqli_real_escape_string($connection, $location_data['country']);
        $region = mysqli_real_escape_string($connection, $location_data['region']);
        $zip = mysqli_real_escape_string($connection, $location_data['zip']);
        $timezone = mysqli_real_escape_string($connection, $location_data['timezone']);
        
        $query = "UPDATE register SET 
            latitude = '$latitude',
            longitude = '$longitude',
            city = '$city',
            country = '$country',
            region = '$region',
            zip = '$zip',
            timezone = '$timezone',
            last_location_update = NOW()
            WHERE id = '$user_id'";
            
        error_log("Update query: " . $query);
        
        $result = mysqli_query($connection, $query);
        if (!$result) {
            error_log("MySQL Error: " . mysqli_error($connection));
        }
        return $result;
    }
    error_log("Invalid location data or user ID");
    return false;
}
?> 