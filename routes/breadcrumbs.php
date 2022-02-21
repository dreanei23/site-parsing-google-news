<?php 

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

use App\Models\Category;
use App\Models\News;

Breadcrumbs::for('index', function (BreadcrumbTrail $trail) {
    $trail->push('Главная', route('index'));
});

Breadcrumbs::for('category.show', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('index');
    $trail->push($category->name, route('category.show', $category));
});

Breadcrumbs::for('news.show', function (BreadcrumbTrail $trail, Category $category, News $news) {
    $trail->parent('category.show', $category);
    $trail->push($news->title, route('news.show', ['category'=>$category, 'news'=>$news]));
});