<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Token extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    /**
     * creating token hash
     *
     * @return string
     */
    public static function createTokenHash()
    {
        return (string)Str::uuid();
    }

    /**
     * prepare expire at based on days given
     *
     * @param int $days
     * @return string
     */
    public static function prepareExpireAt($days = 30)
    {
        return Carbon::today()->addDays($days)->format('Y-m-d H:i:s');
    }

    /**
     * checking the date is expired or not
     *
     * @return bool
     */
    public function isExpired()
    {
        return Carbon::parse($this->expire_at)->isPast();
    }
}
