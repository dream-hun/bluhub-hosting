<?php

namespace App\Http\Controllers;

use App\Models\DomainPricing;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('id', '1');
        })->count();
        $tlds = DomainPricing::count();

        return view('home', ['users' => $users, 'tlds' => $tlds]);
    }
}
