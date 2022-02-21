<?php

namespace App\Http\Controllers;


use App\Models\News;
use App\Models\Category;


class NewsController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, News $news)
    {
        return view('web.page.news', [
            'news' => $news
        ]);
    }

}
