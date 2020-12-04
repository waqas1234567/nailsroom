<?php

/**
 *   Copyright 2018 Vimeo.
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */
declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Vimeo Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'main' => [
            'client_id' => env('VIMEO_CLIENT', 'b7b6773e4acee0f2299838c8f2e39efdb96e2ca1'),
            'client_secret' => env('VIMEO_SECRET', '86uO8yutCvt9rsR3RU/yIv5ybi8bRCu9wkRnMZbdKz0fWYzgqBaYqiyNH/nKmdkDn5kA5tXK+Et8y3l5/trDICm5foF7+9x/V9LtpabVVFyuP0yTuRkA/4vO+iWNuXN5'),
            'access_token' => env('VIMEO_ACCESS', 'b5e59c612d8134a55f9938c14fdd63f1'),
        ],

        'alternative' => [
            'client_id' => env('VIMEO_CLIENT', '072292e03d877569d16233945e3ec024d6334daa'),
            'client_secret' => env('VIMEO_SECRET', 'GaFICrViFb710qENSKKNZVujQt3WDjssMlonO3azUdk7TD3ROMLVxWREr0+igicMmqDRwbiVGOeS1H1RTXP0hWihqzz353TP9vYarlsI2uf3QNnRByIIkxRpoRHNAnEV'),
            'access_token' => env('VIMEO_ACCESS', '6d7d2f34cd1a4138a1cce127f159b0f3'),
        ],

    ],

];
