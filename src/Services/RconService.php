<?php

namespace Atom\Rcon\Services;

use Illuminate\Support\Facades\Log;
use Socket;

class RconService
{
    /**
     * The Socket service instance.
     */
    protected ?Socket $service = null;

    /**
     * The connection status.
     */
    public bool $connected = false;

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        $this->service = $this->connect();
    }

    /**
     * Connect to the RCON server.
     */
    public function connect(): ?Socket
    {
        if (! config('rcon.enabled')) {
            return null;
        }

        if (! function_exists('socket_create')) {
            Log::error('The socket extension is not installed.');

            return null;
        }

        $socket = socket_create(config('rcon.domain'), config('rcon.type'), config('rcon.protocol'));

        if (! $socket) {
            Log::error(sprintf('socket_create() failed: reason: %s', socket_strerror(socket_last_error())));
        }

        $this->connected = @socket_connect($socket, config('rcon.host'), config('rcon.port'));

        if (! $this->connected) {
            Log::error(sprintf('socket_connect() failed: reason: %s', socket_strerror(socket_last_error($socket))));

            return null;
        }

        return $socket;
    }

    /**
     * Send a packet to the tcp server.
     */
    public function sendPacket(string $key, mixed $data = null): mixed
    {
        if ($this->connected && is_resource($this->service)) {
            socket_close($this->service);
            $this->connected = false;
        }

        $this->service = $this->connect();

        if (! $this->connected) {
            return null;
        }

        $request = socket_write(
            $this->service,
            json_encode(['key' => $key, 'data' => $data]),
            strlen(json_encode(['key' => $key, 'data' => $data])),
        );

        if (! $request) {
            Log::error(sprintf('socket_write() failed: reason: %s', socket_strerror(socket_last_error($this->service))));

            return null;
        }

        return json_decode(socket_read($this->service, 2048));
    }

    /**
     * Update user data.
     */
    public function updateUser(int $userId, string $column, mixed $value): mixed
    {
        return $this->sendPacket('updateuser', [
            'user_id' => $userId,
            $column => $value,
        ]);
    }

    /**
     * Update users username.
     */
    public function changeUsername(int $userId, string $username): mixed
    {
        return $this->sendPacket('updateuser', [
            'user_id' => $userId,
            'username' => $username,
        ]);
    }

    /**
     * Give user points by type.
     */
    public function givePoints(int $userId, int $type, int $amount): mixed
    {
        return $this->sendPacket('givepoints', [
            'user_id' => $userId,
            'points' => $amount,
            'type' => $type,
        ]);
    }

    /**
     * Give credits to a user.
     */
    public function giveCredits(int $userId, int $credits): mixed
    {
        return $this->sendPacket('givecredits', [
            'user_id' => $userId,
            'credits' => $credits,
        ]);
    }

    /**
     * Give user diamonds.
     */
    public function giveDiamonds(int $userId, int $amount): mixed
    {
        return $this->sendPacket('givepoints', [
            'user_id' => $userId,
            'points' => $amount,
            'type' => 5,
        ]);
    }

    /**
     * Give user duckets.
     */
    public function giveDuckets(int $userId, int $amount): mixed
    {
        return $this->sendPacket('givepoints', [
            'user_id' => $userId,
            'points' => $amount,
            'type' => 0,
        ]);
    }

    /**
     * Send a gift to a specific user.
     */
    public function sendGift(int $userId, int $itemId, string $message = ''): mixed
    {
        return $this->sendPacket('gift', [
            'user_id' => $userId,
            'itemid' => $itemId,
            'message' => $message,
        ]);
    }

    /**
     * Give badge to a user.
     */
    public function giveBadge(int $userId, string $badge): mixed
    {
        return $this->sendPacket('givebadge', [
            'user_id' => $userId,
            'badge' => $badge,
        ]);
    }

    /**
     * Update users motto.
     */
    public function setMotto(int $userId, string $motto): mixed
    {
        return $this->sendPacket('setmotto', [
            'user_id' => $userId,
            'motto' => $motto,
        ]);
    }

    /**
     * Update the word filter.
     */
    public function updateWordFilter(): mixed
    {
        return $this->sendPacket('updatewordfilter');
    }

    /**
     * Set users rank.
     */
    public function setRank(int $userId, int $rank): mixed
    {
        return $this->sendPacket('setrank', [
            'user_id' => $userId,
            'rank' => $rank,
        ]);
    }

    /**
     * Update the catalog.
     */
    public function updateCatalog(): mixed
    {
        return $this->sendPacket('updatecatalog');
    }

    /**
     * Send user an alert.
     */
    public function alertUser(int $userId, string $message): mixed
    {
        return $this->sendPacket('alertuser', [
            'user_id' => $userId,
            'message' => $message,
        ]);
    }

    /**
     * Send user to a room.
     */
    public function forwardUser(int $userId, int $roomId): mixed
    {
        return $this->sendPacket('forwarduser', [
            'user_id' => $userId,
            'room_id' => $roomId,
        ]);
    }
}
