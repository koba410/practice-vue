<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class DemoController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->orderBy('id')
            ->get();

        return view('demo.index', compact('users'));
    }
}
