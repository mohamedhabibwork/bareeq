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
        $users = $this->repository->getDayUsers($wish_day);
        $orders = app(SingleRequestRepository::class)->getDayOrders();
//        dd(compact('users','orders'));
        $chart = $chart->build();
        return view('home', compact('users', 'orders', 'chart'));
    }
}
