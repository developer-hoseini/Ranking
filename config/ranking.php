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
                'winer_tow_player_with_competition_picture' => 15,
                'loser_tow_player_with_competition_picture' => 7,
                'winer_tow_player_confirmer_agent_c' => 30,
                'loser_tow_player_confirmer_agent_c' => 3,
                'winer_tow_player_confirmer_agent_b' => 40,
                'loser_tow_player_confirmer_agent_b' => 4,
                'winer_tow_player_confirmer_agent_a' => 60,
                'loser_tow_player_confirmer_agent_a' => 5,
                'winer_tow_player_with_competition_picture_confirmer_agent_c' => 35,
                'loser_tow_player_with_competition_picture_confirmer_agent_c' => 5,
                'winer_tow_player_with_competition_picture_confirmer_agent_b' => 45,
                'loser_tow_player_with_competition_picture_confirmer_agent_b' => 6,
                'winer_tow_player_with_competition_picture_confirmer_agent_a' => 65,
                'loser_tow_player_with_competition_picture_confirmer_agent_a' => 7,
                'confirm_by_agent_c' => 5,
                'confirm_by_agent_b' => 10,
                'confirm_by_agent_a' => 20,
                'confirm_by_agent_c_with_competition_picture' => 7,
                'confirm_by_agent_b_with_competition_picture' => 12,
                'confirm_by_agent_a_with_competition_picture' => 22,
            ],
        ],
        'score' => [
            'register' => 100,
        ],

    ],
    'settings' => [
        'global' => [
            'per_page' => 15,
        ],
    ],
];
