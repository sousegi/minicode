<?php

namespace App\GraphQL\Queries;

use App\Models\Blog;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class BlogsQuery extends Query
{
    protected $attributes = [
        'name' => 'blogs',
        'description' => 'Lista de blogs',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Blog'));
    }

    public function args(): array
    {
        return [
            'title' => [
                'type' => Type::string(),
                'description' => 'Titlu',
            ],
            'published' => [
                'type' => Type::boolean(),
                'description' => 'Filtrare dupa publicare',
            ],
            'published_at' => [
                'type' => Type::string(),
                'description' => 'Data publicarii',
            ],
            'search' => [
                'type' => Type::string(),
                'description' => 'Cautarea in bloguri',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Blog::query();

        if (!empty($args['title'])) {
            $query->where('title', $args['title']);
        }

        if (isset($args['published'])) {
            $query->where('published', $args['published']);
        }
        if (!empty($args['published_at'])) {
            $query->whereDate('published_at', $args['published_at']);
        }

        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }
}
