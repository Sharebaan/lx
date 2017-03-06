<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Travel\CachedPrice;
use App\Models\Travel\PriceSet;
use App\Models\Travel\PackagesSearchCached;
use Carbon\Carbon;

class UpdateCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates global cache.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pCache = \DB::table('packages_search_cached_prices')->get();
				
				foreach($pCache as $v){
					
					$dep = PackagesSearchCached::where('id',$v->id_package_search)->first();
					
					$d = explode('-',$dep->departure_date);
					$n = explode('-',Carbon::now()->toDateString());
					
					$date = Carbon::create($d[0],$d[1],$d[2],0,0,0);
					$now = Carbon::create($n[0],$n[1],$n[2],0,0,0);
				
					if($date->gte($now) == false){continue;}
				
					$ps = new PriceSet;
					$ps->id = 2000;
					$ps->valid_from = $dep->departure_date;
					$ps->valid_to = $dep->departure_date;
					$ps->soap_client = $v->soap_client;
					$ps->label = 'Search';
					$ps->description = 'Search';
					$ps->is_local = 0;
					$ps->save();
					
					$c = new CachedPrice;
					$c->id_package = $v->id_package;
					$c->id_room_category = $v->id_room_category;
					$c->id_price_set = 2000;
					$c->id_meal_plan = $v->id_meal_plan;
					$c->departure_date = $dep->departure_date;
					$c->gross = $v->gross;
					$c->tax = $v->tax;
					$c->currency = 0;
					$c->soap_client = $v->soap_client;
					$c->save();
					
				}
    }
}
