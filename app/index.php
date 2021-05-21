<?php

namespace App;

include('/app/SassToCss/SassToCssConverter.php');

use App\SassToCss\SassToCssConverter;

$sassToCssConverter = new SassToCssConverter();
$sassToCssConverter->convert("/app/css_to_convert.scss", "/app/css_converted.css");