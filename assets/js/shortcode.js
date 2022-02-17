/**
 * shortcode.js
 *
 * Fetch info via an AJAX call
 */
(function($) {
    'use strict';

    let table_head = $('#amplugin-data-table thead');
    let table_body = $('#amplugin-data-table tbody');

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

            if (Array.isArray(headers)) {
                headers.forEach(header => {
                    table_head.append('<th>' + header + '</th>');
                });
            }

            if (typeof rows === 'object') {
                for (const row in rows) {
                    let human_date = new Date(rows[row].date);

                    table_body.append('<tr>');
                    table_body.append('<td>' + rows[row].id + '</td>');
                    table_body.append('<td>' + rows[row].fname + '</td>');
                    table_body.append('<td>' + rows[row].lname + '</td>');
                    table_body.append('<td>' + rows[row].email + '</td>');
                    table_body.append('<td>' + human_date.toLocaleTimeString('en-US', { hour12: true }) + '</td>');
                    table_body.append('</tr>');
                }
            }
        } else {
            table_body.append('<tr><td colspan="5">' + data.response.message + '</td></tr>');
        }
    });
})(jQuery);
