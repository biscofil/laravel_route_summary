# Laravel Submodels

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Travis][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]
[![Coverage Status](https://coveralls.io/repos/github/biscofil/laravel-submodels/badge.svg)](https://coveralls.io/github/biscofil/laravel-submodels?branch=v2)

Create submodels in Laravel

## Installation

Via Composer

``` bash
composer require biscofil/laravel-submodels
```

## Usage

``` php
>>> User::find(1)
=> App\AdminUser {#3182
     id: 1,
     first_name: "something",
     last_name: "something"
     is_admin: true,
     admin_parameter: "something"

>>> User::find(2)
=> App\User {#3164
     id: 2,
     first_name: "something",
     last_name: "something",
     is_admin: false
```

In order to accomplish this result, each Model that has to be extended must implement `getSubModelClass` that returns the right class depending on conditions.

``` php
class User extends Authenticatable{

    use HasSubModels;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'is_admin'
    ];

    /**
    * @param $model
    * @return string|null
    */
   public function getSubModelClass($model){
       $class = null;
       if ($model->isAdmin()) {
           $class = AdminUser::class;
       } elseif ($model->isCustomer()) {
           $class = CustomerUser::class;
       }
       return $class;
   }

   /**
     * @param $query
     * @return mixed
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', '=', true);
    }

}
```

On the other side, each sub model can add the `appendedFillable` PRIVATE property that contains the list of fillable parameters. 
This list will be merged with the list of the parent class. 
The same happens for the `appendedCasts` array. 

``` php
class AdminUser extends User{

    use HasAppendedFields;

    private $appendedFillable = [
        'admin_parameter',
        'is_a_cool_admin
    ];

    private $appendedCasts = [
         'is_a_cool_admin' => 'bool'
    ];

    public function newQuery()
    {
        return $this->scopeAdmins(parent::newQuery());
    }

}

```

## Credits

- [Filippo Bisconcin][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license) for more information.

[ico-version]: https://img.shields.io/packagist/v/biscofil/laravel-submodels.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/biscofil/laravel-submodels.svg?style=flat-square
[ico-travis]: https://api.travis-ci.org/biscofil/laravel-submodels.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/biscofil/laravel-submodels
[link-downloads]: https://packagist.org/packages/biscofil/laravel-submodels
[link-travis]: https://travis-ci.org/biscofil/laravel-submodels
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/biscofil
[link-contributors]: ../../contributors
