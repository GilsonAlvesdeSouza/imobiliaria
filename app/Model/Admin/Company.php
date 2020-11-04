<?php

namespace LaraDev\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use LaraDev\Support\Utils;
use LaraDev\User;

class Company extends Model
{
    protected $fillable = [
        'user',
        'social_name',
        'alias_name',
        'document_company',
        'document_company_secondary',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function setDocumentCompanyAttribute($value)
    {
        $this->attributes['document_company'] = Utils::clearField($value);
    }

    public function getDocumentCompanyAttribute($value)
    {
        return substr($value, 0, 2) . '.' . substr($value, 2, 3) . '.' . substr($value, 5, 3) . '/' . substr($value, 8, 4) . '-' . substr($value, 12, 2);
    }
}
