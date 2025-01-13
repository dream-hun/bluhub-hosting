<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'company_logo',
        'company_description',
        'email',
        'phone',
        'alternative_phone',
        'whatsapp',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'working_hours',
        'google_maps_embed',
        'google_analytics_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'footer_text',
        'copyright_text',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
    ];

    /**
     * Get the settings
     *
     * @param  string|array  $keys
     *
     * @return mixed
     */
    public static function get($keys)
    {
        if (is_array($keys)) {
            return self::whereIn('key', $keys)->pluck('value', 'key');
        }

        $setting = self::where('key', $keys)->first();

        return $setting ? $setting->value : null;
    }

    /**
     * Set the setting
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return bool
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
