<?php

namespace LaraDev\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaraDev\Suporte\Cropper;
use LaraDev\Support\Utils;
use LaraDev\User;

class Property extends Model
{
    protected $fillable = [
        'sale',
        'rent',
        'category',
        'type',
        'user',
        'sale_price',
        'rent_price',
        'tribute',
        'condominium',
        'description',
        'bedrooms',
        'suites',
        'bathrooms',
        'rooms',
        'garage',
        'garage_covered',
        'area_total',
        'area_util',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city',
        'air_conditioning',
        'bar',
        'library',
        'barbecue_grill',
        'american_kitchen',
        'fitted_kitchen',
        'pantry',
        'edicule',
        'office',
        'bathtub',
        'fireplace',
        'lavatory',
        'furnished',
        'pool',
        'steam_room',
        'view_of_the_sea',
        'status',
        'title',
        'slug',
        'headline',
        'experience'
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property', 'id')->orderBy('cover', 'ASC');
    }

    public function getCover()
    {
        $images = $this->images();
        $cover = $images->where('cover', 1)->first();

        if (!$cover) {
            $images = $this->images();
            $cover = $images->first(['path']);
        }

        if (!$cover) {
            if (empty($cover['path']) || !File::exists('../public/storage/' . $cover['path'])) {
                return url(asset('backend/assets/images/no-image.jpg'));
            }
        }

        return Storage::url(Cropper::thumb($cover['path'], 1366, 768));
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 0);
    }

    public function scopeSale($query)
    {
        return $query->where('sale', 1);
    }

    public function scopeRent($query)
    {
        return $query->where('rent', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function getSalePriceAttribute($value)
    {
        return Utils::convertFloatToCurrency($value);
    }

    public function setSalePriceAttribute($value)
    {
        $this->attributes['sale_price'] = Utils::convertStringToDouble($value);
    }

    public function getRentPriceAttribute($value)
    {
        return Utils::convertFloatToCurrency($value);
    }

    public function setRentPriceAttribute($value)
    {
        $this->attributes['rent_price'] = Utils::convertStringToDouble($value);
    }

    public function getStatusAttribute($value)
    {
        return ($value == 1 ? true : false);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }

    public function getTributeAttribute($value)
    {
        return Utils::convertFloatToCurrency($value);
    }

    public function setTributeAttribute($value)
    {
        $this->attributes['tribute'] = Utils::convertStringToDouble($value);
    }

    public function getCondominiumAttribute($value)
    {
        return Utils::convertFloatToCurrency($value);
    }

    public function setCondominiumAttribute($value)
    {
        $this->attributes['condominium'] = Utils::convertStringToDouble($value);
    }

    public function setZipcodeAttribute($value)
    {
        $this->attributes['zipcode'] = Utils::clearField($value);
    }

    public function getZipcodeAttribute($value)
    {
        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }

    public function setSaleAttribute($value)
    {
        $this->attributes['sale'] = Utils::setValueCheckBox($value);
    }

    public function setRentAttribute($value)
    {
        $this->attributes['rent'] = Utils::setValueCheckBox($value);
    }

    public function setAirConditioningAttribute($value)
    {
        $this->attributes['air_conditioning'] = Utils::setValueCheckBox($value);
    }

    public function setBarAttribute($value)
    {
        $this->attributes['bar'] = Utils::setValueCheckBox($value);
    }

    public function setLibraryAttribute($value)
    {
        $this->attributes['library'] = Utils::setValueCheckBox($value);
    }

    public function setBarbecueGrillAttribute($value)
    {
        $this->attributes['barbecue_grill'] = Utils::setValueCheckBox($value);
    }

    public function setAmericanKitchenAttribute($value)
    {
        $this->attributes['american_kitchen'] = Utils::setValueCheckBox($value);
    }

    public function setFittedKitchenAttribute($value)
    {
        $this->attributes['fitted_kitchen'] = Utils::setValueCheckBox($value);
    }

    public function setPantryAttribute($value)
    {
        $this->attributes['pantry'] = Utils::setValueCheckBox($value);
    }

    public function setEdiculeAttribute($value)
    {
        $this->attributes['edicule'] = Utils::setValueCheckBox($value);
    }

    public function setOfficeAttribute($value)
    {
        $this->attributes['office'] = Utils::setValueCheckBox($value);
    }

    public function setBathtubAttribute($value)
    {
        $this->attributes['bathtub'] = Utils::setValueCheckBox($value);
    }

    public function setFireplaceAttribute($value)
    {
        $this->attributes['fireplace'] = Utils::setValueCheckBox($value);
    }

    public function setLavatoryAttribute($value)
    {
        $this->attributes['lavatory'] = Utils::setValueCheckBox($value);
    }

    public function setFurnishedAttribute($value)
    {
        $this->attributes['furnished'] = Utils::setValueCheckBox($value);
    }

    public function setPoolAttribute($value)
    {
        $this->attributes['pool'] = Utils::setValueCheckBox($value);
    }

    public function setSteamRoomAttribute($value)
    {
        $this->attributes['steam_room'] = Utils::setValueCheckBox($value);
    }

    public function setViewOfTheSeaAttribute($value)
    {
        $this->attributes['view_of_the_sea'] = Utils::setValueCheckBox($value);
    }

    public function setSlug()
    {
        $this->attributes['slug'] = null;
        if (!empty($this->title)) {
            $this->attributes['slug'] = str_slug($this->title) . "-$this->id";
            $this->save();
        }
    }
}
