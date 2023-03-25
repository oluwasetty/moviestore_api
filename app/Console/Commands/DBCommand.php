<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use App\Models\Crew;
use App\Models\Principal;
use App\Models\Episode;
use App\Models\Alias;
use App\Models\Rating;
use App\Models\Performer;

class DBCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // returns all movies
            $movies = Movie::where('startYear', '2015')->limit(1000)->get();
            foreach ($movies as $movie) {
                $item = Crew::where('tconst', $movie->tconst)->first();
                if ($item) {
                    $item->delete();
                    $this->info("deleted $item");
                } else {
                    $this->info("item is null");
                }
                $item = Principal::where('tconst', $movie->tconst)->first();
                if ($item) {
                    $item->delete();
                    $this->info("deleted $item");
                } else {
                    $this->info("item is null");
                }
                $item = Alias::where('titleId', $movie->tconst)->first();
                if ($item) {
                    $item->delete();
                    $this->info("deleted $item");
                } else {
                    $this->info("item is null");
                }
                $item = Rating::where('tconst', $movie->tconst)->first();
                if ($item) {
                    $item->delete();
                    $this->info("deleted $item");
                } else {
                    $this->info("item is null");
                }
                $epis = Episode::where('parentTconst', $movie->tconst)->get();
                foreach ($epis as $item) {
                    if ($item) {
                        $item->delete();
                        $this->info("deleted $item");
                    } else {
                        $this->info("item is null");
                    }
                }
                $perfs = Performer::where('knownForTitles', '%LIKE%', $movie->tconst)->get();
                foreach ($perfs as $item) {
                    if ($item) {
                        $item->delete();
                        $this->info("deleted $item");
                    } else {
                        $this->info("item is null");
                    }
                }
                $item = Movie::where('tconst', $movie->tconst)->first();
                if ($item) {
                    $item->delete();
                    $this->info("deleted $item");
                } else {
                    $this->info("item is null");
                }
            }
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }

    public function delete(){
        
    }
}
