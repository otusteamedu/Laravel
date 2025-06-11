<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('price');
            $table->geometry('zone');
            $table->timestamps();
        });

        $data = [
            [
                'title' => 'near', 
                'price' => 200,
                'coords' => [
                    [58.004481, 56.181631],
                    [58.020273, 56.252322],
                    [57.999265, 56.270446],
                    [57.990337, 56.233553],
                    [58.004481, 56.181631],
                ]
            ],
            [
                'title' => 'middle', 
                'price' => 300,
                'coords' => [
                    [58.006341, 56.124592],
                    [58.035899, 56.307232],
                    [57.996768, 56.335864],
                    [57.967807, 56.149503],
                    [58.006341, 56.124592],
                ]
            ],
            [
                'title' => 'far', 
                'price' => 500,
                'coords' => [
                    [58.006022, 55.911359],
                    [58.113861, 56.297623],
                    [58.113861, 56.407623],
                    [58.009988, 56.367834],
                    [57.960000, 56.307834],
                    [57.933245, 56.164821],
                    [58.006022, 55.911359],
                ]
            ],
        ];

        foreach ($data as $item) {
            $t = $item['title'];
            $p = $item['price'];
            $coords = $item['coords'];

            if (count($coords) == 5) {
                $x1 = $coords[0][1];
                $y1 = $coords[0][0];
                $x2 = $coords[1][1];
                $y2 = $coords[1][0];
                $x3 = $coords[2][1];
                $y3 = $coords[2][0];
                $x4 = $coords[3][1];
                $y4 = $coords[3][0];
                $x5 = $coords[4][1];
                $y5 = $coords[4][0];
                $sql = "INSERT INTO delivery_zones(title, price, created_at, updated_at, zone)
                    VALUES ('$t', '$p', now(), now(), ST_GeomFromText('POLYGON(($x1 $y1, $x2 $y2, $x3 $y3, $x4 $y4, $x5 $y5))', 4326))";
            } else {
                $x1 = $coords[0][1];
                $y1 = $coords[0][0];
                $x2 = $coords[1][1];
                $y2 = $coords[1][0];
                $x3 = $coords[2][1];
                $y3 = $coords[2][0];
                $x4 = $coords[3][1];
                $y4 = $coords[3][0];
                $x5 = $coords[4][1];
                $y5 = $coords[4][0];
                $x6 = $coords[5][1];
                $y6 = $coords[5][0];
                $x7 = $coords[6][1];
                $y7 = $coords[6][0];
                $sql = "INSERT INTO delivery_zones(title, price, created_at, updated_at, zone)
                    VALUES ('$t', '$p', now(), now(), ST_GeomFromText('POLYGON(($x1 $y1, $x2 $y2, $x3 $y3, $x4 $y4, $x5 $y5, $x6 $y6, $x7 $y7))', 4326))";
            }
            
            DB::insert($sql);
        }       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
