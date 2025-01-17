<p>Как выполнить экспорт товаров или статей в Cotonti CMS и после выполнить импорт из CVS или Excel файла в базу данных MySQL вашего сайта.</p>

<p>Базовый набор инструментов для экспорта и импорта данных в таблицу модуля &quot;Pages&quot;.</p>

<h2>Принцип работы и возможности библиотеки &quot;CVS-Tools для Cotonti&quot;:</h2>

<h3>1. Экспорт статей из базы данных в CVS файл.</h3>

<p>Экспорт полей из таблицы БД в файл на 1400 строк занимает 1 секунду.</p>

<p>При экспорте выгружаются все поля, включая экстраполя или поля созданные другими расшерениями вашего сайта на котонти.</p>

<h3>2. Google spreadsheets (посредник)</h3>

<p>Полученный файл импортируем в гугл-таблицы, правим, дополняем, затем скачиваем в формате cvs, а затем уже через &quot;<strong><a href="https://github.com/webitproff/cot_page_cvstools" rel="nofollow">CVS-Tools для Cotonti</a></strong>&quot; импортируем в БД вашего сайта.</p>

<h3>3. Импорт статей в базу данных из CVS файла.</h3>

<p>На данный момент, инструмент импорта&nbsp; работает с первыми16-тью полями таблицы модуля &quot;Pages&quot;, которые создаются модулем при его установке,&nbsp; - page_id, page_alias, page_state, page_cat, page_title, page_text, и так далее, <a href="https://github.com/Cotonti/Cotonti/blob/39d630e32cec474588cd60df273083bc34efa348/modules/page/setup/page.install.sql#L34" rel="nofollow"><strong>все которые можно посмотреть здесь</strong></a>, но без экстраполей.</p>

<p>Весь список полей:</p>

<pre class="brush:as3;">
page_id
page_alias
page_state
page_cat
page_title
page_desc
page_keywords
page_metatitle
page_metadesc
page_text
page_parser
page_author
page_ownerid
page_date
page_begin
page_expire
page_updated</pre>

<p>Другие поля, по желанию, уже можно дописать самостоятельно.</p>

<p>&nbsp;</p>

<h2>Рабочая среда и список необходимых файлов в папке &quot;cvstools&quot;:</h2>

<h4>dbconfig.php</h4>

<p>Файл конфигурации подключения к базе данных.</p>

<h4><br />
index.php</h4>

<p>Файл вхождения, где получаем доступ к инструментам импорта и экспорта данных строк таблицы со статьями.</p>

<h4><br />
fileslist.txt</h4>

<p>Сюда записываем имена файлов CVS, которыt загружали в форму импорта/обновления БД.</p>

<h4><br />
exportcsvfile.php</h4>

<p>Файл со сценарием построчного экспорта в CVS-файл.</p>

<h4><br />
updateimportcsvfile.php</h4>

<p>Файл, который сочетает в своем сценарии сразу два инструмента, - это обновление строки в базе если она есть и добавление, если такой строки нет.</p>

<h4><br />
importcsvfile.php</h4>

<p>это файл только для импорта.</p>

<p><br />
Остальные файлы не используются. пока хранятся для сравнения и как вариант.</p>

<p>Инструменты хоть и заточены под поля таблицы базы данных модуля статей, но не являются модулем или плагином движка котонти, и поэтому устанавливаются как самостоятельная библиотека.</p>

<p>&nbsp;</p>

<h2>Порядок установки &quot;CVS-Tools для Cotonti&quot;:</h2>

<h3>1. Скачиваем с репозитория по ссылке ниже.</h3>

<h3>2. В скачаном архиве находится папка &quot;cvstools&quot;.</h3>

<p>Её закачиваем в корень вашего сайта.</p>

<h3>3. Подключаем базу.</h3>

<p>Открываем public_html/dbcvstools/dbconfig.php</p>

<pre class="brush:php;">
    // БД конфигурация и поключение  
    const DB_HOST = &quot;localhost&quot;;
    const DB_USERNAME = &quot;пользователь&quot;;
    const DB_PASSWORD = &quot;пароль&quot;;
    const DB_NAME = &quot;имя базы данных&quot;;</pre>

<p>прописываем свои корректные данные доступа.</p>

<h3>4. Интерфейс и запуск</h3>

<p>Прописываем в адресной строке</p>

<pre class="brush:as3;">
https://mydomain.com/cvstools/index.php</pre>

<p>где, &quot;mydomain.com&quot; разумеется свой домен.</p>

<p>&nbsp;</p>

<h4><span style="color:#c0392b;">Внимание!. Перед любым импортом всегда делать бекап БД.</span></h4>

<p>&nbsp;</p>

<p><a href="https://abuyfile.com/ru/forums/cotonti/custom/topic101">Форум по обсуждению и поддержке инструментов для экспорта и импорта статей из CVS-файлов.</a></p>

<p>&nbsp;</p>

<p>Актуальная версия доступная для скачивания всегда на публичном репозитории <a href="https://github.com/webitproff/cot_page_cvstools/tree/main" rel="nofollow"><strong>GitHub</strong></a>.</p>
