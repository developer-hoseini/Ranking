<?php

return [

    'home_ranks_table' => 3, // home ranks table players count
    'home_ranks' => 3, // home players rank list count
    'top_club' => 3, // home top club players list count
    'latest_played' => 5, // home latest played box count
    'latest_photos' => 16, // home latest photos count

    'gameinfo_list' => 20, // count of players list

    'chats_list' => 10, // count of chat in chats page

    'days_ago' => 100, // ex: played count in 100 days ago
    'star_club' => 70, // ex: 70 played in 100 days ago = 100% stars
    'star_image' => 70, // ex: 70 played in 100 days ago = 100% stars
    'star_team_played' => 70, // ex: 70 played in 100 days ago = 100% stars
    'warning_div' => 10, // ex: total_played / 10  (1/10)

    'no_repeat_days' => 1, // ex: 7 days must take at least to send invite for same player
    'no_repeat_played' => 10, // ex: 10 plays must take at least to send invite for same player

    'max_received' => 5, // Max number of received invites
    'max_sent' => 5, // Max number of sent invites
    'max_results' => 10, // Max number of games results list

    'before_after_rank' => 10, // Number of users to show before & after of User in ranks page
    'all_rank' => 30, // Number of users to show in "ranks & global_ranks", when auth user not joined

    'default_score' => 100, // ex: add this score after join to game
    'add_score' => 10, // ex: After win , this value will added to user score
    'add_score_sooner' => 3, // ex: When submit sooner (first submit), this value will added to user score
    'add_score_present' => 5, // add this score for player who did not absent/left play
    'add_score_submitter' => 3, // add this score for who submit result (when opponent not submit after 24h)

    'win_per' => 1, // persentage of opponent score, when user won

    'events_per_page' => 10, // number of events to show in one page
    'notifies_per_page' => 10, // number of notifications to show in one page

    'random_users' => 4, // number of random users to show in gamepage

    'profile_middleware_count' => 10, // ex: after 10 plays, must complete his profile

    'default_coin' => 50, // ex: add this coin after register
    'winner_coin' => 40, // coin for winner in "in_club" play
    'reseller_winner_coin' => 20, // coin for winner in reseller club of rezvani
    'free_winner_coin' => 10, // coin for winner in "free_game" play (ex: home, online_game)
    'loser_coin' => 10, // coin remove from loser in "in_club" play
    'member_coin' => 10, // coin for each member of winner team in "in_club" play

    'coin_value' => 500, // value of each coin

    'min_tournament' => 4, // admin can not create tor less than this value (dont set less than 3)

    'bracket_C' => 30, // bracket grade (C) <30 members
    'bracket_B' => 200, // bracket grade (B) <200 members  &  (A) >200 members

    'tour_organizer_per' => 30, // 30% profit of tournament for organizer
    'tour_first_per' => 30, // 30% profit of tournament for first person
    'tour_second_per' => 20, // 20% profit of tournament for second person
    'tour_third_per' => 10, // 10% profit of tournament for third person
    //'tour_ranking_per' => 10, // 10% profit of tournament for ranking [not need,  total coins - sum of all above = ranking_cash profit ]

    'auto_submit_after' => 1, // after this number of days, system end the play
    'auto_cancel_after' => 1, // after this number of days, system cancel the invite
    'auto_cancel_quick_after' => 1, // after this number of days, system cancel the invite

    'create_team_score' => 100, // ex: add this score to created team
    'max_team_member' => 15, // max member count of team that capitan can add

];
