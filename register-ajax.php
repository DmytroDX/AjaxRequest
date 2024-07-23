<?php
// main-js - назва файлу, в якому буде необхідний ajax скрипт
// loadMoreRequest - назва об'єкта, до якої будемо звертатися в скрипті для отримання значень з масиву
wp_localize_script('main-js', 'loadMoreRequest', [
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'ajaxNonce' => wp_create_nonce('loadmore_post_nonce'),
]);