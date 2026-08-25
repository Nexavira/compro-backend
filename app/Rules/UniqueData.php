<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniqueData implements ValidationRule
{
    protected $identifier;

    protected $table;

    protected $column;

    public function __construct($table, $column = null, $identifier = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->identifier = $identifier;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $columnToCheck = $this->column ?? $attribute;

        if ($this->table instanceof Model || $this->table instanceof BaseModel) {
            $query = $this->table->newQuery();
        } else {
            $query = DB::table($this->table);
        }

        $query = $query->where($columnToCheck, $value)->whereNull('deleted_at');

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
