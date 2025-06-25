<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\District;

class ImportProvinces extends Command
{
    protected $signature = 'import:provinces';
    protected $description = 'Import provinces and districts from external API';

    public function handle()
    {
        $url = 'https://provinces.open-api.vn/api/?depth=2';
        $response = Http::get($url);

        if (!$response->ok()) {
            $this->error('Không thể lấy dữ liệu từ API');
            return;
        }

        $data = $response->json();

        foreach ($data as $provinceData) {
            $province = Province::updateOrCreate(
                ['id' => $provinceData['code']],
                ['name' => $provinceData['name']]
            );

            foreach ($provinceData['districts'] as $districtData) {
                District::updateOrCreate(
                    ['id' => $districtData['code']],
                    [
                        'name' => $districtData['name'],
                        'province_id' => $province->id
                    ]
                );
            }
        }

        $this->info('Dữ liệu tỉnh và huyện đã được import thành công!');
    }
}
