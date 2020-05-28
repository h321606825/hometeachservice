<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Db::connection()->enableQueryLog();
Log::info('sql',DB::getQueryLog());
