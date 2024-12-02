<?php

namespace BNETDocs\Models\User;

use \BNETDocs\Libraries\User\User;
use \BNETDocs\Libraries\User\Profile as UserProfile;

class Update extends \BNETDocs\Models\ActiveUser implements \JsonSerializable
{
    public ?User $user = null;

    public ?string $display_name = null;
    public ?array $display_name_error = null;
    public int $display_name_max_len = User::MAX_DISPLAY_NAME;

    public ?string $email_1 = null;
    public ?string $email_2 = null;
    public ?array $email_error = null;
    public int $email_max_len = User::MAX_EMAIL;

    public ?string $username = null;
    public ?array $username_error = null;
    public int $username_max_len = User::MAX_USERNAME;

    public ?UserProfile $profile = null;

    public ?string $biography = null;
    public ?array $biography_error = null;
    public int $biography_max_len = UserProfile::MAX_LEN;

    public ?string $discord_username = null;
    public ?array $discord_username_error = null;
    public int $discord_username_max_len = UserProfile::MAX_LEN;

    public ?string $facebook_username = null;
    public ?array $facebook_username_error = null;
    public int $facebook_username_max_len = UserProfile::MAX_LEN;

    public ?string $github_username = null;
    public ?array $github_username_error = null;
    public int $github_username_max_len = UserProfile::MAX_LEN;

    public ?string $instagram_username = null;
    public ?array $instagram_username_error = null;
    public int $instagram_username_max_len = UserProfile::MAX_LEN;

    public ?string $phone = null;
    public ?array $phone_error = null;
    public int $phone_max_len = UserProfile::MAX_LEN;

    public ?string $reddit_username = null;
    public ?array $reddit_username_error = null;
    public int $reddit_username_max_len = UserProfile::MAX_LEN;

    public ?string $skype_username = null;
    public ?array $skype_username_error = null;
    public int $skype_username_max_len = UserProfile::MAX_LEN;

    public ?string $steam_id = null;
    public ?array $steam_id_error = null;
    public int $steam_id_max_len = UserProfile::MAX_LEN;

    public ?string $twitter_username = null;
    public ?array $twitter_username_error = null;
    public int $twitter_username_max_len = UserProfile::MAX_LEN;

    public ?string $website = null;
    public ?array $website_error = null;
    public int $website_max_len = UserProfile::MAX_LEN;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'user' => $this->user,
            'display_name' => $this->display_name,
            'display_name_error' => $this->display_name_error,
            'display_name_max_len' => $this->display_name_max_len,
            'email_1' => $this->email_1,
            'email_2' => $this->email_2,
            'email_error' => $this->email_error,
            'email_max_len' => $this->email_max_len,
            'username' => $this->username,
            'username_error' => $this->username_error,
            'username_max_len' => $this->username_max_len,
            'profile' => $this->profile,
            'biography' => $this->biography,
            'biography_error' => $this->biography_error,
            'biography_max_len' => $this->biography_max_len,
            'discord_username' => $this->discord_username,
            'discord_username_error' => $this->discord_username_error,
            'discord_username_max_len' => $this->discord_username_max_len,
            'facebook_username' => $this->facebook_username,
            'facebook_username_error' => $this->facebook_username_error,
            'facebook_username_max_len' => $this->facebook_username_max_len,
            'github_username' => $this->github_username,
            'github_username_error' => $this->github_username_error,
            'github_username_max_len' => $this->github_username_max_len,
            'instagram_username' => $this->instagram_username,
            'instagram_username_error' => $this->instagram_username_error,
            'instagram_username_max_len' => $this->instagram_username_max_len,
            'phone' => $this->phone,
            'phone_error' => $this->phone_error,
            'phone_max_len' => $this->phone_max_len,
            'reddit_username' => $this->reddit_username,
            'reddit_username_error' => $this->reddit_username_error,
            'reddit_username_max_len' => $this->reddit_username_max_len,
            'skype_username' => $this->skype_username,
            'skype_username_error' => $this->skype_username_error,
            'skype_username_max_len' => $this->skype_username_max_len,
            'steam_id' => $this->steam_id,
            'steam_id_error' => $this->steam_id_error,
            'steam_id_max_len' => $this->steam_id_max_len,
            'twitter_username' => $this->twitter_username,
            'twitter_username_error' => $this->twitter_username_error,
            'twitter_username_max_len' => $this->twitter_username_max_len,
            'website' => $this->website,
            'website_error' => $this->website_error,
            'website_max_len' => $this->website_max_len,
        ]);
    }
}
