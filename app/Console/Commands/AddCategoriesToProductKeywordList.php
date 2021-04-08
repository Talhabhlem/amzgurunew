<?php

namespace App\Console\Commands;

use App\Location;
use App\ProductKeywordList;
use Illuminate\Console\Command;
use Askedio\Laravelcp\Models\User;
use App\Scripts\ProductAdvertisingApi;

class AddCategoriesToProductKeywordList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backend:addCategoriesToProductKeywordList';

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
//        $user = Auth::user();

        $paApi = new ProductAdvertisingApi();

        //@todo getRandomKeys
        $paApi->setKeys( User::getRandomKeys() );
        $asins = ProductKeywordList::getAsinsWithoutSearchIndex();
        //@todo pass location
//        dd($asins);

        // Pull existing search indexes for each asin, if available
        $saveList = array();
        $asinSearchList = array();

        foreach($asins['asins'] as $index=>$asin) {

            $location = $asins['raw'][$index]->location;
//            echo("<pre>");
//            print_r($asins['raw'][$index]);

            if ($searchIndex = ProductKeywordList::findSearchIndexForAsin($asin, $location)) {
//                echo("test if");
                // IF we could get an existing search index, add it to the save list
                $saveList[] = ['asin' => $asin, 'searchIndex' => $searchIndex];
            } else {

                // If we couldn't get an existing search index, add asin to the list to search API
                $asinSearchList = [];
                $asinSearchList[] = $asin;

//                echo("<pre>");
//                print_r($asinSearchList);
//                echo("location = $location\n");
                // Lookup the ones that need to be looked up
                $locationData = Location::find($location);
//                echo("<pre>");
//                print_r($locationData);

                $paApi->setLocationData( $locationData );

                echo('getting serach indexes');
                $searchIndex = $paApi->ItemLookup( $asinSearchList )->getSearchIndexes();
                echo('done wtih search indexes');
//                echo("test");
//                echo("<pre>");
//                print_r($searchIndex);

                // Merge the looked up and existing records
                echo('merging serach index');
                foreach($searchIndex as $item) {
                    $saveList[] = $item;
                }
//                echo('test');
            }
        }

//        print_r($searchIndex);

        // Save the results
        echo('trying to save');
        ProductKeywordList::saveSearchIndexResults($saveList);
    }
}
