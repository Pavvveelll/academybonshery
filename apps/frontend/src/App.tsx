export function App() {
  return (
    <main className="min-h-screen bg-base text-ink font-body">
      <section className="mx-auto max-w-5xl px-6 py-16">
        <p className="mb-4 inline-block rounded-full bg-sea/10 px-3 py-1 text-sm text-sea">
          PetsGroomer vNext
        </p>
        <h1 className="font-display text-4xl leading-tight md:text-6xl">
          Новый современный сайт готов к поэтапной миграции
        </h1>
        <p className="mt-6 max-w-3xl text-lg text-ink/80">
          Этот frontend подключается к новому API на Node.js и позволяет постепенно
          переносить старый PHP-функционал без остановки текущего бизнеса.
        </p>
      </section>
    </main>
  );
}
