<?php

function displayToast($message, $type = 'error') {
    $bgColor = $type == 'error' ? 'bg-danger' : 'bg-success';
    $strongText = $type == 'error' ? 'Error' : 'Success';

    echo "
    <div class='toast position-absolute top-0 end-0 m-3' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='15000'>
        <div class='toast-header $bgColor text-white'>
            <strong class='me-auto'>$strongText</strong>
            <small class='text-muted'>Just now</small>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='toast' aria-label='Close'></button>
        </div>
        <div class='toast-body'>
            $message
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(event) { 
            var toastEl = document.querySelector('.toast');
            var toast = new bootstrap.Toast(toastEl, {delay: 15000});
            toast.show();
        });
    </script>";
}


?>
