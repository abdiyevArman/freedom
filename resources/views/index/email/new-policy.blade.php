<html>
<body>
<p>Здравствуйте, {{$row['name']}}!</p>

<p>Вы успешно оформили страховой полис по обязательному автострахованию ОГПО. Он начнёт действовать <b>{{$row['start_date']}}</b>.</p>

<p>Поздравляем, теперь ваша ответственность застрахована в компании Freedom Finance Insurance!</p>

<p>Электронный полис прикреплён к этому письму. Вы также можете скачать его в личном кабинете на нашем сайте <a href="https://ffins.kz">ffins.kz</a>.</p>

@if($row['is_new_user'] == 1)
    <p><b>Логин:</b> {{$row['phone']}}</p>
    <p><b>Пароль:</b> {{$row['password']}}</p>
@endif

<p>Помимо этого, в личном кабинете можно узнать всё о полисах, оформленных в нашей компании: условия страхования, срок действия полиса. Там же можно написать онлайн-заявление о страховом случае.</p>

<p style="color:red">Не забудьте, каждый месяц, весь 2019 год мы разыгрываем среди клиентов автомобили и множество других ценных призов!</p>

<p>Подпишитесь на наши социальные сети, чтобы следить за новостями о розыгрышах.</p>

<p>Если у вас есть вопросы, позвоните по номеру <a href="tel:+5777">5777</a> (бесплатно с мобильного телефона) или напишите нам: <a href="mailto:info@ffins.kz">info@ffins.kz</a></p>

</body>
</html>