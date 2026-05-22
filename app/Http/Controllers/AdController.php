<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index() { return view('ads.index'); }
    public function create() { return view('ads.create'); }
}
