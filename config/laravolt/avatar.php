<?php
/*
 * Set specific configuration variables here
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    | Avatar use Intervention Image library to process image.
    | Meanwhile, Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */
    'driver'    => 'gd',

    // Initial generator class
    'generator' => \Laravolt\Avatar\Generator\DefaultGenerator::class,

    // Whether all characters supplied must be replaced with their closest ASCII counterparts
    'ascii'    => false,

    // Image shape: circle or square
    'shape' => 'circle',

    // Image width, in pixel
    'width'    => 221,

    // Image height, in pixel
    'height'   => 221,

    // Number of characters used as initials. If name consists of single word, the first N character will be used
    'chars'    => 2,

    // font size
    'fontSize' => 90,

    // convert initial letter in uppercase
    'uppercase' => true,

    // Fonts used to render text.
    // If contains more than one fonts, randomly selected based on name supplied
    'fonts'    => [__DIR__.'/../fonts/OpenSans-Bold.ttf', __DIR__.'/../fonts/rockwell.ttf'],

    // List of foreground colors to be used, randomly selected based on name supplied
    'foregrounds'   => [
        '#FFFFFF',
    ],

    // List of background colors to be used, randomly selected based on name supplied
    'backgrounds'   => [
        '#88db55', '#1aa33c', '#db6a67', '#5cd695', '#69ef6b', '#086aa3', '#80c1dd', '#3bb20c', '#fcc7d3', '#6fd845', '#4f6eb5', '#edf7a0', '#4f0593', '#b0e226', '#f9a395', '#80e585', '#411082', '#ad1d41', '#1f7d7f', '#36db68', '#ff8c7c', '#6268c4', '#62a4d1', '#02526d', '#d653cf', '#9effeb', '#f8b8f9', '#840507', '#db81d9', '#ced85d', '#c6158e', '#4276c9', '#416dfc', '#db3f66', '#ea3c82', '#b539ce', '#792eea', '#c65633', '#7992ea', '#7529c6', '#aefcc6', '#a2f788', '#f2bc9b', '#8f50d3', '#edc76f', '#1204db', '#149b45', '#d81c7a', '#e0505f', '#02e560', '#f24d60', '#e02c89', '#a50dd8', '#cc6539', '#6e54d3', '#d86569', '#bcf48b', '#f4cb35', '#77dd7b', '#467c04', '#1ad85f', '#dc71f7', '#ea83b5', '#470e9e', '#3b93bf', '#f7cdbb', '#1cba10', '#ffbad4', '#5201a3', '#f2d1a9', '#e896a8', '#66ddcb', '#7d66cc', '#ffccaa', '#e0d906', '#31b6ea', '#598409', '#fcd4b0', '#98ed49', '#f2a693', '#70aeff', '#e1b8fc', '#6c2bdb', '#81ea75', '#e0267d', '#574ed3', '#04d690', '#535cdb', '#91a0d8', '#0b2493', '#86ef2f', '#e579c5', '#fffb93', '#db6462', '#fce3ba', '#96a3e8', '#931021', '#ffef16', '#09c137', '#72d155', '#9acfe0', '#48cea4', '#0d6689', '#55bfed', '#4ea7ad', '#09156b', '#dda87a', '#c3ef88', '#ef8fef', '#8722f9', '#f72cc1', '#c63129', '#e0bf6b', '#ce5f2b', '#b20c22', '#d80439', '#67e5da', '#44f41d', '#fc902a', '#0b7c3a', '#67fc16', '#1306cc', '#caea3c', '#ce0889', '#098c9b', '#f2ed60', '#e5e83a', '#f4ae89', '#bdef99', '#ed80ed', '#ed8492', '#d7c7fc', '#a1f4c8', '#f9742c', '#a0f7b6', '#3cb7b7', '#a0f7ee', '#026b07', '#1e14ad', '#f7d2ad', '#ad0f22', '#e83cdf', '#43cad3', '#ecef92', '#fc02e3', '#9e79ce', '#1d5d7c', '#78d65e', '#6bdbb6', '#5aa4c9', '#2aff05', '#efbd07', '#ddbef7', '#e57ea0', '#c5f4f9', '#e87195', '#c9d3ff', '#01b726', '#f45da6', '#b5d6fc', '#7eeaa4', '#e0cd53', '#26c906', '#809cf7', '#f9a7d4', '#0d5087', '#daf49c', '#6cbefc', '#a2f9b3', '#5963cc', '#e899b3', '#79e2f2', '#099e62', '#8fef5b', '#f7c5ad', '#373b9b', '#e59ff4', '#0bbadd', '#f26a75', '#8478db', '#98e579', '#b77333', '#b7b8f7', '#070c68', '#e9b0f4', '#cf68d8', '#bef0f7', '#aa3c20', '#befca1', '#e5c97e', '#b55e39', '#ddc5f9', '#eaa48c', '#c9efff', '#f7c8b7', '#fcc4ef', '#d615bc', '#efe188', '#6db71f', '#57f2f2', '#f4e96e', '#38edcf', '#3a11f4', '#76bbfc', '#efbd58', '#f9c2e7', '#02cc19', '#7c4df2', '#f27548', '#afffbe', '#c6ccff', '#ffff32', '#58e02a', '#0abdd8', '#f704f7', '#ccfaff', '#d18e29', '#451b89', '#fce771', '#9cbde2', '#eda3a7', '#0f8e8a', '#ed768a', '#f49b7a', '#022e75', '#fcb5fc', '#f73e3b', '#87eda7', '#c97a42', '#1f01a3', '#f285e7', '#e495f9', '#e0007f', '#b5e057', '#f29dc6', '#b21717', '#e8d52e', '#d8ed93', '#be57db', '#d6379e', '#f291e2', '#76e2c0', '#cbfc3a', '#44c9b5', '#ed7d0e', '#62d300', '#f7e813', '#37d129', '#11a030', '#fc9fca', '#e07957', '#f7889e', '#46e53d', '#8029c6', '#63bfbd', '#e87fbe', '#82b719', '#a3b2f7', '#e2a581', '#88fc90', '#e89e94', '#05718c', '#9dfc85', '#f0c0f9', '#e217ed', '#8f9fef', '#1de2e5', '#0f43dd', '#52c134', '#1ff2a1', '#ed9ae4', '#abfcc5', '#f97c81', '#5d11d8', '#a3ed04', '#d4c7fc', '#dc2dff', '#327893', '#c691f2', '#ff7a9b', '#f75151', '#abfcca', '#bf15ea', '#fcd694', '#c4ed87', '#83efed', '#213896', '#c1eeff', '#acbc2f', '#e195ed', '#49e5ce', '#e2703f', '#a378db', '#f7c2b9', '#edcda3', '#c5ffb2', '#42c47a', '#06e806', '#2d0bc1', '#a0afe5'
    ],

    'border'    => [
        'size'  => 0,

        // border color, available value are:
        // 'foreground' (same as foreground color)
        // 'background' (same as background color)
        // or any valid hex ('#aabbcc')
        'color' => 'transparent',
    ],
];
