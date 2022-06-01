<?php

namespace App\Http\Controllers;

use App\Charts\WorkerOrderChart;
use App\Models\User;
use App\Repository\User\UserInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private UserInterface $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserInterface $repository)
    {
        $this->middleware('auth');
        $this->repository = $repository;
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(Request $request,WorkerOrderChart $chart)
    {
        $users = $this->repository->getDayUsers($request->get('wish_day',date('l')));
        $chart = $chart->build();
        return view('home', compact('users','chart'));
    }
}
