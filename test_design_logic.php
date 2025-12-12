<?php

use App\Models\User\ModelDesign;
use Illuminate\Support\Facades\URL;

// Mock request data
$req = new \stdClass();
$req->industry_id = 3;
$req->category_id = 3;
$req->product_type_id = 3;
$req->shoot_type_id = 1;

echo "Searching for Industry: {$req->industry_id}, Category: {$req->category_id}, Product: {$req->product_type_id}, Shoot: {$req->shoot_type_id}\n";

$designs = ModelDesign::where([
    'industry_id' => $req->industry_id,
    'category_id' => $req->category_id,
    'product_type_id' => $req->product_type_id,
    'shoot_type_id' => $req->shoot_type_id,
])->get();

echo "Found " . $designs->count() . " designs.\n";

$designs->transform(function ($design) {
    if ($design->image) {
        // asset() won't include domain in CLI usually, but let's see
        $design->image = asset($design->image);
        $design->thumbnail = $design->image;
    }
    return $design;
});

foreach ($designs as $design) {
    echo "ID: " . $design->id . "\n";
    echo "Image URL: " . $design->image . "\n";
}
