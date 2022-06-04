<?php

namespace App\Http\Controllers;

use App\Charts\WorkerOrderChart;
use App\Repository\SingleRequest\SingleRequestRepository;
use App\Repository\User\UserInterface;
use App\Repository\WorkerUser\WorkerUserRepository;
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
    public function index(Request $request, WorkerOrderChart $chart)
    {
        $wish_day = $request->get('wish_day', date('l'));
        // get users has wish today
        $users = $this->repository->getDayUsers($wish_day);
        // get single requests
        $requests = app(SingleRequestRepository::class)->getDayOrders();
        // orders
        $orders = app(WorkerUserRepository::class)->getDayOrders($request->date('date'));
        $chart = $chart->build();
        return view('home', compact('users', 'requests','orders', 'chart'));
    }
}
