<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Counseling;

class Service extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function is_argentina(){
      $country_name = request()->session()->get('country', ""); // Second argument is a default value
      if ($country_name == ""){
        $ip = request()->ip();
        $location = \Location::get($ip);
        if($location) {
          $country_name = $location->countryName;
        } else {
          $country_name = "Argentina";
        }
        request()->session()->put('country', $country_name);
      }

      return $country_name  == "Argentina";
    }

    public function price_symbol()
    {
      if ($this->is_argentina()){
        return "$ ";
      } else {
        return "USD ";
      }

    }

    public function price_raw()
    {
      if ($this->is_argentina()){
        return ($this->price_ars + 0);
      } else {
        return ($this->price_usd + 0);
      }
    }

    public function price()
    {
      return $this->price_symbol() . $this->price_raw();
    }

    public function fullname($full = true){
      $counseling = Counseling::where('service_id', $this->id)->first();
      if (!is_null($counseling)){
        $pre = $full ? 'Consultoría Estrategica “' : '“' ;
        return $pre . $this->name . '”';
      } else {
        return $this->name;
      }
    }
}
