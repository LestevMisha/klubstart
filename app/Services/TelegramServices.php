<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramServices {

    /* +++++++++++++++++++ HEADER +++++++++++++++++++ */
    public function __construct(
        protected \App\Services\Models\AvatarServices $avatarServices,
        protected \App\Services\Models\UserServices $userServices,
        protected \App\Services\RedisServices $redisServices,
    ) {}

    /* +++++++++++++++++++ PUBLIC SECTION +++++++++++++++++++ */
    public function __getUnderscoreRestrictedTelegramLink(string $subject, string $identifier): string {
        // telegram deep link formation
        $base = config("services.telegram.bot_url");
        $param = implode("_", [$subject, $identifier]);
        return "{$base}?start={$param}";
    }

    public function _getRegisterLink(): string {
        // get referred_by_uuid from the request - default to cookies
        $referred_by_uuid = request()->query('referred_by_uuid', request()->cookie("referred_by_uuid", ""));
        return auth()->user() ? route("private.dashboard") : $this->__getUnderscoreRestrictedTelegramLink("web", $referred_by_uuid);
    }

    public function observeCurrentUserImage($user_id, $index = 0): array {
        try {
            // get user images
            $photos = Telegram::getUserProfilePhotos([
                "user_id" => $user_id,
            ]);

            // get path to last best-quality image
            $fileId = data_get($photos, "photos.{$index}.0.file_id");
            if (!$fileId) {
                return [false, __("profile.invalid_index")];
            }

            $link = Telegram::getFile(["file_id" => $fileId]);

            // return binary code of image
            $url = "https://api.telegram.org/file/bot" . config("services.telegram.bot_token") . "/" . $link["file_path"];
            $client = new Client();
            $response = $client->get($url);
            return [true, $response->getBody()->getContents()];
        } catch (\Exception $e) {
            return [false, __("profile.unexpected_error")];
        }
    }

    public function updateImage($index): array {
        // Ensure $index is a valid integer
        if (!is_numeric($index)) return [false, __("profile.invalid_index")];

        // data
        $user = auth()->user();
        $uuid = $user->uuid;

        // Get the latest user's profile photo/image
        [$success, $response] = $this->observeCurrentUserImage($user->user_id, $index);
        if (!$success) return [$success, $response];

        // Save it to database
        $encoded = base64_encode($response);
        $this->avatarServices->updateOrCreateAvatar($uuid, $encoded)->image_data;

        // Save it to redis
        Redis::hset("users:{$uuid}", "avatar", $encoded);

        return [true, null];
    }

    public function _getImage(): string {
        // data
        $user = auth()->user();
        $uuid = $user->uuid;

        $encoded = Redis::hget("users:{$uuid}", "avatar");

        if (!$encoded) {
            if (!$this->avatarServices->hasAvatar($uuid)) {
                // Get the latest user's profile photo/image
                [$_, $response] = $this->observeCurrentUserImage($user->user_id);

                // Save it to database
                $encoded = $this->avatarServices->updateOrCreateAvatar($uuid, base64_encode($response))->image_data;
            } else {
                // Retrieve the image data
                $encoded = $this->avatarServices->getAvatar('user_uuid', $uuid)->image_data;
            }

            // Save it to redis
            Redis::hset("users:{$uuid}", "avatar", $encoded);
        }

        // Return base64 encoded string
        return $encoded;
    }

    public function autoLogin(Request $request): mixed {
        // valiade signature
        if (!$request->hasValidSignature()) return null;

        // validate uuid and user
        $uuid = $request->input("uuid");
        if (!$uuid) return null;

        $user = $this->userServices->getUser("uuid", $uuid);
        if (!$user) return null;

        // Create an new login session
        $guestId = session()->getId();
        Auth::login($user);

        $this->redisServices->transferGuestPreferencesToUser($user->uuid, $guestId);
        $request->session()->regenerate();

        return $user;
    }

}
