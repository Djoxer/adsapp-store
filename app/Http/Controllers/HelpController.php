<?php
namespace App\Http\Controllers;

class HelpController extends Controller
{
    public function index()
    {
        return view('help.index');
    }

    public function privacy()
    {
        return view('help.privacy');
    }
}
