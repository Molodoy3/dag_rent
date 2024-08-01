<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('account-update', function () {
    return true;
});
