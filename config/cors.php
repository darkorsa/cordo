<?php

return [
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => [
        "X-Requested-With", "Content-Type", "Origin", "Authorization", "Accept", "Client-Security-Token"
    ],
    "headers.expose" => ["Etag"],
    "credentials" => true,
    "cache" => 86400
];
