<?php

namespace GGPHP\Config\Http\Controllers;

use GGPHP\Config\Models\GGConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GGPHP\Config\Services\FirebaseService;

class ConfigController extends Controller
{
    /**
     * Show the view for the configuration fields
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $configs = config('config.system');
        $data = array_filter($configs, function($value) {
            if (! empty($value['key']))
                return $value['key'] == 'configuration.system.fields';
        });

        // Get user role, exp for developer: admin
        $userRole = 'admin';

        return view('ggphp-config::config')->with(
            ['data' => array_pop($data), 'userRole' => $userRole]
        );
    }

    /**
     * Update the configuration fields.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function update(Request $request)
    {
        $data = $request->except(['_method', '_token']);
        $configs = config('config.system');
        $rules = $booleans = $types = [];

        // Get user role, exp for developer: admin
        $userRole = 'admin';

        // Get validation from config file
        foreach ($configs as $config) {
            if ($config['key'] == 'configuration.system.fields') {
                foreach ($config['fields'] as $field) {
                    if (! isset($field['access']) || ! in_array($userRole, $field['access']))
                        continue;

                    if (isset($field['validation']))
                        $rules[$field['code']] = $field['validation'];

                    if (isset($field['type']) && $field['type'] == 'boolean')
                        $booleans[] = $field['code'];

                    $types[$field['code']] = $field['type'];
                }
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails())
            return view('ggphp-config::config')->with('errors', $validator->errors());

        // Default is false if boolean field empty
        foreach ($booleans as $value)
            if (! in_array($value, array_keys($data)))
                $data[$value] = false;

        foreach ($data as $code => $value) {
            $attributes = [
                'code' => $code,
                'value' => $value ?? '',
                'type' => $types[$code] ?? '',
                'default' => getDefaultValueByCode($code) ?? ''
            ];

            if (env('STORE_DB', 'database') == 'database') {
                $configInfo = getConfigByCode($code);

                if ($configInfo) {
                    $configInfo->value = $value ?? '';
                    $configInfo->save();
                } else {
                    GGConfig::create($attributes);
                }
            } elseif (env('STORE_DB') == 'firebase') {
                $firebaseService = new FirebaseService;
                $configInfo = $firebaseService->getDataByCode($code);

                if (empty($configInfo)) {
                    $firebaseService->addData(GGConfig::FIELD_REFERENCE, $attributes);
                } elseif ($key = getKeyByCode($code)) {
                    $firebaseService->setData(GGConfig::FIELD_REFERENCE . '/' . $key, $attributes);
                }
            }
        }

        $request->session()->flash('message', 'The update was successful!');

        return $request->path() == 'configuration/field/edit' ? view('ggphp-config::config') : redirect()->route('config.field.edit');
    }

    /**
     * Reset the fields to default
     * @return \Illuminate\Http\Response
     */
    public function reset()
    {
        $configs = [];

        if (env('STORE_DB', 'database') == 'database') {
            $configs = GGConfig::get(['code', 'type', 'default']);

            if (empty($configs))
                return response()->json(['data' => null], 400);

        } elseif (env('STORE_DB') == 'firebase') {
            $firebaseService = new FirebaseService;
            $reference = $firebaseService->retrieveData(GGConfig::FIELD_REFERENCE);
            $data = $reference->getValue() ?? [];

            foreach($data as $value) {
                $configs[] = [
                    'code' => $value['code'],
                    'type' => $value['type'],
                    'default' => $value['default']
                ];
            }
        }

        return response()->json(['data' => $configs], 200);
    }
}
