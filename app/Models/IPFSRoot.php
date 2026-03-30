<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IPFSRoot extends Model {

    protected $table = 'ipfs_root';

    protected $fillable = [
        'folder_hash',
        'author',
    ];

}

?>