import './bootstrap';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();



toastr.options = {
    "closeButton": true,        // Show close button
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,        // Show progress bar
    "positionClass": "toast-top-right", // Position of the toast
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",         // Time to show notification (ms)
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",    // Animation for showing
    "hideMethod": "fadeOut"     // Animation for hiding
};
