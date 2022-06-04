<?php

namespace App\Charts;

use App\Models\Worker;
use App\Models\WorkerUser;
use ArielMejiaDev\LarapexCharts\BarChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class WorkerOrderChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): BarChart
    {
        $workers = Worker::query()->select(['id', 'name'])->addSelect([
            'orders_count' => WorkerUser::selectRaw('count(*)')
                ->where('user_status',  WorkerUser::USER_STATUS['success'])
                ->whereColumn('workers.id', 'worker_user.worker_id')
                ->whereDate('created_at', today())
                ->limit(1),
            'orders_success_count' => WorkerUser::selectRaw('count(*)')
                ->where('user_status',WorkerUser::USER_STATUS['success'])
                ->where('order_status',WorkerUser::ORDER_STATUS['success'])
                ->whereColumn('workers.id', 'worker_user.worker_id')
                ->whereDate('created_at', today())
                ->limit(1),
        ])->get();

        $names = $workers->pluck('name')->values()->toArray() ?? [];
        $orders_count = $workers->pluck('orders_count')->values()->toArray() ?? [];
        $orders_success_count = $workers->pluck('orders_success_count')->values()->toArray() ?? [];

        return $this->chart->barChart()
            ->setDataset([])
            ->setSubtitle(date('Y-m-d'))
            ->setTitle(__('main.worker_vs_orders'))
            ->setToolbar(true)
            ->setDataLabels(true)
            ->addData(__('main.orders_success_count'), $orders_success_count)
            ->addData(__('main.orders_count'), $orders_count)
            ->setXAxis($names);
    }
}
