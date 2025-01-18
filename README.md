# CVS-Tools for Cotonti. #
## Import and export data from a CVS file into DB MySQL. ##

<p>Как выполнить экспорт товаров или статей в Cotonti CMS и после выполнить импорт из CVS или Excel файла в базу данных MySQL вашего сайта.</p>

<p>Базовый набор инструментов для экспорта и импорта данных в таблицу модуля &quot;Pages&quot;.</p>

<h2>Принцип работы и возможности библиотеки &quot;CVS-Tools для Cotonti&quot;:</h2>

<h3>1. Экспорт статей из базы данных в CVS файл.</h3>

<p>Экспорт полей из таблицы БД в файл на 1400 строк занимает 1 секунду.</p>

<p>При экспорте выгружаются все поля, включая экстраполя или поля созданные другими расшерениями вашего сайта на котонти.</p>

<h3>2. Google spreadsheets (посредник)</h3>

<p>Полученный файл импортируем в гугл-таблицы, правим, дополняем, затем скачиваем в формате cvs, а затем уже через &quot;<strong><a href="https://github.com/webitproff/cot_page_cvstools">CVS-Tools для Cotonti</a></strong>&quot; импортируем в БД вашего сайта.</p>

<h3>3. Импорт статей в базу данных из CVS файла.</h3>

<p>На данный момент, инструмент импорта&nbsp; работает с первыми16-тью полями таблицы модуля &quot;Pages&quot;, которые создаются модулем при его установке,&nbsp; - page_id, page_alias, page_state, page_cat, page_title, page_text, и так далее, <a href="https://github.com/Cotonti/Cotonti/blob/39d630e32cec474588cd60df273083bc34efa348/modules/page/setup/page.install.sql#L34"><strong>все которые можно посмотреть здесь</strong></a>, но без экстраполей.</p>

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

<h2>Рабочая среда и список необходимых файлов в папке &quot;cvstools&quot;:</h2>

<h4>dbconfig.php</h4>

<p>Файл конфигурации подключения к базе данных.</p>

<h4>index.php</h4>

<p>Файл вхождения, где получаем доступ к инструментам импорта и экспорта данных строк таблицы со статьями.</p>

<h4>fileslist.txt</h4>

<p>Сюда записываем имена файлов CVS, которыt загружали в форму импорта/обновления БД.</p>

<h4>exportcsvfile.php</h4>

<p>Файл со сценарием построчного экспорта в CVS-файл.</p>

<h4>updateimportcsvfile.php</h4>

<p>Файл, который сочетает в своем сценарии сразу два инструмента, - это обновление строки в базе если она есть и добавление, если такой строки нет.</p>

<h4>importcsvfile.php</h4>

<p>это файл только для импорта.</p>

<p>Остальные файлы не используются. пока хранятся для сравнения и как вариант.</p>

<p>Инструменты хоть и заточены под поля таблицы базы данных модуля статей, но не являются модулем или плагином движка котонти, и поэтому устанавливаются как самостоятельная библиотека.</p>

<p>&nbsp;</p>

<h2>Порядок установки &quot;CVS-Tools для Cotonti&quot;:</h2>

<h3>1. Скачиваем с репозитория по ссылке ниже.</h3>

<h3>2. В скачаном архиве находится папка &quot;cvstools&quot;.</h3>

<p>Её закачиваем в корень вашего сайта.</p>

<h3>3. Подключаем базу.</h3>

<p>Открываем public_html/cvstools/dbconfig.php</p>

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


<p>1. Напрямую скрипт импортирует только CVS-файлы.</p>

<p>2. Электронные таблицы, например как Excel от Microsoft Office или таблицы от LibreOffice &quot;прогоняем&quot; через <a href="https://docs.google.com/spreadsheets/u/0/">Google Таблицы. </a><br />
То есть просто жмем &quot;Новая таблица&quot;, &quot;Файл&quot; =&gt; &quot;Импортировать&quot;.<br />
Выбираем свой документ электронной таблицы будь-то .xlsx, .ods, .tsv или .cvs и импортируем.</p>

<p>3. Импортированный файл приводим в нужный вид (Пункт №3 в параграфе &quot;Принцип работы и возможности библиотеки CVS-Tools для Cotonti&quot; или просто смотрим свой экспортированный файл при помощи этого скрипта).</p>

<p>4. Скачиваем отредактированный файл как .cvs.</p>

<p>5. Всё, если бекап БД сделали - смело импортируем.</p>

<p>&nbsp;</p>

<p><b>Как раз то, что любую электронную таблицу в самых распространных расширениях как .xlsx, .ods, .tsv или .cvs мы можем прогнать и преобразовать через Google Таблицы в нужный нам формат cvs-файл для импорта - делает скрипт действительно универсальным и не требует никаких дополнительных библиотек.</b></p>


<p><b><a href="https://abuyfile.com/ru/forums/cotonti/custom/topic101">Форум по обсуждению и поддержке инструментов для экспорта и импорта статей из CVS-файлов.</a></b></p>
<hr>

# ENGLISH #

<p>CVS-Tools for Cotonti. Import and export from a CVS or Excel file</p>

<p>How to export products or articles to Cotonti CMS and then import from a CVS or Excel file into the MySQL database of your site.</p>

<p>How to export products or articles to Cotonti CMS and then import from a CVS or Excel file into the MySQL database of your site.</p>

<p>A basic set of tools for exporting and importing data to the Pages module table.</p>

<p>The principle of operation and capabilities of the &quot;CVS-Tools for Cotonti&quot; library:</p>

<p>1. Export articles from the database to a CVS file.</p>

<p>Exporting fields from a database table to a 1400-line file takes 1 second.</p>

<p>When exporting, all fields are unloaded, including extra fields or fields created by other extensions of your site on cotonti.</p>

<p>2. Google spreadsheets (intermediary)</p>

<p>The resulting file is imported into Google tables, edited, supplemented, then downloaded in cvs format, and then imported into your site&#39;s database via CVS-Tools for Cotonti.</p>

<p>3. Import articles into the database from a CVS file.</p>

<p>At the moment, the import tool works with the first 16 fields of the Pages module table, which are created by the module during installation, page_id, page_alias, page_state, page_cat, page_title, page_text, and so on, all of which can be viewed here, but without extrapolations.</p>

<p>The entire list of fields:</p>

<p><br />
page_id<br />
page_alias<br />
page_state<br />
page_cat<br />
page_title<br />
page_desc<br />
page_keywords<br />
page_metatitle<br />
page_metadesc<br />
page_text<br />
page_parser<br />
page_author<br />
page_ownerid<br />
page_date<br />
page_begin<br />
page_expire<br />
page_updated</p>

<p>Other fields, if desired, can already be added independently.</p>

<p>&nbsp;<br />
The working environment and the list of necessary files in the folder &quot;cvstools&quot;:<br />
dbconfig.php</p>

<p>The configuration file for the database connection.</p>

<p>index.php</p>

<p>The entry file, where we get access to tools for importing and exporting data from rows in a table with articles.</p>

<p>fileslist.txt</p>

<p>Here we write down the names of the CVS files that were uploaded to the database import/update form.</p>

<p>exportcsvfile.php</p>

<p>A file with a line-by-line export script to a CVS file.</p>

<p>updateimportcsvfile.php</p>

<p>A file that combines two tools at once in its script is updating a row in the database if there is one and adding it if there is no such row.</p>

<p>importcsvfile.php</p>

<p>this is an import-only file.</p>

<p><br />
The remaining files are not used. for now, they are stored for comparison and as an option.</p>

<p>Although the tools are tailored to the fields of the article module database table, they are not a module or plug-in of the Cotonti engine, and therefore are installed as an independent library.</p>

<p>&nbsp;<br />
The installation procedure for &quot;CVS-Tools for Cotonti&quot;:</p>

<p><br />
1. Download from the repository using the link below.<br />
2. The downloaded archive contains the folder &quot;cvstools&quot;.</p>

<p>We upload it to the root of your site.</p>

<p><br />
3. Connect the database.</p>

<p>Opening it public_html/dbcvstools/dbconfig.php<br />
&nbsp;&nbsp; &nbsp;<br />
// DB configuration and connection<br />
const DB_HOST = &quot;localhost&quot;;<br />
const DB_USERNAME = &quot;user&quot;;<br />
const DB_PASSWORD = &quot;password&quot;;<br />
const DB_NAME = &quot;database name&quot;;</p>

<p>we register our correct access data.</p>

<p>4. Interface and launch</p>

<p>We write it in the address bar</p>

<p>&nbsp;&nbsp; &nbsp;<br />
https://mydomain.com/cvstools/index.php</p>

<p>where, &quot;mydomain.com &quot; of course your domain.</p>

<p>&nbsp;<br />
Attention!. Always backup the database before any import.</p>

<p>&nbsp;</p>

<p><a href="https://abuyfile.com/en/forums/cotonti/custom/topic101"><strong>A forum for discussing and supporting tools </strong></a>for exporting and importing articles from CVS files.</p>

<p>&nbsp;</p>

<p>The current version is always available for download on the <a href="https://github.com/webitproff/cot_page_cvstools" rel="nofollow">GitHub public repository</a>.</p>


<p>&nbsp;</p>

<p>Актуальная версия доступная для скачивания всегда на публичном репозитории <a href="https://github.com/webitproff/cot_page_cvstools/tree/main" rel="nofollow"><strong>GitHub</strong></a>.</p>

