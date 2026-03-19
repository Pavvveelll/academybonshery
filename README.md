# PetsGroomer Modern Rewrite

Workspace для миграции legacy сайта на современный стек.

## Стек

- Next.js (новый сайт): apps/web-next
- React + Vite (предыдущий прототип): apps/frontend
- Node.js + Express + Prisma API: apps/backend
- PostgreSQL: docker-compose

## Запуск

1. Установка зависимостей:
   npm install
2. Запуск API:
   npm run dev:backend
3. Запуск Next.js сайта:
   npm run dev:web

## Публичная ссылка (шаринг проекта)

Самый простой и стабильный способ: Vercel.

1. Логин:
   npx vercel login
2. Деплой:
   npm run deploy:vercel

После деплоя Vercel выведет публичную ссылку вида:
https://project-name-xxxx.vercel.app

Эту ссылку можно отправлять любому человеку, сайт откроется у него сразу.

## Деплой на обычный хостинг по FTP из терминала

1. Собрать статический билд Next.js:
   npm run build:web
2. Скопировать переменные:
   copy .env.deploy.example .env.deploy
3. Заполнить FTP данные в .env.deploy
4. Загрузить на хостинг:
   npm run deploy:ftp

По умолчанию отправляется содержимое папки apps/web-next/out.

## Миграция legacy

1. Сначала переносим страницы и формы в apps/web-next.
2. Затем переносим бизнес-логику в apps/backend.
3. Постепенно переключаем домен с legacy на Next.js.
