<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //这里是所有的任务调度设置
        //服务器端添加crontab  * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
        //分　时　日　月　周
        //第1列表示分钟1～59 每分钟用*或者 */1表示
        //第2列表示小时1～23（0表示0点）
        //第3列表示日期1～31
        //第4列表示月份1～12
        //第5列标识号星期0～6（0表示星期天）












    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
