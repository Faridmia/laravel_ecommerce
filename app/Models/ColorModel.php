<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorModel extends Model
{
    use HasFactory;

    protected $table = 'colors';

    protected $primaryKey = 'color_id';

    public $incrementing = true;

    protected $keyType = 'int';

    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getRecord()
    {
        return self::select('colors.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'colors.created_by')
            ->where('colors.is_delete', '=', 0)
            ->orderBy('colors.color_id', 'desc')->paginate(10);
    }
}
