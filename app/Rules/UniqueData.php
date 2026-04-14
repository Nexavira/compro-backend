<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniqueData implements ValidationRule
{
    protected $identifier;

    protected $table;

    protected $column;

    public function __construct($table, $column, $identifier = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->identifier = $identifier;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table($this->table)->where($this->column, $value)->where('deleted_at', null);
        
        if(Str::isUuid($this->identifier)) {
            $this->identifier != null ? $query->where('uuid', '!=', $this->identifier) : null;
        }else {
            $this->identifier != null ? $query->where('id', '!=', $this->identifier) : null;
        }

        $result = empty($query->first()) ? true : false;
        
        if($result == false){
            $fail('Atribut :attribute sudah digunakan.');
        }
    }
}
