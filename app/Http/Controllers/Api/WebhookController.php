<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebhookStoreRequest;
use App\Models\Webhook;

class WebhookController extends Controller
{
    public function store(WebhookStoreRequest $request)
    {
        Webhook::query()->insert($request->validated()['webhooks']);

        return response()->json([
            'message' => 'Webhooks Stored Successfully',
        ]);
    }
}
