<?php

namespace GGPHP\Config\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use GGPHP\Config\Models\LaravelConfig;

class ConfigController extends Controller
{
    public function updateConfigs(Request $request)
    {
        $data = Arr::except($request->all(), ['_method', '_token']);

        foreach ( $data as $code => $value) {
            $infoConfig = app('GGPHP\Config\Helpers\Config')->getConfigOf($code);

            if ($infoConfig) {
                $infoConfig->update([
                    'code' => $code,
                    'value' => $value
                ]);
            } else {
                LaravelConfig::create([
                    'code' => $code,
                    'value' => $value
                ]);
            }
        }

        return redirect()->back();
    }
}
