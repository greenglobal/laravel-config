<?php

namespace GGPHP\Config\Http\Controllers;

use GGPHP\Config\Models\GGConfig;
use Illuminate\Support\Facades\Validator;
use Route;

class ThrottleController extends Controller
{
    /**
     * Show the view for the specified resource.
     *
     * @param  int  $orderId
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $routeCollection = Route::getRoutes();
        $throttles = [];
        $throttle_default = json_encode([
            'max_attempts' => GGConfig::MAX_ATTEMPTS_DEFAULT,
            'decay_minutes' => GGConfig::DECAY_MINUTES_DEFAULT,
        ]);

        foreach ($routeCollection as $route) {
            $actions = $route->getAction();
            $isThrottleRoute = is_array($actions['middleware'])
                ? array_filter($actions['middleware'], function ($value) {
                    return (strpos($value, 'throttle:') !== false) ?? false;
                }) : false;

            if (! empty($actions['middleware']) && is_array($actions['middleware']) && ! empty($isThrottleRoute)) {
                continue;
            }

            if (! empty($actions['prefix']) && $actions['prefix'] == 'api') {
                $routeName = $route->getName();
                $routePath = $route->uri();

                if (empty($routeName) && ! empty($routePath)) {
                    $routeName = str_replace(['-', '/'], '_', $routePath);
                } elseif (empty($routeName)) {
                    continue;
                }

                $routeInfo = getConfigByCode($routeName);
                $throttles[] = [
                    'name' => $routeName,
                    'path' => $routePath,
                    'throttleDefault' => [
                        'max_attempts' => GGConfig::MAX_ATTEMPTS_DEFAULT,
                        'decay_minutes' => GGConfig::DECAY_MINUTES_DEFAULT,
                    ],
                    'data' => ! empty($routeInfo)
                        ? json_decode($routeInfo['value'], true) : []
                ];
            }
        }

        $throttleDefault = getConfigByCode('throttle_default');

        if (empty($throttleDefault)) {
            $throttleDefault = GGConfig::create([
                'code' => 'throttle_default',
                'value' => $throttle_default,
                'type' => 'throttle',
                'default' => $throttle_default,
            ]);
        }

        $throttleDefaultId = $throttleDefault->id ?? '';

        return view('ggphp-config::throttle.index', compact('throttles', 'throttleDefaultId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($name)
    {
        $route = GGConfig::where('code', $name)->first();

        $throttle = [
            'max_attempts' => GGConfig::MAX_ATTEMPTS_DEFAULT,
            'decay_minutes' => GGConfig::DECAY_MINUTES_DEFAULT,
        ];

        if (! empty($route)) {
            $throttle = json_decode($route['value'], true);
        }

        return view('ggphp-config::throttle.edit', compact('name', 'throttle'));
    }

    /**
     * Edit's the premade resource of Route.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'max_attempts' => 'required|string|max:255',
            'decay_minutes' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $data = request()->only(['name', 'max_attempts', 'decay_minutes']);

        $throttle = GGConfig::where('code', $data['name'])->first();

        if (! empty($throttle)) {
            $throttle->value = json_encode([
                'max_attempts' => $data['max_attempts'] ?? '',
                'decay_minutes' => $data['decay_minutes'] ?? '',
            ]);

            $throttle->save();
        } else {
            $result = GGConfig::create([
                'code' => $data['name'],
                'value' => json_encode([
                    'max_attempts' => $data['max_attempts'],
                    'decay_minutes' => $data['decay_minutes'],
                ]),
                'type' => 'throttle',
                'default' => json_encode([
                    'max_attempts' => GGConfig::MAX_ATTEMPTS_DEFAULT,
                    'decay_minutes' => GGConfig::DECAY_MINUTES_DEFAULT,
                ])
            ]);
        }

        $throttleDefault = getConfigByCode('throttle_default');

        if (! empty($throttleDefault) && $throttleDefault->code == $data['name']) {
            $routeCollection = Route::getRoutes();

            foreach ($routeCollection as $route) {
                $actions = $route->getAction();

                $isThrottleRoute = array_filter($actions['middleware'], function ($value) {
                    return (strpos($value, 'throttle:') !== false) ?? false;
                });

                if (! empty($actions['middleware']) && is_array($actions['middleware']) && ! empty($isThrottleRoute)) {
                    continue;
                }

                if (! empty($actions['prefix']) && $actions['prefix'] == 'api') {
                    $routeName = $route->getName();
                    $routePath = $route->uri();

                    if (empty($routeName) && ! empty($routePath)) {
                        $routeName = str_replace(['-', '/'], '_', $routePath);
                    } elseif (empty($routeName)) {
                        continue;
                    }

                    $throttle = getConfigByCode($routeName);

                    if (! empty($throttle) && ! empty($throttleDefault)) {
                        $throttle->value = json_encode([
                            'max_attempts' => json_decode($throttleDefault->value, true)['max_attempts']
                                ?? GGConfig::MAX_ATTEMPTS_DEFAULT,
                            'decay_minutes' => json_decode($throttleDefault->value, true)['decay_minutes']
                                ?? GGConfig::DECAY_MINUTES_DEFAULT,
                        ]);

                        $throttle->save();
                    }
                }
            }
        }

        return redirect()->route('config.throttle.index');
    }
}
