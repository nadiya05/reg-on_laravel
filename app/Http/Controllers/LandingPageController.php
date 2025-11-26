<?php

namespace App\Http\Controllers;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('admin.LandingPage.landing_page');
    }
}

