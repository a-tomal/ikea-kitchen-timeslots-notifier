<?php
require_once "IkeaKitchen.php";
require_once "SimpleCurl.php";
require_once "SimpleTelegramBot.php";

$ikeaKitchen = new IkeaKitchen(1, 6);
$timeslots = array_map(fn($timeslot) => $timeslot['formatted_date'], $ikeaKitchen->getAvailableTimeslots()) ?? [];

// Telegram bot notify target
//array_unshift($timeslots, "Ikea kitchen planner | Timeslots");
//$message = $timeslots ? implode("\n\n", $timeslots) : 'Nothing found';
//$telegram = new SimpleTelegramBot(botId, secretKey);
//$telegram->sendMessage(chatId, $message);