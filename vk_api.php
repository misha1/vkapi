<?php
	
	$available = 'market-201387040_4823136,market-201387040_4901642,market-201387040_4823128,market-201387040_4823113,market-201387040_4823116,market-201387040_4823119,market-201387040_4823266,market-201387040_4823120,';
	
	function getBtn($label, $color, $payload = '') {
		return [
			'action' => [
				'type' => 'text',
				"payload" => json_encode($payload, JSON_UNESCAPED_UNICODE),
				'label' => $label
			],
			'color' => $color
		];
	}

	if (!isset($_REQUEST)) {
		return;
	}

	//Строка для подтверждения адреса сервера из настроек Callback API
	$confirmation_token = '';

	//Ключ доступа сообщества
	$token = '';

	//Получаем и декодируем уведомление
	$data = json_decode(file_get_contents('php://input'));

	//Проверяем, что находится в поле "type"
	switch ($data->type) {
	//Если это уведомление для подтверждения адреса...
	case 'confirmation':
	//...отправляем строку для подтверждения
	echo $confirmation_token;
	break;

	//Если это уведомление о новом сообщении...
	case 'message_new':
	//...получаем id его автора
	$user_id = $data->object->message->from_id;
	//затем с помощью users.get получаем данные об авторе
	$user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&access_token={$token}&v=5.130"));

	//и извлекаем из ответа его имя
	$first_name = $user_info->response[0]->first_name;  //получаем имя
	$last_name = $user_info->response[0]->last_name;  //получаем фамилию
	
	$date_message = $data->object->message->date; 	//получаем дату сообщение
	$text = $data->object->message->text; //получаем текст отправителя
	$date_message_ok = date("d M Y H:i:s", $date_message); 
	$payload_message = $data->object->message->payload; // получаем ответ от кнопки
	$peer_id = $data->object->message->peer_id;
	
	$lowtext = mb_strtolower($text, 'UTF-8');
	//сохранение и создание файла с данными пользователя
	// $message = "
// Дата: $date_message_ok
// Имя: $first_name
// Фамилия: $last_name
// Профиль: https://vk.com/id$user_id
// сообщение: $text
	// ";
	
	
	// try {
		// $time_record = date("d M Y H:i:s");
		// $file_name = "log_from_vk.log";
		// $f = fopen($file_name, "a");
		// flock($f, 2);
		// fwrite($f, "записано в лог: $time_record\n$message");
		// fclose($f);
	// }
	
	// catch(Exception $e) {}
	
	
	if( $lowtext == 'привет' or $lowtext == 'здравствуйте'){
		$msg = "Здравствуй, {$first_name}! Я - бот Аня, Чем могу помочь?😊";
	}
	
	//клава
	$kbd_1 = [
		'one_time' => false,
		'buttons' => [
			[
				getBtn("Начать", 'positive', 'start')		
			],
		]
	];
	
	$kbd = $kbd_1;

	$kbd_2 = [
		'one_time' => false,
		'buttons' => [
			[
				getBtn("Прайс", 'positive', 'PRICE'),
				getBtn("Все категории", 'default', 'CATEGORY')
			],
			
			[
				getBtn("Наличие товара", 'default', 'available'),
				getBtn("Менеджер", 'default', 'HELP')
			],
			[
				getBtn("Отзыв", 'default', 'REPLY'),
				getBtn("О Нас", 'default', 'ABOUT')
			],
			[
				getBtn("Доставка", 'default', 'SHIP'),
				getBtn("Халява", 'default', 'bonus')
			],
		]
	];
	
	$kbd_3 = [
		'one_time' => false,
		'buttons' => [
			[
				getBtn("💳 Л'Этуаль", 'default', 'letual'),
				getBtn("💳 РИВ ГОШ", 'default', 'rivgosh')
			],
			[
				getBtn("💳 Лента", 'default', 'lenta'),
				getBtn("💳 Пандора", 'default', 'pandora')
			],
			
			[
				getBtn("Назад", 'default', 'start'),
			],
		]
	];
	
	

	
	$carousew = [
		'type' => 'carousel',
		'elements' => [
				[
					'title' => 'Title',
					'description' => 'Description',
					'action' => [
							'type' => 'open_link',
							'link' => 'https://vk.com'
					],
					'photo_id' => '-109837093_457242809',
					'buttons' => [[
							'action' => [
									'type' => 'text',
									'label' => 'Label'
							]
					]]
				],
				[
					'title' => 'Title',
					'description' => 'Description',
					'action' => [
							'type' => 'open_link',
							'link' => 'https://vk.com'
					],
					'photo_id' => '-109837093_457242809',
					'buttons' => [[
							'action' => [
									'type' => 'text',
									'label' => 'Label'
							]
					]]
				],
				[
					'title' => 'Title',
					'description' => 'Description',
					'action' => [
							'type' => 'open_link',
							'link' => 'https://vk.com'
					],
					'photo_id' => '-109837093_457242809',
					'buttons' => [[
							'action' => [
									'type' => 'text',
									'label' => 'Label'
							]
					]]
				],
			]
	];
	
	// $jsondata = json_encode($payload_message, JSON_UNESCAPED_UNICODE);
	// file_put_contents('t.json', $jsondata);
	switch ($payload_message) {
		
		case "\"start\"" : 
				$msg = "{$first_name}, что вы хотите узнать?😊";
				$kbd = $kbd_2;
				break;
				
		case "\"PRICE\"" : 
				$msg = "Спасибо, что обратились к нам! Мы даем гарантию цены и качества. Лучше вы нигде не найдете😊";
				$media = 'photo-201387040_457239317';
				$kbd = $kbd_2;
				break;
		
		case "\"CATEGORY\"" : 
				
				$request_params = array(
					'message' => "Спасибо, что обратились к нам! Мы даем гарантию цены и качества. Лучше вы нигде не найдете😊 Раздел разрабатывается",
					'peer_id' => $user_id,
					'access_token' => $token,
					'v' => '5.130',
					'random_id' => '0',
					'template' => json_encode($carousew, JSON_UNESCAPED_UNICODE)
				);

				$get_params = http_build_query($request_params);

				
				
				file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
				
				break;
				
		case "\"available\"" : 
				$media = $available;
				$msg = "Товары в наличии, также есть товары под заказ напишите нашему менеджеру:)";
				$kbd = $kbd_2;
				break;	
				
		case "\"HELP\"" : 
				$msg = "Скоро с вами свяжется наш менеджер, ожидайте пожалуйста😊";
				$kbd = $kbd_1;
				break;		
				
		case "\"REPLY\"" : 
				$msg = "У нас очень качественные  волосы, поэтому гарантия 100% что все довольны😊";
				$media = "photo-201387040_457239222,photo-201387040_457239221,photo-201387040_457239223,photo-201387040_457239224,photo-201387040_457239229,photo-201387040_457239228,photo-201387040_457239226";
				$kbd = $kbd_2;
				break;		
				
		case "\"ABOUT\"" : 
				$media = "photo-201387040_457239231,photo-201387040_457239232,photo-201387040_457239233";
				$msg = "Мы собираем и производим натуральный волос в срезах, неокрашенный - темный и окрашиваем фабричным методом в любые цвета. Кроме темных и окрашенных всегда в наличии седой волос, белое седое сырье.
				
Волосы исключительно натуральные, мягкие, тонкие, как славянское качество. У нас нет перевёрнутых волос, нет силикона, нет никаких махинаций с челками, резинками и весом. Есть и синглдрон и даблдрон - хвосты с плотными концами. Все честно и прозрачно. Один раз на тест вы всегда можете заказать 200-300 грамм одной длины и категории товара по оптовому прайсу.

Все прайсы всегда актуальны также всегда можете запросить любую информацию и цены в ватцапе или по email и следить за нами 

в инстаграм instagram.com/anya_onehair
в тиктоке tiktok.com/@anya_onehair

Будем рады видеть вас в числе наших постоянных и довольных клиентов ;)

Перед отправлением по вашему желанию отправляем фото вашего заказа, если вдруг вас не устраивает цвет или качество вернем деньги😊";
				$kbd = $kbd_2;
				break;		
				
		case "\"SHIP\"" : 
				$msg = "Доставка посредством курьерской службы Сдек с Москвы, срок прохождения посылок в Россию около недели. Ход посылок отслеживается по трекинг-коду. В городе Ижевск товар можно забрать только через самовывоз😊";
				$kbd = $kbd_2;
				break;		
				
		case "\"bonus\"" : 
				$msg = "💎раздел помощь для экономии при покупке💎";
				$kbd = $kbd_3;
				break;		
				
		case "\"letual\"" : 
				$msg = "💳 Л'Этуаль Карта ";
				$media = 'photo-201387040_457239318';
				$kbd = $kbd_3;
				break;		
				
		case "\"rivgosh\"" : 
				$msg = "💳 РИВ ГОШ Карта ";
				$media = 'photo-201387040_457239319';
				$kbd = $kbd_3;
				break;	
				
		case "\"lenta\"" : 
				$msg = "💳 Лента Карта ";
				$media = 'photo-201387040_457239320';
				$kbd = $kbd_3;
				break;	
				
		case "\"pandora\"" : 
				$msg = "💳 PANDORA Карта ";
				$media = 'photo-201387040_457239321';
				$kbd = $kbd_3;
				break;		
		
				
				
				
	}
	
	
	
	//С помощью messages.send отправляем ответное сообщение
	$request_params = array(
		'attachment' => $media,
		'message' => $msg,
		'peer_id' => $user_id,
		'access_token' => $token,
		'v' => '5.130',
		'random_id' => '0',
		'dont_parse_links' => '1',
		'keyboard' => json_encode($kbd, JSON_UNESCAPED_UNICODE)
	);

	$get_params = http_build_query($request_params);

	
	
	file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);

	
	//Возвращаем "ok" серверу Callback API

	echo('ok');

	break;

}
?>