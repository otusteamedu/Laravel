<?php

return [
    "enabled" => env('EXPORT_EXCEL_PRODUCT_ENABLED', true),
    "route_prefix" => env('EXPORT_EXCEL_PRODUCT_ROUTE_PREFIX', 'export'),
    "default_column" => env('EXPORT_EXCEL_PRODUCT_DEFAULT_COLUMN', ['id', 'title', 'price']),
];
