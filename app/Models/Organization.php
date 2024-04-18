<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organization';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['company_name', 'email', 'timestamp'];

    public function contactPersons()
    {
        return $this->hasMany(ContactPerson::class, 'organization_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'organization_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'organization_id');
    }
}
