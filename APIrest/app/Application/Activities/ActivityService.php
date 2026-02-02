<?php

namespace App\Application\Activities;

use App\Models\User;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Events\ActivityRegistered;
use Illuminate\Support\Facades\Event;

class ActivityService
{
    public function listFor(User $user): Collection
    {
        return $user->isAdmin()
            ? Activity::query()->get()
            : Activity::query()
                ->whereHas('client', function($q) use ($user){
                    $q->where('user_id', $user->id);
                })->get();
    }

    public function createForClient(int $clientId, array $data): Activity
    {
        $client = Client::query()->findOrFail($clientId);

        $activity = Activity::query()->create([
            'description' => $data['description'],
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);

        $this->onActivityCreated($activity);

        return $activity;
    }

    public function update(Activity $activity, array $data): Activity
    {
        $activity->update($data);
        return $activity;
    }

    public function delete(Activity $activity): void
    {
        $activity->delete();
    }

    protected function onActivityCreated(Activity $activity): void
    {
        Event::dispatch(new ActivityRegistered($activity));
    }
}