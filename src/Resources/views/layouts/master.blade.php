<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
            }

            * {
                box-sizing: border-box;
            }
            .container {
                background-color: #f2f2f2;
                padding: 20px;
                width: 70%;
                margin: auto;
                margin-top: 60px;
            }
            .error, .required {
                color: red;
            }
            .input-text {
                width: 100%;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                margin-top: 6px;
                margin-bottom: 16px;
                resize: vertical;
            }
            .select {
                width: 100%;
                padding: 12px;
                margin-top: 6px;
                margin-bottom: 16px;
                resize: vertical;
            }
            .input-boolean {
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                margin-top: 6px;
                margin-bottom: 16px;
                resize: vertical;
            }
            .label {
                float:left;
                margin-right:20px;
                line-height: 5px" 
            }
            .input-submit{
                background-color: #e85959;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            input[type=submit]:hover {
                background-color: #af3838;
            }
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }
            td {
                background-color: #fff;
            }
            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            .row {
                margin-bottom: 18px;
            }

            /* Toggle switch */
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }

            /* Hide default HTML checkbox */
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            /* The slider */
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: #2196F3;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }

            /* Rounded sliders */
            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }
            .set-default {
                margin-bottom: 10px;
            }
        </style>
    </head>

    <body class="antialiased">

        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            @yield('content-wrapper')
        </div>

    </body>
</html>
