<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $username
 * @property ?string $fullname
 * @property string $password
 * @property ?string $tmp_password
 * @property ?string $salt
 * @property ?string $email
 * @property ?string $phone
 * @property ?int $gender
 * @property ?string $company
 * @property ?string $address
 * @property ?string $country
 * @property ?string $city
 * @property ?string $zip
 * @property ?string $lang
 * @property int $role_id
 * @property ?int $file_id
 * @property bool $active
 * @property bool $blocked
 * @property ?string $comment
 * @property ?int $remote_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read UserRole $role
 * @property-read File $file
 * @property-read Cart $cart
 * @property-read UserToken[] $tokens
 * @property-read UserAddress[] $addresses
 * @property-read Order[] $orders
 */
class User extends \Vesp\Models\User
{
    protected $guarded = ['id', 'remote_id', 'created_at', 'updated_at'];
    protected $fillable = [];
    protected $hidden = ['password', 'tmp_password', 'salt'];

    public function setAttribute($key, $value)
    {
        // remove pbkdf2 salt to use native password methods
        if ($key === 'password' && !empty($value)) {
            parent::setAttribute('salt', null);
        } elseif ($key === 'tmp_password') {
            $value = password_hash($value, PASSWORD_DEFAULT);
        }

        return parent::setAttribute($key, $value);
    }

    public function verifyPassword(string $password): bool
    {
        $hash = $this->getAttribute('password');
        if ($salt = $this->getAttribute('salt')) {
            // See modx/hashing/modpbkdf2.class.php
            $pbkdf2 = base64_encode(hash_pbkdf2('sha256', $password, $salt, 1000, 32, true));
            if ($hash === $pbkdf2) {
                return true;
            }
        }

        return password_verify($password, $hash);
    }

    public function resetPassword($length = 20): string
    {
        $tmp = bin2hex(random_bytes($length));
        $this->setAttribute('tmp_password', $tmp);
        $this->save();

        return $tmp;
    }

    public function activatePassword(string $password): bool
    {
        $hash = $this->getAttribute('tmp_password');
        if (!password_verify($password, $hash)) {
            return false;
        }
        $this->active = true;
        $this->tmp_password = null;
        $this->save();

        return true;
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(UserToken::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}