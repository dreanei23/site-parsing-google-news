<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Facades\Parser;

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
     * Парсим новости с гугл трендов и добавляем новые ссылки на новости в таблицу news
     *
     * @return int
     */
    public function handle()
    {
        //парсим новости гугл тренда
        $news = Parser::parseGoogleTrendNews();

        if (!empty($news)) {
            foreach ($news as $new) {
                $parsed_news[] = $new->href;
            }
            
            //проверяем каких новостей из спарсенных у нас нет
            $parsed_news = collect($parsed_news); //сделаем коллекцию из массива
            $new_news = $parsed_news->diff(News::all()->pluck('source_url')); 
     
            
            $new_news = [
                'http://sport.bigmir.net/football/england/1977524-Mikolenko-ne-popal-v-zajavku-Evertona-na-match-s-Aston-Villoj',
                // 'https://sportarena.com/football/seriya-a/dzhenoa-udineze-kogda-i-gde-smotret-v-pryamom-efire/'
            ];

            //начинаем парсить каждую новость
            foreach ($new_news as $new) {
                Parser::parseNews($new);
            }
        }
    }
}
