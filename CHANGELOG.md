# Changelog

## 2.0.0 — 2026-09-29

- FIXED: TypeError при запуске задач с symfony/process >= 5.0 (конструктор Process
  принимает только array). `new Process($command)` → `Process::fromShellCommandline($command)`
  в TaskExecutor. Shell-семантика (sh -c) и таймаут 3600 сохранены.
- CHANGED: требования — `php: ^8.2`, `symfony/process: ^7.4` (было: php не задан,
  symfony/process `*`). Причина мажора: подъём floor зависимостей.

## 1.0.4 — 2020-05-31

- Последний релиз ветки 1.x.
