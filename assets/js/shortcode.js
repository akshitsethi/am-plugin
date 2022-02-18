/**
 * shortcode.js
 *
 * Fetch info via an AJAX call
 */
 function convertEpochToSpecificTimezone(timeEpoch, offset) {
    var d = new Date(timeEpoch);
    var utc = d.getTime() + (d.getTimezoneOffset() * 60000);  // Converts to UTC 00:00
    var nd = new Date(utc + (3600000 * offset));
    return nd.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) + ' ' + nd.toLocaleTimeString('en-US');
}

(function($) {
    'use strict';

    const table_head = $('#amplugin-data-table table thead');
    const table_body = $('#amplugin-data-table table tbody');

    $.ajax({
        type: 'get',
        url: amplugin_l10n.ajaxurl,
        data: {
            action: amplugin_l10n.prefix + 'fetch_data',
            _nonce: amplugin_l10n.nonce
        },
        error: function(xhr, status, error) {
            if (status === 'error') {
                // Wrap this around an error
                table_body.append('<tr><td colspan="5">' + amplugin_l10n.error_text + '</td></tr>');
            }
        }
    }).done(function(data) {
        if (data.response.status === 'success') {
            var headers = data.response.data?.data?.headers;
            var rows = data.response.data?.data?.rows;

            if (data.response.data.title) {
                $('#amplugin-data-table').prepend('<h3>' + data.response.data.title + '</h3><br>');
            }

            if (Array.isArray(headers)) {
                headers.forEach(header => {
                    table_head.append('<th>' + header + '</th>');
                });
            }

            if (typeof rows === 'object') {
                for (const row in rows) {
                    var human_date = convertEpochToSpecificTimezone(rows[row].date * 1000, 0);

                    table_body.append('<tr>');
                    table_body.append('<td>' + rows[row].id + '</td>');
                    table_body.append('<td>' + rows[row].fname + '</td>');
                    table_body.append('<td>' + rows[row].lname + '</td>');
                    table_body.append('<td>' + rows[row].email + '</td>');
                    table_body.append('<td>' + human_date + '</td>');
                    table_body.append('</tr>');
                }
            }
        } else {
            table_body.append('<tr><td colspan="5">' + data.response.message + '</td></tr>');
        }
    });
})(jQuery);
