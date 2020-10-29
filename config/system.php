<?php

return [
    'fields' => [
        [
            'code' => 'code-1',
            'type' => 'text',
            'name' => 'name 1',
            'title' => 'title 1'
        ],
        [
            'code' => 'code-2',
            'type' => 'text',
            'name' => 'name 2',
            'title' => 'title 2',
            'default' => 'default 2',
            'validation' => 'required|min:1'
        ],
        [
            'code' => 'code-3',
            'type' => 'number',
            'name' => 'name 3',
            'title' => 'title 3',
            'validation' => 'required|numeric'
        ],
        [
            'code' => 'code-4',
            'type' => 'boolean',
            'name' => 'name 4',
            'title' => 'title 4',
            'value' => true
        ],
        [
            'code' => 'code-5',
            'type' => 'selected',
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
            ]
        ],

    ],
    'roles' => []
];
