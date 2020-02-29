<?

function GetTic($domen){
  $xmlstr = file_get_contents("http://bar-navig.yandex.ru/u?show=31&url=http://".$domen); // Скачиваю код страницы
  $xml = simplexml_load_string($xmlstr);
  return $xml->tcy['value'];
}

include('vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$telegram = new Api(' 488064656:AAE_iCaUvTxZKmSaA9uw1NZEOHQOsDP76wI'); //Устанавливаем токен, полученный у BotFather
$result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя

$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя


if($text){
  $text = mb_strtolower($text,'utf-8');
    if ($text == "/start") {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Добро пожаловать! Данный бот сообщит Вам значение тематического индекса цитирования (тИЦ) сайта по запросу. Для получения значения сообщите боту доменное имя." ]);
    }
    elseif ($text == "/help") {
      $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Чтобы узнать значение тИЦ, сообщите боту домменное имя. Бот разработан студентом РЭУ им. Г.В. Плеханова!" ]);
    }
    else
    {
      if (preg_match('/^([0-9a-z]([0-9a-z\-])*[0-9a-z]\.)+[0-9a-z\-]{1,8}$/i', $text)) {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "".GetTic($text)."" ]);
      }
      elseif (preg_match('/^((?=[\p{Ll}\p{Nd}-]{1,63}\.)[\p{Ll}\p{Nd}]+(-[\p{Ll}\p{Nd}]+)*\.)+[\p{Ll}]{2,63}$/uA', $text)) {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "".GetTic($text)."" ]);
      }
      else {
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте мне доменное имя для получения значения тИЦ."."" ]);
      }
    }
}
else {
  $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте мне доменное имя текстовым сообщением." ]);
}
