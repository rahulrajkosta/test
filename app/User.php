<?php
namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $table = 'users';

    protected $guarded = ['id'];
    
    protected $fillable = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }    

    public function userAddresses(){
        return $this->hasMany('App\UserAddress', 'user_id');
    }


    public function userWishlist(){
        return $this->hasMany('App\UserWishlist', 'user_id');
    }


    public function getState(){
        return $this->belongsTo('App\State', 'state_id');
    }
    public function getCity(){
        return $this->belongsTo('App\City', 'city_id');
    }
    public function getParent(){
        return $this->belongsTo('App\User', 'refer_user_id');
    }

    public function wishlistProducts(){
        return $this->belongsToMany('App\Product', 'user_wishlist', 'user_id')->withPivot('user_id', 'product_id', 'size_id');
    }


    public function userState(){
        return $this->belongsTo('App\State', 'state');
    }

    public function userCity(){
        return $this->belongsTo('App\City', 'city');
    }

    public function userCountry(){
        return $this->belongsTo('App\Country', 'country');
    }

    public function userWallet(){
        return $this->hasMany('App\UserWallet');
    }

}