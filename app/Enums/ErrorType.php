<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * ErrorType enum.
 */
class ErrorType extends BaseEnum
{

    const STATUS_200 = 200;

    const STATUS_500 = 500;

    const CODE_4000 = '4000';
    const STATUS_4000 = 400;

    // Authentication failed
    const CODE_4010 = '4010';
    const STATUS_4010 = 401;

    // No account
    const CODE_4040 = '4040';
    const STATUS_4040 = 404;

    // No data
    const CODE_4041 = '4041';
    const STATUS_4041 = 404;

    // Invalid HTTP method
    const CODE_4050 = '4050';
    const STATUS_4050 = 405;

    // Validation error
    const CODE_4220 = '4220';
    const STATUS_4220 = 422;

    const CODE_5000 = '5000';
    const STATUS_5000 = 500;

    // Unexpected error
    const CODE_5001 = '5001';
    const STATUS_5001 = 500;

    // DB error
    const CODE_5002 = '5002';
    const STATUS_5002 = 500;

    const CODE_5006 = '5006';
    const STATUS_5006 = 500;
}
