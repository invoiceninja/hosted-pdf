<?php

namespace App\Analytics;

class PdfCreated
{
    /**
     * The type of Sample.
     *
     * Monotonically incrementing counter
     *
     * 	- counter
     *
     * @var string
     */
    public $type = 'mixed_metric';

    /**
     * The name of the counter.
     * @var string
     */
    public $name = 'pdf.created';

    /**
     * The datetime of the counter measurement.
     *
     * date("Y-m-d H:i:s")
     *
     * @var DateTime
     */
    public $datetime;

    /**
     * The license
     *
     * @var string
     */
    public $string_metric5 = '';

    /**
     * The counter
     * set to 0.
     *
     * @var int
     */
    public $int_metric1 = 0;
}
