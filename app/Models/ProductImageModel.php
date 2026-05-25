<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImageModel extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    static public function getSingle($id)
    {
        return self::find($id);
    }

    public function getImagesLogo()
    {
        if(!empty($this->image_name) && file_exists(public_path('upload/products/'.$this->image_name)) ){
            return url('upload/products/'.$this->image_name);
        }
        return null;
    }
}
