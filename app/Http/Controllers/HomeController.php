<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = Setting::query()
            ->whereIn('key', ['institution_name', 'logo_path'])
            ->pluck('value', 'key');

        return view('home', [
            'institutionName' => $settings->get('institution_name', 'Puskot Jogja'),
            'logoPath' => $settings->get('logo_path'),
        ]);
    }
}
