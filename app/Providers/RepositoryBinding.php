<?php

use App\Repository\Admin\AdminInterface;
use App\Repository\Admin\AdminRepository;
use App\Repository\Car\CarInterface;
use App\Repository\Car\CarRepository;
use App\Repository\Plan\PlanInterface;
use App\Repository\Plan\PlanRepository;
use App\Repository\SingleRequest\SingleRequestInterface;
use App\Repository\SingleRequest\SingleRequestRepository;
use App\Repository\User\UserInterface;
use App\Repository\User\UserRepository;
use App\Repository\UserPlan\UserPlanInterface;
use App\Repository\UserPlan\UserPlanRepository;
use App\Repository\Worker\WorkerInterface;
use App\Repository\Worker\WorkerRepository;
use App\Repository\WorkerUser\WorkerUserInterface;
use App\Repository\WorkerUser\WorkerUserRepository;

$this->app->bind(WorkerInterface::class, WorkerRepository::class);

$this->app->bind(UserInterface::class, UserRepository::class);
$this->app->bind(AdminInterface::class, AdminRepository::class);
$this->app->bind(CarInterface::class, CarRepository::class);
$this->app->bind(PlanInterface::class, PlanRepository::class);
$this->app->bind(SingleRequestInterface::class, SingleRequestRepository::class);
$this->app->bind(UserPlanInterface::class, UserPlanRepository::class);
$this->app->bind(WorkerUserInterface::class, WorkerUserRepository::class);
