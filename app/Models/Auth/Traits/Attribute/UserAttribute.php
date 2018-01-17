<?php

namespace App\Models\Auth\Traits\Attribute;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

trait UserAttribute
{
    /**
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        return $this->getAvatar();
    }

    /**
     * @param bool $size
     *
     * @return mixed
     */
    public function getAvatar()
    {
        if (!$this->avatar_date) {
            Storage::put('public/avatars/' . $this->user_id . '/' . $this->user_id . '.png', \Avatar::create($this->username)->save($this->user_id . '.png', 100));
            $this->avatar_date = Carbon::now()->timestamp; $this->update(['avatar_date' => Carbon::now()->timestamp]);
        }

        return Storage::url('public/avatars/' . $this->user_id . '/' . $this->user_id . '.png?' . $this->avatar_date);
    }
}
