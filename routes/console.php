<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('telescope:prune')->monthly();
Schedule::command('activitylog:clean --force')->monthly();
