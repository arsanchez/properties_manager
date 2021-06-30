<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\Type;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SyncProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        // Calling the api and inserting the data
        $client = new Client();
        
        $page_number = 1;
        $next_page = true;
        $properties = [];
        $types = [];
        $rows_count = 0;

        $this->line('Syncing properties...');

        while ($next_page) {
            $response = $client->request('GET', 'https://trial.craig.mtcserver15.com/api/properties', [
                'query' => ['api_key' => '2S7rhsaq9X1cnfkMCPHX64YsWYyfe1he',
                            'page' => ['size' => 100, 'number' => $page_number]
                        ]
            ]);

            // Parsing the data
            $result = json_decode($response->getBody());
            $temp_data = $result->data;
            $temp_types = [];

            foreach ($temp_data as $key => $d) {
                $temp_types[] =  (array) $d->property_type;
                unset($d->property_type);
                $d->uuid = pack("h*", str_replace('-', '', $d->uuid));
                $temp_data[$key] =  (array)$d;
            }

            $properties = array_merge($properties, $temp_data);
            $types = array_merge($types, $temp_types);
            $page_number++;
            sleep(1);

            // Saving the data in batched of 200
            if (($page_number % 2 == 0) || $result->next_page_url == null) {
                Type::upsert($types, ['id'], ['title', 'description']);
                Property::upsert($properties, ['uiid'], ['price', 'type']);

                $rows_count += count($properties);
                $this->line('Inserting ' .$rows_count. ' property rows');
                $properties = [];
                $types = [];

            }
            
            // End of the line
            if ($result->next_page_url == null) {
                $next_page = false;
            }
        }

        // Saving the property types first
        
        $this->line('Sync finished');
        

        return 0;
    }
}
