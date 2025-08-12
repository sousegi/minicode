<?php

namespace App\GraphQL\Types;

use App\Models\Blog;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class BlogType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Blog',
        'description' => 'Blog entry',
        'model' => Blog::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID',
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'Titlu',
            ],
            'content' => [
                'type' => Type::string(),
                'description' => 'Text articol',
            ],
            'published' => [
                'type' => Type::boolean(),
                'description' => 'Publicat',
            ],
            'published_at' => [
                'type' => Type::string(),
                'description' => 'Data adăugării',
            ],
        ];
    }
}
