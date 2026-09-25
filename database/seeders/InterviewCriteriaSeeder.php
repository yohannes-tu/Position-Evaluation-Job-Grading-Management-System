<?php

namespace Database\Seeders;

use App\Models\InterviewCriterion;
use Illuminate\Database\Seeder;

class InterviewCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            [
                'name' => 'Technical Knowledge',
                'description' =>
                    'Knowledge and understanding relevant to the position.',
                'weight' => 30,
                'max_score' => 100,
                'sort_order' => 1,
            ],

            [
                'name' => 'Communication',
                'description' =>
                    'Ability to communicate clearly and professionally.',
                'weight' => 20,
                'max_score' => 100,
                'sort_order' => 2,
            ],

            [
                'name' => 'Problem Solving',
                'description' =>
                    'Ability to analyze problems and develop appropriate solutions.',
                'weight' => 20,
                'max_score' => 100,
                'sort_order' => 3,
            ],

            [
                'name' => 'Relevant Experience',
                'description' =>
                    'Practical experience relevant to the position.',
                'weight' => 20,
                'max_score' => 100,
                'sort_order' => 4,
            ],

            [
                'name' => 'Professionalism',
                'description' =>
                    'Professional behavior, attitude and suitability.',
                'weight' => 10,
                'max_score' => 100,
                'sort_order' => 5,
            ],
        ];

        foreach ($criteria as $criterion) {
            InterviewCriterion::updateOrCreate(
                [
                    'name' => $criterion['name'],
                ],
                $criterion + [
                    'is_active' => true,
                ]
            );
        }
    }
}