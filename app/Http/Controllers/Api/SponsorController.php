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
        $ad = Sponsor::active()
            ->with('media')
            ->where('type', $type)
            ->inRandomOrder()
            ->first();

        return response()->json($ad);
    }
}
