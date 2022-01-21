<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DiDom\Document;
use Symfony\Component\Panther\Client;

class ParseNewsGoogleTrends extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-news-google-trends';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсит последние новости с гугл трендов';

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
        $url = 'https://trends.google.ru/trends/trendingsearches/realtime?geo=RU&category=all';


        $client = Client::createChromeClient();        
        $client->request('GET', $url);

        $crawler = $client->waitForVisibility('.feed-load-more-button');
        $html = $crawler->html();

        $document = new Document($html);
        $news = $document->find('.summary-text a');
        
        echo COUNT($news) . "\n\n\n\n";

        foreach ($news as $new) {
            echo $new->href. "\n\n";
            echo $new->text(). "\n\n\n\n\n";
        }

        return 0;
    }
}
