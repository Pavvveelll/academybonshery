import styles from "./Enrollment.module.css";

const groups = [
  { period: "12-16 марта", course: "Курс Базовый", days: "5 дней" },
  { period: "12-18 марта", course: "Курс Классика", days: "7 дней" },
  { period: "12-21 марта", course: "Курс Интенсив", days: "10 дней" },
];

export function Enrollment() {
  return (
    <section id="enrollment" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.left}>
          <h2 className={styles.title}>Ближайшие группы для записи</h2>
          <p className={styles.text}>
            Места заполняются быстро. Для записи на курс звоните 8 (499) 994-01-40,
            8 (925) 889-99-63 (Viber, WhatsApp, Telegram) или пишите на
            school@petsgroomer.ru.
          </p>
          <a className={styles.callToAction} href="tel:+74999940140">
            Позвонить и записаться
          </a>
        </div>

        <div className={styles.right}>
          <div className={styles.photoWrap}>
            <img
              src="/images/start-potokov.jpg"
              alt="Ближайшие потоки — Академия груминга Боншери"
              loading="lazy"
            />
          </div>

          {groups.map((group) => (
            <article className={styles.groupCard} key={`${group.period}-${group.course}`}>
              <p className={styles.groupDate}>{group.period}</p>
              <h3 className={styles.groupCourse}>{group.course}</h3>
              <p className={styles.groupDays}>{group.days}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
