<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        $ads = Sponsor::active()->with('media')->get();
        return response()->json($ads);
    }

    public function getRandom($type = 'banner')
    {
        $ads = Sponsor::active()
            ->where('type', $type)
            ->with('media')
            ->inRandomOrder()
            ->get();

        // Return all active ads of this type (frontend can rotate/display them)
        // For backward compat: if only one ad, return it directly
        if ($ads->count() === 1) {
            return response()->json($ads->first());
        }

        return response()->json($ads);
    }
}
