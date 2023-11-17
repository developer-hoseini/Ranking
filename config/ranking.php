<?php

return [
    'rules' => [
        'coin' => [
            'tournoment' => [
                'tour_first_per' => 50, // 50% profit of tournament for first person
                'tour_second_per' => 35, // 35% profit of tournament for second person
                'tour_third_per' => 15, // 15% profit of tournament for third person
            ],
            'user' => [
                'register' => 10,
                'complete_profile' => 40,
                'winer_tow_player' => 10,
                'loser_tow_player' => 2,
                'winer_tow_player_with_picture' => 15,
                'loser_tow_player_with_picture' => 7,
            ],
        ],

    ],
    'settings' => [
        'global' => [
            'per_page' => 15,
        ],
    ],
];
