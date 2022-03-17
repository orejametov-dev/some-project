1. не возращать response json в use case - **done**
2. Возвращать везде api resource в контроллерах
3. Переписать SortableByQueryParams
4. Почистить лишние команды и вынести логику в UseCase - **done**
5. Написать собственные правила request и response формата
6. Переписать merchant user access - **done**
7. вынести оставшиеся контроллеры в use cases
8. почистить ненужные services, controllers, exceptions, models, traits, migrations, routes
9. проставить во всех классах declare(strict_types=1) - **done**
10. проставить типы принимаемых и возвращаемых переменных
11. начать писать кастомные эксепшены и переделать логику в Handler.php
12. переделать статусы в enum и проставить тип в моделях enum, вынести из модели $statuses с биндингом id к имени
13. Пересмотреть simple state machine
14. проставить правильные нейминги
15. в eloquent отказаться от fillable, hidden и getters в пользу api resources и use cases
16. отказаться от кастомного user в контроллерах и роутах
17. контракт между бек ту бек запросам
18. почистить миграции, настроить правильно
