<?php

namespace App\Http\Controllers;

use App\Models\TraceabilityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TraceabilityController extends Controller
{
    /**
     * Decode QR Token and display detailed public food safety & origin journey.
     */
    public function show(string $token): Response
    {
        $log = TraceabilityLog::with(['commodity.cooperative'])
            ->where('qr_code_token', $token)
            ->firstOrFail();

        return Inertia::render('Trace', [
            'log' => $log,
            'commodity' => $log->commodity,
            'cooperative' => $log->commodity->cooperative,
        ]);
    }
}
