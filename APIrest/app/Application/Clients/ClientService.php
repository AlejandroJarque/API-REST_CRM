<?php 

namespace App\Application\Clients;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;
use App\Domain\Events\ClientCreated;

class ClientService
{
    public function listFor(User $user): Collection
    {
        return $user->isAdmin()
            ? Client::query()->get()
            : Client::query()->where('user_id', $user->id)->get();
    }

    public function createFor(User $user, array $data): Client
    {
        $client = Client::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'user_id' => $user->id,
        ]);

        $this->onClientCreated($client);

        return $client;
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client;
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }

    protected function onClientCreated(Client $client): void
    {
        Event::dispatch(new ClientCreated($client));
    }
}