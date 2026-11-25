<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'role',
        'foto_profil',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findByEmailOrUsername(string $login)
    {
        return $this->where('email', $login)
                    ->orWhere('username', $login)
                    ->first();
    }
}
