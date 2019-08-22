<?php

namespace Galahad\LaravelAddressing\Support\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Galahad\LaravelAddressing\Entity\Country;

class AdministrativeAreaCodeRule implements Rule
{
    /**
     * @var \Galahad\LaravelAddressing\Entity\Country
     */
    protected $country;

    /**
     * Constructor.
     *
     * @param \Galahad\LaravelAddressing\Entity\Country $country
     */
    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value) : bool
    {
        if (! is_string($value)) {
            return false;
        }

        return null !== $this->country->administrativeArea($value);
    }

    /**
     * {@inheritdoc}
     */
    public function message() : string
    {
        $type = $this->country->addressFormat()->getAdministrativeAreaType();

        return trans('laravel-addressing::validation.administrative_area_code', compact('type'));
    }
}