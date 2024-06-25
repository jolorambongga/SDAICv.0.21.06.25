function logAction(user_id, category, action, affected_data) {
    // Retrieve device and browser information
    var ua = navigator.userAgent;
    var device = '';
    if (/Android/i.test(ua)) {
        device = 'Android';
    } else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
        device = 'iOS';
    } else {
        device = 'Desktop';
    }

    var browser_info = bowser.getParser(ua);
    var browser_name = browser_info.getBrowserName();
    var browser_version = browser_info.getBrowserVersion();

    // Retrieve user's IP address from PHP endpoint
    $.ajax({
        url: '../admin/handles/users/get_user_ip.php',
        type: 'GET',
        success: function(response) {
            var userIP = response.ip_address;
            // Fetch geolocation information
            getLocation(userIP, function(locationInfo) {
                // Prepare data for insertion into tbl_Logs
                var logData = {
                    user_id: user_id,
                    category: category,
                    action: action,
                    affected_data: affected_data,
                    device: device,
                    device_model: '', // You can add device model if available
                    browser: browser_name + ' ' + browser_version,
                    latitude: null,
                    longitude: null,
                    location: null,
                    ip_address: userIP,
                    time_stamp: new Date().toISOString().slice(0, 19).replace('T', ' ') // Ensure timestamp format
                };

                // Process location information if available
                if (locationInfo && locationInfo.loc) {
                    var locParts = locationInfo.loc.split(',');
                    logData.latitude = locParts.length > 0 ? parseFloat(locParts[0]) : null;
                    logData.longitude = locParts.length > 1 ? parseFloat(locParts[1]) : null;
                    logData.location = locationInfo.city + ', ' + locationInfo.region + ', ' + locationInfo.country;
                }

                // Perform the insertion into tbl_Logs using another AJAX call or any other method you prefer
                $.ajax({
                    url: '../admin/handles/logs/create_log.php', // Replace with your endpoint to insert logs into MySQL
                    type: 'POST',
                    data: logData,
                    success: function(response) {
                        console.log('Log inserted successfully:', response);
                    },
                    error: function(error) {
                        console.error('Error inserting log:', error);
                        // Handle error if needed
                    }
                });
            });
        },
        error: function(error) {
            console.error('Error fetching user IP:', error);
            // Handle error case if needed
        }
    });

    // Function to fetch geolocation information from IP address using IPinfo.io service
    function getLocation(ip_address, callback) {
        var apiUrl = 'https://ipinfo.io/' + ip_address + '/json/';

        $.ajax({
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                callback(response);
            },
            error: function(error) {
                console.error('Error fetching location:', error);
                callback(null);
            }
        });
    }
}
