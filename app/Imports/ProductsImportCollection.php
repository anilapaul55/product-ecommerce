<?php

namespace App\Imports;

use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;

use Intervention\Image\ImageManager;

use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ProductsImportCollection implements ToCollection
{
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key=>$row) {
            if($key == 0){
                continue;
            }
            $name     = $row[0] ?? 'Unnamed Product';
            $category = $row[1] ?? 'Default Category';
            $color    = $row[2] ?? 'Default Color';
            $size     = $row[3] ?? 'Default Size';
            $qty      = intval($row[4] ?? 0);
            $price    = floatval($row[5] ?? 0);
            $imageUrl = $row[6] ?? null;

            $category = Category::firstOrCreate(['name' => $category]);
            $color    = Color::firstOrCreate(['name' => $color]);
            $size     = Size::firstOrCreate(['name' => $size]);

            $imagePath = null;
            if (!empty($imageUrl)) {
                try {
                    $response = Http::get($imageUrl);

                if ($response->successful()) {
                    $imageBinary = $response->body(); // raw image data
                    $imagePath = $this->uploadWebpFromUrl($imageBinary);
                }

                } catch (\Exception $e) {
                    $imagePath = null;
                }
            }

            Product::create([
                'name'        => $name,
                'category_id' => $category->id,
                'color_id'    => $color->id,
                'size_id'     => $size->id,
                'qty'         => $qty,
                'price'       => $price,
                'image'       => $imagePath,
            ]);
        }
    }


private function uploadWebpFromUrl($imageBinary)
{
    $filename = uniqid() . '.webp';
    $path = 'products/' . $filename;

    $manager = new ImageManager(new GdDriver());

    $img = $manager->read($imageBinary)
                   ->toWebp(80);

    Storage::disk('public')->put($path, (string) $img);

    return $path;
}
}