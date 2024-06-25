function logAction(user_id, category, action, affected_data) {
    // Retrieve device and browser information
    var ua = navigator.userAgent;
    var device = '';
    var device_model = ''; // Initialize device model

    // Determine device type
    if (/Android/i.test(ua)) {
        device = 'Android';
        // Extract device model if available
        var match = ua.match(/Android\s([^\s;]+)/);
        if (match) {
            device_model = match[1];
        }
    } else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
        device = 'iOS';
        // Extract device model if available
        var match = ua.match(/(iPad|iPhone|iPod);[\w\s]+(?:\s([\w\s]+))?/);
        if (match) {
            device_model = match[2] || match[1];
        }
    } else {
        device = 'Desktop';
        // No specific device model for desktops typically
    }

    var browser_info = bowser.getParser(ua);
    var browser_name = browser_info.getBrowserName();
    var browser_version = browser_info.getBrowserVersion();

    // Retrieve user's IP address using IPinfo.io service
    $.ajax({
        url: 'https://ipinfo.io/json',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var userIP = response.ip;
            var organization = response.org;

            // Prepare data for insertion into tbl_Logs
            var logData = {
                user_id: user_id,
                category: category,
                action: action,
                affected_data: affected_data,
                device: device,
                device_model: device_model || null, // Add device model if available
                browser: browser_name + ' ' + browser_version,
                latitude:  null,
                longitude: null,
                location: response.city + ', ' + response.region + ', ' + response.country, // Use response fields directly
                ip_address: userIP,
                organization: organization || null, // Handle organization as null if not available
                time_stamp: new Date().toISOString().slice(0, 19).replace('T', ' ') // Ensure timestamp format
            };

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
        },
        error: function(error) {
            console.error('Error fetching user IP:', error);
            // Handle error case if needed
        }
    });
}
