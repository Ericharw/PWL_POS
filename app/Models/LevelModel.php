<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\UserModel;

class LevelModel extends Model
{
    protected $table = 'm_level';

    protected $primaryKey = 'level_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

    protected $fillable = ['level_kode', 'level_nama'];
}