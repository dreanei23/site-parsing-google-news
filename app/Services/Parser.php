<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use DiDom\Document;
use Illuminate\Support\Facades\Storage;
use App\Models\News;
use App\Models\Site;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewNews;
use Symfony\Component\Panther\Client;


class Parser {

    public function parseGoogleTrendNews()
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
        return $document->find('.summary-text a');
    }

    public function parseNews($url)
    {
        $domain = parse_url($url)['host'];
        $site = Site::where('domain', $domain)->first();

        $news_data = News::firstOrCreate([
            'source_url' => $url,
        ]);

        //есть в нашей базе, парсим новость
        if ($site) {
            $http = Http::withOptions([
                'verify' => false,
            ]);
            $response = $http->get($url);
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
            Notification::send('-1001390760487', new NewNews("Новый сайт: $domain\nНовость: $url"));
        }

    }

}