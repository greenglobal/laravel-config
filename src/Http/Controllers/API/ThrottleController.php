<?php

namespace GGPHP\Config\Http\Controllers\API;

use GGPHP\Config\Models\GGConfig;
use Illuminate\Support\Facades\Validator;
use GGPHP\Config\Http\Controllers\Controller;
use Route;

class ThrottleController extends Controller
{
    /**
     * Get all resource.
     *
     * @return \Illuminate\Http\Response
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

        return response()->json([
            'data' => $throttles ?? [],
            'message' => 'Get the throttles successfully.'
        ]);
    }

    /**
     * Get the specified resource by name
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function get($name)
    {
        $route = GGConfig::where('code', $name)->first();

        if (empty($route)) {
            $routeCollection = Route::getRoutes();

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

                    if (empty($routeName)) {
                        continue;
                    }

                    if ($routeName == $name) {
                        return response()->json([
                            'data' => [
                                'name' => $routeName,
                                'path' => route($routeName),
                                'throttleDefault' => $throttle_default,
                                'data' => []
                            ],
                            'message' => 'Get the throttle successfully.'
                        ], 200);
                    }
                }
            }
        } else {
            return response()->json([
                'data' => [
                    'name' => $name,
                    'path' => route($name),
                    'throttleDefault' => ! empty($route)
                        ? json_decode($route['default'], true) : [],
                    'data' => ! empty($route)
                        ? json_decode($route['value'], true) : []
                ],
                'message' => 'Get the throttle successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Not Found the throttle in system.',
        ], 404);
    }

    /**
     * Edit's the premade resource of Route.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'string|required',
            'max_attempts' => 'string|required|max:255',
            'decay_minutes' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
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

        return response()->json([
            'message' => 'Your field has been updated successfully.'
        ], 200);
    }

    /**
     * Reset all throttle value to default
     *
     * @return \Illuminate\Http\Response
     */
    public function reset()
    {
        $throttles = GGConfig::where('type', 'throttle')->get();

        foreach ($throttles as $key => $throttle) {
            $results = GGConfig::find($throttle['id']);

            if (! empty($results)) {
                $value = json_encode([
                    'max_attempts' => GGConfig::MAX_ATTEMPTS_DEFAULT,
                    'decay_minutes' => GGConfig::DECAY_MINUTES_DEFAULT
                ]);
                $results->value = $value;
                $results->save();
            }
        }

        return response()->json([
            'message' => 'Reseted successfully.'
        ], 200);
    }
}
