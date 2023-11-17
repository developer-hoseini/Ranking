<?php

namespace App\Services\Actions\Achievement\GameResult;

use App\Enums\AchievementTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Achievement;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ReceiveCoin
{
    private $gameResults;

    private $gameResult;

    private $isCreatedAchievmentForAgent = false;

    public static function handle(Collection $gameResults)
    {
        new self($gameResults);
    }

    public function __construct(Collection $gameResults)
    {
        $this->gameResults = $gameResults;
        $this->makeAchievements();
    }

    private function makeAchievements()
    {
        foreach ($this->gameResults as $gameResult) {
            $this->gameResult = $gameResult;

            if ($this->isAcceptedByAdmin()) {
                $this->createAchievement();
            } else {
                $this->removeAchievement();
            }

        }
    }

    private function isAcceptedByAdmin(): bool
    {
        return $this->gameResult->gameResultAdminStatus->name == StatusEnum::ACCEPTED->value;
    }

    private function isWiner(): bool
    {
        return $this->gameResult->gameResultStatus->name == StatusEnum::GAME_RESULT_WIN->value;
    }

    private function isLoser(): bool
    {
        return $this->gameResult->gameResultStatus->name == StatusEnum::GAME_RESULT_LOSE->value;
    }

    private function confirmerIsAgent(string $type): bool
    {
        $authUser = \Auth::user();

        $isAgent = false;

        if ($authUser->isAgent) {
            $isAgent = match ($type) {
                'a' => $authUser->isAgentA,
                'b' => $authUser->isAgentB,
                'c' => $authUser->isAgentC,
                default => false
            };
        }

        return $isAgent;
    }

    private function hasPicture(): bool
    {
        return $this->gameResult->gameresultable?->media?->count() > 0;
    }

    private function loserCoinCountWithCompetitionPicture(): int
    {
        $coin = 0;

        $confirmerIsAgentA = $this->confirmerIsAgent('a');
        $confirmerIsAgentB = $this->confirmerIsAgent('b');
        $confirmerIsAgentC = $this->confirmerIsAgent('c');

        if ($confirmerIsAgentC) {
            $coin = config('ranking.rules.coin.user.loser_tow_player_with_competition_picture_confirmer_agent_c');
        }
        if ($confirmerIsAgentB) {
            $coin = config('ranking.rules.coin.user.loser_tow_player_with_competition_picture_confirmer_agent_b');
        }
        if ($confirmerIsAgentA) {
            $coin = config('ranking.rules.coin.user.loser_tow_player_with_competition_picture_confirmer_agent_a');
        }

        if (! $confirmerIsAgentC && ! $confirmerIsAgentB && ! $confirmerIsAgentA) {
            $coin = config('ranking.rules.coin.user.loser_tow_player_with_competition_picture');
        }

        return $coin;
    }

    private function loserCoinCountWithoutCompetitionPicture(): int
    {
        $coin = 0;

        $confirmerIsAgentA = $this->confirmerIsAgent('a');
        $confirmerIsAgentB = $this->confirmerIsAgent('b');
        $confirmerIsAgentC = $this->confirmerIsAgent('c');

        if ($confirmerIsAgentA) {
            return config('ranking.rules.coin.user.loser_tow_player_confirmer_agent_a');
        }

        if ($confirmerIsAgentB) {
            return config('ranking.rules.coin.user.loser_tow_player_confirmer_agent_b');
        }

        if ($confirmerIsAgentC) {
            return config('ranking.rules.coin.user.loser_tow_player_confirmer_agent_c');
        }

        if (! $confirmerIsAgentC && ! $confirmerIsAgentB && ! $confirmerIsAgentA) {

            $coin = config('ranking.rules.coin.user.loser_tow_player');
        }

        return $coin;

    }

    private function winerCoinCountWithCompetitionPicture(): int
    {
        $coin = 0;

        $confirmerIsAgentA = $this->confirmerIsAgent('a');
        $confirmerIsAgentB = $this->confirmerIsAgent('b');
        $confirmerIsAgentC = $this->confirmerIsAgent('c');

        if ($confirmerIsAgentC) {
            $coin = config('ranking.rules.coin.user.winer_tow_player_with_competition_picture_confirmer_agent_c');
        }
        if ($confirmerIsAgentB) {
            $coin = config('ranking.rules.coin.user.winer_tow_player_with_competition_picture_confirmer_agent_b');
        }
        if ($confirmerIsAgentA) {
            $coin = config('ranking.rules.coin.user.winer_tow_player_with_competition_picture_confirmer_agent_a');
        }

        if (! $confirmerIsAgentC && ! $confirmerIsAgentB && ! $confirmerIsAgentA) {
            $coin = config('ranking.rules.coin.user.winer_tow_player_with_competition_picture');
        }

        return $coin;
    }

    private function winerCoinCountWithoutCompetitionPicture(): int
    {
        $coin = 0;

        $confirmerIsAgentA = $this->confirmerIsAgent('a');
        $confirmerIsAgentB = $this->confirmerIsAgent('b');
        $confirmerIsAgentC = $this->confirmerIsAgent('c');

        if ($confirmerIsAgentC) {
            $coin = config('ranking.rules.coin.user.winer_tow_player_confirmer_agent_c');
        }
        if ($confirmerIsAgentB) {
            $coin = config('ranking.rules.coin.user.winer_tow_player_confirmer_agent_b');
        }
        if ($confirmerIsAgentA) {
            $coin = config('ranking.rules.coin.user.winer_tow_player_confirmer_agent_a');
        }

        if (! $confirmerIsAgentC && ! $confirmerIsAgentB && ! $confirmerIsAgentA) {
            $coin = config('ranking.rules.coin.user.winer_tow_player');
        }

        return $coin;

    }

    private function loserCoinCount(): int
    {
        $coin = 0;
        $hasPicture = $this->hasPicture();

        if ($hasPicture) {
            $coin = $this->loserCoinCountWithCompetitionPicture();
        }

        if (! $hasPicture) {
            $coin = $this->loserCoinCountWithoutCompetitionPicture();
        }

        return $coin;
    }

    private function winerCoinCount(): int
    {

        $coin = 0;
        $hasPicture = $this->hasPicture();

        if ($hasPicture) {
            $coin = $this->winerCoinCountWithCompetitionPicture();
        }

        if (! $hasPicture) {
            $coin = $this->winerCoinCountWithoutCompetitionPicture();
        }

        return $coin;

    }

    private function getCountCoin(): int
    {
        if ($this->isWiner()) {
            return $this->winerCoinCount();
        }

        if ($this->isLoser()) {
            return $this->loserCoinCount();
        }

        return 0;
    }

    private function getCountCoinAgent(): int
    {
        $coin = 0;

        if ($this->confirmerIsAgent('a')) {
            return config('ranking.rules.coin.user.confirm_by_agent_a');
        }

        if ($this->confirmerIsAgent('b')) {
            return config('ranking.rules.coin.user.confirm_by_agent_b');
        }

        if ($this->confirmerIsAgent('c')) {
            return config('ranking.rules.coin.user.confirm_by_agent_c');
        }

        return $coin;
    }

    private function getStatusId(): ?int
    {
        $statusId = null;

        if ($this->isWiner()) {
            $statusId = Status::nameScope(StatusEnum::ACHIEVEMENT_WIN->value)->firstOrFail()?->id;
        }

        if ($this->isLoser()) {
            $statusId = Status::nameScope(StatusEnum::ACHIEVEMENT_LOSE->value)->firstOrFail()?->id;
        }

        return $statusId;
    }

    private function createAchievement()
    {

        $count = $this->getCountCoin();
        $statusId = $this->getStatusId();

        $columnCheckAcheivementExist = [
            'achievementable_type' => User::class,
            'achievementable_id' => $this->gameResult->playerable_id,
            'type' => AchievementTypeEnum::COIN->value,
            'occurred_model_type' => $this->gameResult->gameresultable_type,
            'occurred_model_id' => $this->gameResult->gameresultable_id,
            'status_id' => $statusId,
        ];

        $columnForUpdate = [
            'count' => $count,
            'status_id' => $statusId,
            'deleted_at' => null,
        ];

        $achievement = Achievement::where($columnCheckAcheivementExist)->withTrashed()->first();

        if ($achievement) {
            $achievement->update($columnForUpdate);
        } else {

            $authUser = \Auth::user();

            Achievement::create([
                ...$columnCheckAcheivementExist,
                ...$columnForUpdate,
                'created_by_user_id' => $authUser->id,
            ]);

            if ($authUser->isAgent && ! $this->isCreatedAchievmentForAgent) {
                $this->createAchievementForAgent();
                $this->isCreatedAchievmentForAgent = true;
            }

        }
    }

    private function removeAchievement()
    {
        $statusId = $this->getStatusId();

        Achievement::query()
            ->where('achievementable_type', User::class)
            ->where('achievementable_id', $this->gameResult->playerable_id)
            ->where('type', AchievementTypeEnum::COIN->value)
            ->where('occurred_model_type', $this->gameResult->gameresultable_type)
            ->where('occurred_model_id', $this->gameResult->gameresultable_id)
            ->where('status_id', $statusId)
            ->delete();
    }

    private function createAchievementForAgent()
    {
        $authId = \Auth::id();

        $coinAgent = $this->getCountCoinAgent();

        Achievement::create([
            'achievementable_type' => User::class,
            'achievementable_id' => $authId,
            'type' => AchievementTypeEnum::COIN->value,
            'occurred_model_type' => $this->gameResult->gameresultable_type,
            'occurred_model_id' => $this->gameResult->gameresultable_id,
            'status_id' => Status::nameScope(StatusEnum::ACHIEVEMENT_CONFIRM_COMPETITION->value)->firstOrFail()?->id,
            'count' => $coinAgent,
            'created_by_user_id' => $authId,
        ]);
    }
}
