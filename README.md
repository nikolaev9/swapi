# swapi

Сайт https://dev-nikolaev.pantheonsite.io/  
Логин: admin  
Пароль: 123123  
Страница с формой https://dev-nikolaev.pantheonsite.io/myform  
Странциа с таблицей https://dev-nikolaev.pantheonsite.io/swapi-people

Очередь реализовал не по элементам API, 
а по страницам, потому что основное время тратится на
получение данных, а не на добавление.
Работает следующим образом:
При первом запуске крона (то есть если очередь пуста) загружаются 
первые страницы всех сущностей 
(People, Starships, ...), сразу добавляются элементы с этих страниц,
также каждая загруженная страница проверяется 
на существование следующей, если она есть, то 
добавляется в очередь.

При последующих запусках крона (если очередь есть) загружаются 
страницы из очереди, и по тому же принципу что и выше добавляются
элементы, а также доблавяется в очередь следующие страницы, 
если есть. Стоит ограничение 6 страниц за 1 шаг.

Как только страницы в очереди закончатся, процесс начинается заново
с первых страниц. Проверку на дублирование сделал, будут добавляться
только новые элементы.

Получается примерно так:  
### Первый запуск крона ###
1. https://swapi.dev/api/people/
2. https://swapi.dev/api/films/
3. https://swapi.dev/api/starships/
4. https://swapi.dev/api/vehicles/
5. https://swapi.dev/api/species/
6. https://swapi.dev/api/planets/

### Второй запуск крона ###
1. https://swapi.dev/api/people/?page=2
2. https://swapi.dev/api/starships/?page=2
3. https://swapi.dev/api/vehicles/?page=2
4. https://swapi.dev/api/species/?page=2
5. https://swapi.dev/api/planets/?page=2
6. https://swapi.dev/api/people/?page=3

### Третий запуск крона ###
1. https://swapi.dev/api/starships/?page=3
2. https://swapi.dev/api/vehicles/?page=3
3. https://swapi.dev/api/species/?page=3
4. https://swapi.dev/api/planets/?page=3
5. https://swapi.dev/api/people/?page=4
6. https://swapi.dev/api/starships/?page=4

и т.д. пока не кончатся страницы

---

Сайт делал локально, поставил его через lando. Не разобрался
как выгружать изменения на пантеон, выдавалось сообщение при 
синхронизации конфигов что
сайты не являются копиями, ну это логично, т.к. я выполнял две
установки - локально и на пантеоне.
Ну в итоге через гит перенес свой модуль туда, но пришлось заново создавать
все поля и типы материалов.