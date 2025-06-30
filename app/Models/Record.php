<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Record extends Model
{
    protected $table = 'test';
    protected $fillable = ['uuid', 'name', 'description', 'code', 'status'];

    public $timestamps = false;
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}

// class Record extends Model
// {
//     // Laravel no sabrá que la PK es 'uuid', así que se lo decimos:
//     protected $primaryKey = 'uuid';
//     public $incrementing = false;
//     protected $keyType = 'string';

//     // Nombre de la tabla
//     protected $table = 'records';

//     // Campos rellenables
//     protected $fillable = [
//         'uuid',
//         'name',
//         'description',
//         'code',
//         'status',
//     ];

//     // Crear automáticamente un UUID si no se pasa
//     protected static function booted()
//     {
//         static::creating(function ($record) {
//             if (empty($record->uuid)) {
//                 $record->uuid = (string) Str::uuid();
//             }
//         });
//     }
// }