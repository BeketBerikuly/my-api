<?php

use App\Http\Action;

/** @var \Framework\Http\Application $app */

$app->get('showCountryName', '/phone/country/{phone_number}', Action\ShowAction::class);
$app->get('showFeedback', '/phone/feedback/{phone_number}', Action\ShowFeedbackAction::class);
$app->get('showPhone', '/phone/phone/{number}', Action\ShowPhonesAction::class);
$app->post('addFeedback', '/phone/feedback/{phone_number}', Action\AddFeedbackAction::class);