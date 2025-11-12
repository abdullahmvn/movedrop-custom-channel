<?php

namespace App\Enums;

enum WebhookEventEnum: string
{
    case PRODUCT_DELETED = 'product.deleted';
    case ORDER_CREATED = 'order.created';
    case ORDER_UPDATED = 'order.updated';
}
