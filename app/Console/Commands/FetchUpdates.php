<?php

namespace App\Console\Commands;

use Auth;
use Carbon\Carbon;
use App\ProductKeywordList;
use Illuminate\Console\Command;
use Askedio\Laravelcp\Models\User;
use App\Scripts\ProductAdvertisingFacade;

class FetchUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        echo("fetching api results... ");
        //|--------------------------------------------
        //|    Search for unranked products first
        //|--------------------------------------------
        //|
        //|
        $paApi = new ProductAdvertisingFacade();

        // For testing purposes
//        $product = ProductKeywordList::first();
//        $paApi->setKeys( User::getRandomKeys() );
//
//        echo("kw = " . $product->keyword . "<BR>");
//        echo("kw = " . $product->product . "<BR>");
//        $results = $paApi->findRankForProductAndKeyword($product->product, $product->keyword, $product->search_index);
//        echo("<pre>");
//        print_r($results);
//        $product->saveApiResults($results);
//        exit;
        // End testing stuff

        $unRanList = ProductKeywordList::findUnranAsinsAndKeywords();

        if (count($unRanList) != 0) {
            foreach ($unRanList as $product) {
//            $results = $paApi->findRankForProduct('B00CAZAU62', 'zzz');

                $paApi->setKeys( User::getRandomKeys() );

                echo("kw = " . $product->keyword . "<BR>");
                echo("kw = " . $product->product . "<BR>");

                if ($product->search_index != '') {
                    echo("test");
                    $results = $paApi->findRankForProductAndKeyword($product->product, $product->keyword, $product->search_index, $product->location);
                    $product->saveApiResults($results);
                }

            }
        }

        //|--------------------------------------------
        //|  Then search for previously ranked products
        //|--------------------------------------------
        //|
        //|

        // Find products not run in the past x minutes
        $list = ProductKeywordList::where('updated_at', '<', Carbon::now()->subHours(24)->toDateTimeString())->get();

        if (count($list) != 0) {
            foreach ($list as $product) {

                $paApi->setKeys( User::getRandomKeys() );

                echo("kw = " . $product->keyword . "<BR>");
                echo("kw = " . $product->product . "<BR>");
                $results = $paApi->findRankForProductAndKeyword($product->product, $product->keyword, $product->search_index, $product->location);
                $product->saveApiResults($results);
            }
        }

        echo(" done");
    }
}
