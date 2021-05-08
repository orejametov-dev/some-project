<?php

namespace Database\Seeders;

use App\Modules\Core\Models\Comment;
use Illuminate\Database\Seeder;

class MerchantCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            [
                'body' => 'New merchant',
                'commentable_type' => 'merchant_requests',
                'commentable_id' => 1,
                'created_from_id' => 1,
                'updated_from_id' => 1,
                'created_by_id' => 1,
                'updated_by_id' => 1
            ],
            [
                'body' => 'New merchant',
                'commentable_type' => 'merchant_requests',
                'commentable_id' => 2,
                'created_from_id' => 2,
                'updated_from_id' => 2,
                'created_by_id' => 2,
                'updated_by_id' => 1
            ],
            [
                'body' => 'New merchant',
                'commentable_type' => 'merchant_requests',
                'commentable_id' => 3,
                'created_from_id' => 3,
                'updated_from_id' => 3,
                'created_by_id' => 3,
                'updated_by_id' => 1
            ],
        ];

        Comment::query()->insert($comments);
    }
}
