<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('process:user-updates')->cron('*/6 * * * *');
