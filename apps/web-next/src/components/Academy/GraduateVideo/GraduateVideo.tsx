import styles from "./GraduateVideo.module.css";

const testimonials = [
  {
    name: "Елена",
    background: "Банковский специалист",
    text: "Я проработала в банке 8 лет. На курсе Академии Боншери поняла, что нашла своё дело — сейчас у меня свой кабинет, постоянные клиенты и я полностью перешла в груминг.",
    course: "Курс Классика",
  },
];

export function GraduateVideo() {
  return (
    <section id="graduates" className={styles.section}>
      <div className={styles.container}>
        <h2 className={styles.title}>Наши выпускники</h2>
        <p className={styles.subtitle}>
          Большинство студентов приходят без опыта. Многие меняют профессию и открывают
          собственный кабинет уже в первые месяцы после обучения.
        </p>

        <div className={styles.layout}>
          <div className={styles.testimonials}>
            {testimonials.map((t) => (
              <article key={t.name} className={styles.card}>
                <p className={styles.quote}>&ldquo;{t.text}&rdquo;</p>
                <footer className={styles.cardFooter}>
                  <strong className={styles.cardName}>{t.name}</strong>
                  <span className={styles.cardMeta}>{t.background} → {t.course}</span>
                </footer>
              </article>
            ))}

            <a
              className={styles.link}
              href="https://www.petsgroomer.ru/vypuskniki/"
              target="_blank"
              rel="noreferrer"
            >
              Все истории выпускников →
            </a>
          </div>

          <div className={styles.preview}>
            <img
              src="/images/master-photo.jpg"
              alt="Мастер Bonshery Groom с собакой"
              loading="lazy"
            />
            <img
              src="/images/group-photo.jpg"
              alt="Студенты и выпускники Академии груминга Боншери"
              loading="lazy"
            />
          </div>
        </div>
      </div>
    </section>
  );
}
