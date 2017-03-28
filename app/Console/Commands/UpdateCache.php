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
					
					if($dep == null){continue;}
					
					$d = explode('-',$dep->departure_date);
					$n = explode('-',Carbon::now()->toDateString());
					
					$date = Carbon::create($d[0],$d[1],$d[2],0);
					$now = Carbon::create($n[0],$n[1],$n[2],0);
				
					if($date->gte($now) == false){continue;}
				
					$checkCP = CachedPrice::where([
						'id_package'=>$v->id_package,
						'id_room_category'=>$v->id_room_category,
						'id_meal_plan'=>$v->id_meal_plan,
						'departure_date'=>$dep->departure_date,
						'gross'=>$v->gross,
						'tax'=>$v->tax,
						'soap_client'=>$v->soap_client
					])->first();
					
					if($checkCP == null){continue;}
					
					$validTo = $date->addDays($dep->duration);
					
					$checkPS = PriceSet::where([
						'id'=>$checkCP->id_price_set,
						'valid_from'=>$dep->departure_date,
						'valid_to'=>$validTo,
					])->first();
				
					if($checkPS == null){continue;}
				
					$latestPS = PriceSet::orderBy('id','DESC')->first()->id;
				
					$ps = new PriceSet;
					$ps->id = $latestPS + 1;
					$ps->valid_from = $dep->departure_date;
					$ps->valid_to = $validTo;
					$ps->soap_client = $v->soap_client;
					$ps->label = 'Search';
					$ps->description = 'Search';
					$ps->is_local = 0;
					$ps->save();
					
					
					$c = new CachedPrice;
					$c->id_package = $v->id_package;
					$c->id_room_category = $v->id_room_category;
					$c->id_price_set = $latestPS + 1;
					$c->id_meal_plan = $v->id_meal_plan;
					$c->departure_date = $dep->departure_date;
					$c->gross = $v->gross;
					$c->tax = $v->tax;
					$c->currency = 0;
					$c->soap_client = $v->soap_client;
					$c->save();
					
					
					
				}
				
				$this->comment('Update finished.');
    }
}
