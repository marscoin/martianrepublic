<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $address
 * @property int|null $userid
 * @property string|null $tag
 * @property string|null $message
 * @property string|null $embedded_link
 * @property string|null $txid
 * @property int|null $blockid
 * @property \DateTime|null $mined
 * @property \DateTime|null $updated_at
 * @property \DateTime|null $created_at
 * @property User $user
 * @property Citizen $citizen
 */
class Feed extends Model {

    protected $table = 'feed';
    protected $appends = ['profile_image'];
    protected $dates = ['mined'];
    protected $casts = [
        'mined' => 'datetime',
    ];

    protected $fillable = [
        'address',
        'userid', 
        'tag',
        'message',
        'embedded_link',
        'txid',
        'blockid',
        'mined'
     ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid'); // Assuming 'userid' is the foreign key in 'feeds' table
    }

    public function getProfileImageAttribute()
    {
        if (!$this->address) {
            return null;
        }

        return 'https://martianrepublic.org/assets/citizen/' . $this->address . '/profile_pic.png';
    }

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'userid');
    }

}

?>