# yii2-cron

Скриптовый крон (шедулер) для Yii2.

## Requirements
- PHP ^8.2
- yiisoft/yii2 ~2.0.15
- symfony/process ^7.4
- dragonmantank/cron-expression ~1.2

## Install
composer require alikdex/yii2-cron:^2.0

## Security

Команда из поля `command` исполняется через shell ОС (`sh -c` на Unix, `cmd.exe`
на Windows) с привилегиями процесса PHP. Интерполяция shell (пайпы, `&&`, `$VAR`)
доступна по дизайну. Право записи в `cron_tasks` и/или доступ к CRUD контроллеру
модуля эквивалентны выполнению произвольного кода на сервере — предоставляйте их
только доверенным администраторам.

## История версий
См. CHANGELOG.md
