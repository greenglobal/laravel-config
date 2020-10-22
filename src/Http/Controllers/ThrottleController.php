<?php

namespace GGPHP\Config\Http\Controllers;

use App\Http\Controllers\Controller;
use GGPHP\Config\Models\LaravelConfig;
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

        foreach ($routeCollection as $route) {
            $actions = $route->getAction();

            if (! empty($actions['middleware']) &&
                ! empty(array_filter($actions['middleware'], function($value) {
                    return (strpos($value, 'throttle:') !== false) ?? false;
                }))
            )
                continue;

            if (! empty($actions['prefix']) && $actions['prefix'] == 'api') {
                $routeName = $route->getName();
                $routePath = $route->uri();

                if (empty($routeName) && ! empty($routePath)) {
                    $routeName = str_replace(['-', '/'], '_', $routePath);
                } elseif (empty($routeName)) {
                    continue;
                }

                $routeInfo = app('GGPHP\Config\Helpers\Config')->getConfigByCode($routeName);

                if (! empty($routeInfo)) {
                    $throttles[] = [
                        'id' => $routeInfo['id'],
                        'name' => $routeName,
                        'path' => $routePath,
                        'data' => json_decode($routeInfo['value'], true)
                    ];
                } else {
                    $result = LaravelConfig::create([
                        'code' => $routeName,
                        'value' => json_encode([
                            'max_attempts' => LaravelConfig::MAX_ATTEMPTS_DEFAULT,
                            'decay_minutes' => LaravelConfig::DECAY_MINUTES_DEFAULT,
                        ]),
                    ]);

                    if (! empty($result)) {
                        $throttles[] = [
                            'id' => $result['id'],
                            'name' => $routeName,
                            'path' => $routePath,
                            'data' => json_decode($result['value'], true)
                        ];
                    }
                }
            }
        }

        $throttleDefault = app('GGPHP\Config\Helpers\Config')->getConfigOf('throttle_default');

        if (empty($throttleDefault)) {
            $throttleDefault = LaravelConfig::create([
                'code' => 'throttle_default',
                'value' => json_encode([
                    'max_attempts' => LaravelConfig::MAX_ATTEMPTS_DEFAULT,
                    'decay_minutes' => LaravelConfig::DECAY_MINUTES_DEFAULT,
                ]),
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
    public function edit($id)
    {
        $route = LaravelConfig::findOrFail($id);
        $throttle = json_decode($route['value'], true);

        return view('ggphp-config::throttle.edit', compact('id', 'throttle'));
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
            'id' => 'required|string|exists:laravel_config,id',
            'max_attempts' => 'required|string|max:255',
            'decay_minutes' => 'required|integer',
        ]);

        if ($validator->fails())
            return redirect()->back()->with('errors', $validator->errors());

        $data = request()->only(['id', 'max_attempts', 'decay_minutes']);

        $throttle = LaravelConfig::findOrFail($data['id']);

        if (! empty($throttle)) {
            $throttle->value = json_encode([
                'max_attempts' => $data['max_attempts'] ?? '',
                'decay_minutes' => $data['decay_minutes'] ?? '',
            ]);

            $throttle->save();
        }

        $throttleDefault = app('GGPHP\Config\Helpers\Config')->getConfigOf('throttle_default');

        if (! empty($throttleDefault) && $throttleDefault->id == $data['id']) {
            $routeCollection = Route::getRoutes();

            foreach ($routeCollection as $route) {
                $actions = $route->getAction();

                if (! empty($actions['middleware']) &&
                    ! empty(array_filter($actions['middleware'], function($value) {
                        return (strpos($value, 'throttle:') !== false) ?? false;
                    }))
                )
                    continue;

                if (! empty($actions['prefix']) && $actions['prefix'] == 'api') {
                    $routeName = $route->getName();
                    $routePath = $route->uri();

                    if (empty($routeName) && ! empty($routePath)) {
                        $routeName = str_replace(['-', '/'], '_', $routePath);
                    } elseif (empty($routeName)) {
                        continue;
                    }

                    $throttle = app('GGPHP\Config\Helpers\Config')->getConfigOf($routeName);

                    if (! empty($throttle) && ! empty($throttleDefault)) {
                        $throttle->value = json_encode([
                            'max_attempts' => json_decode($throttleDefault->value, true)['max_attempts'] ?? LaravelConfig::MAX_ATTEMPTS_DEFAULT,
                            'decay_minutes' => json_decode($throttleDefault->value, true)['decay_minutes'] ?? LaravelConfig::DECAY_MINUTES_DEFAULT,
                        ]);

                        $throttle->save();
                    }
                }
            }
        }

        return redirect()->route('api.throttle.index');
    }
}
