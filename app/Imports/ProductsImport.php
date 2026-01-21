<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
// public function model(array $row)
// {
    
//     $category = $row['category'] ?? 'Default Category';
//     $color    = $row['color'] ?? 'Default Color';
//     $size     = $row['size'] ?? 'Default Size';

//     $category = Category::firstOrCreate(['name' => $category]);
//     $color    = Color::firstOrCreate(['name' => $color]);
//     $size     = Size::firstOrCreate(['name' => $size]);

//     $imagePath = null;
//     if (!empty($row['image'])) {
//         try {
//             $imageContents = file_get_contents($row['image']);
//             $manager = new ImageManager(new Driver());
//             $image = $manager->read($imageContents)->toWebp(90);

//             $imageName = uniqid() . '.webp';
//             $imagePath = 'products/' . $imageName;
//             Storage::disk('public')->put($imagePath, $image);
//         } catch (\Exception $e) {
          
//             $imagePath = null;
//         }
//     }

//     return new Product([
//         'name'        => $row['name'] ?? 'Unnamed Product',
//         'category_id' => $category?->id,
//         'color_id'    => $color?->id,
//         'size_id'     => $size?->id,
//         'qty'         => $row['qty'] ?? 0,
//         'price'       => $row['price'] ?? 0,
//         'image'       => $imagePath,
//     ]);
// }

    public function model(array $row)
    {
        return new Product([
            'name'        => $row['name'] ?? 'Unnamed Product',
            'category_id' => Category::firstOrCreate(['name'=>$row['category']])->id,
            'color_id'    => Color::firstOrCreate(['name'=>$row['color']])->id,
            'size_id'     => Size::firstOrCreate(['name'=>$row['size']])->id,
            'qty'         => intval($row['qty'] ?? 0),
            'price'       => floatval($row['price'] ?? 0),
            'image'       => $this->processImage($row['image'] ?? null),
        ]);
    }
}
