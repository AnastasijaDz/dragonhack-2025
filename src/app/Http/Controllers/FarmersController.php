<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmersController extends Controller
{
    public function index()
    {
        $farmers = Farmer::all();
        $farmer = Farmer::find(2);
        return view('farmers.index', compact('farmers'));
    }

    public function create() {}

    public function store() {}
}
