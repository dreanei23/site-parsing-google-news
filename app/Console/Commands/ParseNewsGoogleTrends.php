<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DiDom\Document;
use Symfony\Component\Panther\Client;
use App\Models\News;
use App\Models\Site;
use App\Notifications\NewNews;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        $url = 'https://trends.google.ru/trends/trendingsearches/realtime?geo=RU&category=all';

        //создаем клиент хрома и отправляем запрос
        $client = Client::createChromeClient();        
        $client->request('GET', $url);

        //ждем, пока появится элемент, после чего парсим страницу
        $crawler = $client->waitForVisibility('.feed-load-more-button');
        $html = $crawler->html();

        //находим все ссылки на новости
        $document = new Document($html);
        $news = $document->find('.summary-text a');

        if (!empty($news)) {
            foreach ($news as $new) {
                $parsed_news[] = $new->href;
            }
            
            //проверяем каких новостей из спарсенных у нас нет
            $parsed_news = collect($parsed_news); //сделаем коллекцию из массива
            $new_news = $parsed_news->diff(News::all()->pluck('source_url')); 
     
            
            $new_news = [
                'http://sport.bigmir.net/football/england/1977524-Mikolenko-ne-popal-v-zajavku-Evertona-na-match-s-Aston-Villoj',
                //'https://sportarena.com/football/seriya-a/dzhenoa-udineze-kogda-i-gde-smotret-v-pryamom-efire/'
            ];

            //начинаем парсить каждую новость
            foreach ($new_news as $new) {
                $domain = parse_url($new)['host'];
                $site = Site::where('domain', $domain)->first();

                $news_data = News::firstOrCreate([
                    'source_url' => $new,
                ]);

                //есть в нашей базе, парсим новость
                if ($site) {
                    $http = Http::withOptions([
                        'verify' => false,
                    ]);
                    $response = $http->get($new);
                    $charset = Str::after($response->headers()['Content-Type'][0], 'charset=');

                    $parsed_news_html = new Document($response->body(), false, $charset);
                    $news_data->title = $parsed_news_html->first($site->selector_title)->text();
                    
                    //подготовка документа под парсинг контента и фото
                    $news_content_html = $parsed_news_html->first($site->selector_content)->toDocument();

                    //контент
                    $news_content = $news_content_html->format()->html();

                    //фото
                    $find_img = $news_content_html->find('img');
                    if ($find_img) {
                        $img_for_replace = []; //массив, куда запишем ссылки на фото для замены
                        foreach($news_content_html->find('img') as $image) {
                            $image_link = $image->attr('src');
                            $img = $http->get($image_link);
                            $img_extension = last(explode('.', $image_link));
                            $img_name = time().'.'.$img_extension;
                            $img_path = date("Y").'/'.date("m").'/'.$img_name;
                            Storage::disk('public')->put($img_path, $img);
                            $img_for_replace[$image_link] = '/storage/'.$img_path; 
                        }
                
                        //заменим ссылки на изображения
                        $news_content = strtr($news_content, $img_for_replace);
                    }

                    $news_data->content = $news_content;
                    $news_data->save();

                } else { //добавляем сайт в базу
                    //TODO отсылать в ТГ ссылки на формы редактирования
                    Site::create([
                        'domain' => $domain,
                    ]);
                    Notification::send('-1001390760487', new NewNews("Новый сайт: $domain\nНовость: $new"));
                }
            }
        }

        return 0;
    }
}
