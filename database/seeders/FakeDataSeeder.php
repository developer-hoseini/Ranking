<?php

namespace Database\Seeders;

use App\Enums\AchievementTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Achievement;
use App\Models\Competition;
use App\Models\Cup;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\GameType;
use App\Models\Role;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {

            $this->createGames();

            $teams = Team::factory()->count(20)->create();

            $users = $this->createUsers();

            $compeations = $this->createCompetitions($users, $teams);

            $this->createCups($compeations);

            $this->createAchievements();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function createGames()
    {

        $gameTypes = GameType::inRandomOrder()->get();

        $games = Game::all();

        $dataCollect = collect([]);
        foreach ($games as $game) {
            $dataCollect->push([
                'game_id' => $game->id,
                'game_type_id' => $gameTypes->shuffle()->first()->id,
            ]);

            $dataCollect->push([
                'game_id' => $game->id,
                'game_type_id' => $gameTypes->shuffle()->first()->id,
            ]);
        }

        DB::table('game_game_type')->upsert($dataCollect->toArray(), [
            'game_id',
            'game_type_id',
        ]);

    }

    private function createUsers(): Collection
    {

        if (User::count() > 100) {
            return User::all();
        }

        $users = User::factory()
            ->count(300)
            ->create();

        $roleClient = Role::where('name', 'client')->first();
        $dataCollect = collect([]);
        foreach ($users as $user) {
            $dataCollect->push(['role_id' => $roleClient->id, 'model_type' => User::class, 'model_id' => $user->id]);
        }

        DB::table('model_has_roles')->upsert($dataCollect->toArray(), ['role_id', 'model_type', 'model_id']);

        return $users;
    }

    private function createCompetitions($users, $teams): Collection
    {
        if (Competition::count() > 60) {
            return Competition::all();
        }

        $competitions = Competition::factory()
            ->count(70)
            ->create();

        $dataCollect = collect([]);
        $gameResultsCollect = collect([]);
        $scoreAndCoincollect = collect([]);
        $gameResultsStatuses = Status::modelType(GameResult::class)->get();
        $acceptedStatuse = Status::modelType(null)->where('name', StatusEnum::ACCEPTED->value)->first();

        $competitions->loadMissing(['users', 'teams']);
        foreach ($competitions as $competition) {
            $forModel = User::class;

            if ($forModel == User::class) {
                $isWin = true;
                foreach ($users->shuffle()->take(2) as $user) {
                    $gameResultStatusId = $isWin ? $gameResultsStatuses->where('name', StatusEnum::GAME_RESULT_WIN->value)->first()->id : $gameResultsStatuses->where('name', StatusEnum::GAME_RESULT_LOSE->value)->first()->id;

                    $gameResultsCollect->push([
                        'playerable_type' => User::class,
                        'playerable_id' => $user->id,
                        'gameresultable_type' => Competition::class,
                        'gameresultable_id' => $competition->id,
                        'game_result_status_id' => $gameResultStatusId,
                        'status_id' => $acceptedStatuse->id,
                    ]);

                    $dataCollect->push(
                        [
                            'competition_id' => $competition->id,
                            'competitionable_type' => User::class,
                            'competitionable_id' => $user->id,
                        ]
                    );
                    $isWin = false;
                }
                $forModel = Team::class;
            } else {
                $isWin = true;

                foreach ($teams->shuffle()->take(2) as $team) {
                    $gameResultStatusId = $isWin ? $gameResultsStatuses->where('name', StatusEnum::GAME_RESULT_WIN->value)->first()->id : $gameResultsStatuses->where('name', StatusEnum::GAME_RESULT_LOSE->value)->first()->id;
                    $gameResultsCollect->push([
                        'playerable_type' => Team::class,
                        'playerable_id' => $team->id,
                        'gameresultable_type' => Competition::class,
                        'gameresultable_id' => $competition->id,
                        'game_result_status_id' => $gameResultStatusId,
                        'status_id' => $acceptedStatuse->id,
                    ]);

                    $dataCollect->push(
                        [
                            'competition_id' => $competition->id,
                            'competitionable_type' => Team::class,
                            'competitionable_id' => $team->id,
                        ]
                    );
                    $isWin = false;
                }
                $forModel = User::class;
            }

            for ($i = 0; $i < 2; $i++) {
                $scoreAndCoincollect->push([
                    'achievementable_type' => Competition::class,
                    'achievementable_id' => $competition->id,
                    'type' => $i == 0 ? AchievementTypeEnum::COIN->value : AchievementTypeEnum::SCORE->value,
                    'count' => $i == 0 ? 25 : 50,
                    'created_by_user_id' => auth()->id(),
                ]);
            }

        }

        DB::table('competitionables')->upsert($dataCollect->toArray(), ['competition_id', 'competitionable_type', 'competitionable_id']);
        DB::table('achievements')->upsert($scoreAndCoincollect->toArray(), ['achievementable_id', 'achievementable_type', 'type']);
        DB::table('game_results')->upsert($gameResultsCollect->toArray(), ['playerable_type', 'playerable_id', 'gameresultable_type', 'gameresultable_id']);

        return $competitions;
    }

    private function createCups($competitions): Collection
    {
        if (Cup::count() >= 40) {
            return Cup::all();
        }

        $dataCollect = collect([]);
        $cups = Cup::factory()->count(40)->create();

        foreach ($cups as $cup) {
            for ($i = 0; $i < $cup->capacity; $i++) {
                $dataCollect->push([
                    'cup_id' => $cup->id,
                    'cupable_type' => Competition::class,
                    'cupable_id' => $competitions->shuffle()->first()->id,
                ]);
            }
        }

        DB::table('cupables')->upsert($dataCollect->toArray(), ['cup_id', 'cupable_type', 'cupable_id']);

        return $cups;
    }

    private function createAchievements()
    {
        $competitions = Competition::with(['users', 'gameResults.status', 'achievements', 'scoreAchievement', 'coinAchievement'])->get();
        $dataCollect = collect([]);

        $acheivementsStatuses = Status::modelType(Achievement::class, false)->get();

        foreach ($competitions as $competition) {

            foreach ($competition->users as $user) {

                for ($i = 0; $i < 2; $i++) {
                    $isCoin = $i == 0;

                    $gameResult = $competition->gameResults
                        ->where('playerable_type', User::class)
                        ->where('playerable_id', $user->id)
                        ->first();

                    $achievementStatusName = match ($gameResult?->gameResultStatus?->name) {
                        StatusEnum::GAME_RESULT_WIN->value => StatusEnum::ACHIEVEMENT_WIN->value ,
                        StatusEnum::GAME_RESULT_LOSE->value => StatusEnum::ACHIEVEMENT_LOSE->value ,
                        default => null
                    };

                    $achievementStatusId = $acheivementsStatuses->where('name', $achievementStatusName)->first()?->id;

                    if ($achievementStatusId) { // only win and lose submit in achievement
                        $dataCollect->push([
                            'achievementable_type' => User::class,
                            'achievementable_id' => $user->id,
                            'type' => $isCoin ? AchievementTypeEnum::COIN->value : AchievementTypeEnum::SCORE->value,
                            'count' => $isCoin ? $competition->coinAchievement->count : $competition->scoreAchievement->count,
                            'occurred_model_id' => $competition->id,
                            'occurred_model_type' => Competition::class,
                            'status_id' => $achievementStatusId,
                            'created_by_user_id' => auth()->id(),
                        ]);
                    }

                }
            }
        }

        DB::table('achievements')->insert($dataCollect->toArray());

    }
}
