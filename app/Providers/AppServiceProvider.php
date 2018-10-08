<?php

namespace App\Providers;

use App\Services\BuildTableField;
use App\Services\StoreDataService;
use App\Services\PushNotificationService;
use App\Services\UserService;
use App\Services\StaffService;
use App\Services\ContractService;
use App\Services\VacationService;
use App\Services\SendEmailService;
use App\Services\NotificationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('storeDataService', function () {
            return new StoreDataService();
        });

        $this->app->singleton('buildTableField', function () {
            return new BuildTableField();
        });
        $this->app->singleton('pushNotificationService', function () {
            return new PushNotificationService();
        });
        $this->app->singleton('userService', function(){
            return new UserService();
        });
        $this->app->singleton('staffService', function(){
            return new StaffService();
        });
        $this->app->singleton('contractService', function(){
            return new ContractService();
        });
        $this->app->singleton('vacationService', function(){
            return new VacationService();
        });
        $this->app->singleton('notificationService', function(){
            return new NotificationService();
        });
        $this->app->singleton('sendEmailService', function(){
            return new SendEmailService();
        });
    }
}
