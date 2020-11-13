<?php

return [
    [
        'key' => 'configuration.system.fields',
        'name' => 'System configurations',
        'fields' => [
            [
                'code' => 'code-1',
                'type' => 'text',
                'name' => 'name 1',
                'title' => 'title 1',
                'access' => ['admin'],
            ],
            [
                'code' => 'code-2',
                'type' => 'text',
                'name' => 'name 2',
                'title' => 'title 2',
                'default' => 'default 2',
                'validation' => 'required|min:1',
                'access' => ['user'],
            ],
            [
                'code' => 'code-3',
                'type' => 'number',
                'name' => 'name 3',
                'title' => 'title 3',
                'default' => 1,
                'validation' => 'numeric',
                'access' => ['admin'],
            ],
            [
                'code' => 'code-4',
                'type' => 'boolean',
                'name' => 'name 4',
                'title' => 'title 4',
                'value' => true,
                'access' => ['admin'],
            ],
            [
                'code' => 'code-5',
                'type' => 'select',
                'name' => 'name 5',
                'title' => 'title 5',
                'options' => [
                    [
                        'title' => 'option 1',
                        'value' => 1
                    ],
                    [
                        'title' => 'option 2',
                        'value' => 2
                    ]
                ],
                'access' => ['admin'],
            ],
        ]
    ],
];
