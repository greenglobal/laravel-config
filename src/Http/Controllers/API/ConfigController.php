<?php

namespace GGPHP\Config\Http\Controllers\API;

use GGPHP\Config\Models\GGConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GGPHP\Config\Services\FirebaseService;
use GGPHP\Config\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    /**
     * Get all data configure
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = GGConfig::all();

        foreach ($results as $key => $value) {
            $results[$key]['value'] = json_decode($value['value']);
            $results[$key]['default'] = json_decode($value['default']);
        }

        return response()->json([
            'data' => $results ?? [],
            'message' => 'Get the fields successfully.'
        ]);
    }

    /**
     * Get all data configure
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $results = GGConfig::find($id);

        if (empty($results)) {
            return response()->json([
                'message' => 'Not Found the field in system.',
            ], 404);
        }

        return response()->json([
            'data' => $results ?? [],
            'message' => 'Get the field successfully.'
        ], 200);
    }

    /**
     * Create the new field
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'code' => 'string|required|max:255',
            'value' => 'array|required',
            'type' => 'string|required|max:255'
        ]);

        $data = $request->all();

        $results = GGConfig::create([
            'code' => $data['code'],
            'value' => json_encode($data['value']),
            'type' => $data['type'],
            'default' => json_encode($data['value'])
        ]);

        if (empty($results)) {
            return response()->json([
                'message' => 'Created the field failed.',
            ], 400);
        }

        return response()->json([
            'data' => $results ?? null,
            'message' => 'Field has been created successfully.'
        ], 201);
    }

    /**
     * Update the field
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'integer|required|exists:gg_config,id',
            'code' => 'string|required|max:255',
            'value' => 'array|required',
            'type' => 'string|required|max:255'
        ]);

        $data = $request->all();

        if (! empty($data['value'])) {
            $data['value'] = json_encode($data['value']);
        }

        $results = GGConfig::where('id', $data['id'])
            ->update($data);

        if (empty($results)) {
            return response()->json([
                'message' => 'Updated the field failed.',
            ], 400);
        }

        return response()->json([
            'message' => 'Your field has been updated successfully.'
        ], 200);
    }

    /**
     * Delete data configure
     *
     * @param int $id
     */
    public function delete($id)
    {
        $results = GGConfig::find($id);

        if (empty($results)) {
            return response()->json([
                'message' => 'Not Found the field in system.',
            ], 404);
        }

        $isDelete = $results->delete();

        if ($isDelete) {
            return response()->json([
                'message' => 'Delete the field failed.'
            ], 400);
        }

        return response()->json([
            'message' => 'Delete the field successfully.'
        ], 204);
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

            if (empty($configs)) {
                return response()->json(['data' => null], 400);
            }
        } elseif (env('STORE_DB') == 'firebase') {
            $firebaseService = new FirebaseService();
            $reference = $firebaseService->retrieveData(GGConfig::FIELD_REFERENCE);
            $data = $reference->getValue() ?? [];

            foreach ($data as $value) {
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
