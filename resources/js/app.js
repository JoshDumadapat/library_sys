import "./bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";

import "bootstrap/dist/js/bootstrap.bundle.min.js";

import $ from 'jquery';
import 'select2';

$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Search or select an option',
        tags: true, // Allow adding new options
        tokenSeparators: [',', ' '], // Allow multi-select
        ajax: {
            url: '/api/fetch-options', // Adjust URL to fetch options dynamically
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data.items
                };
            }
        }
    });
});
