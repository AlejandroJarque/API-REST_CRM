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

        $payload = [
            'title' => $data['title'],
            'status' => $data['status'],
            'date' => $data['date'],
            'description' => $data['description'] ?? null,

            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ];

        if ($payload['status'] === Activity::STATUS_DONE) {
            $payload['completed_at'] = now();
        } else {
            $payload['completed_at'] = null;
        }

        $activity = Activity::query()->create($payload);

        $this->onActivityCreated($activity);

        return $activity;
    }

    public function update(Activity $activity, array $data): Activity
    {
        if (array_key_exists('status', $data)) {
            if ($data['status'] === Activity::STATUS_DONE) {
                $data['completed_at'] = now();
            } else {
                $data['completed_at'] = null;
            }
        }

        $activity->update($data);

        return $activity->refresh();
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