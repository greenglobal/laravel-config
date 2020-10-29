<?php

namespace GGPHP\Config\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use GGPHP\Config\Models\GGConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    public function updateConfigs(Request $request)
    {
        $data = Arr::except($request->all(), ['_method', '_token']);
        $configs = config('ggconfig.fields');
        $rules = $booleans = [];

        foreach ($configs as $config) {
            if (isset($config['validation'])) {
                $rules[$config['code']] = $config['validation'];
            }

            if (isset($config['type']) && $config['type'] == 'boolean') {
                $booleans[] = $config['code'];
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return view('ggphp-config::config')->with('errors', $validator->errors());
        }

        // Ìf not checked input boolean is false.
        foreach ($booleans as $value) {
            if (! in_array( $value, array_keys($data))) {
                $data[$value] = false;
            }
        }

        foreach ($data as $code => $value) {
            $configInfo = getConfigByCode($code);

            if ($configInfo) {
                $configInfo->update([
                    'code' => $code,
                    'value' => $value,
                    'default' => getDefaultValue($code)
                ]);
            } else {
                GGConfig::create([
                    'code' => $code,
                    'value' => $value,
                    'default' => getDefaultValue($code)
                ]);
            }
        }

        return;
    }

    public function reset()
    {
        if ($configs = GGConfig::get(['code', 'default'])) {
            return response()->json(['data' => $configs], 200);
        }

        return response()->json(['data' => 'data null'], 400);
    }
}
