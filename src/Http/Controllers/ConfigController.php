<?php

namespace GGPHP\Config\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use GGPHP\Config\Models\LaravelConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    public function updateConfigs(Request $request)
    {
        $data = Arr::except($request->all(), ['_method', '_token']);
        $configs = config('laravelconfig.fields');
        $rules = [];

        foreach ($configs as $config) {
            if (isset($config['validation'])) {
                $rules[$config['code']] = $config['validation'];
            }
        }

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return view('ggphp-config::config')->with('errors', $validator->errors());
        }

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

        return;
    }
}
