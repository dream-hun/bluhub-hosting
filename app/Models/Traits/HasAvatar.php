<?php

namespace App\Models\Traits;

trait HasAvatar
{
    public function getUserAvatarAttribute(): ?string
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }

        $hash = md5(strtolower(trim($this->email)));

        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }
}
