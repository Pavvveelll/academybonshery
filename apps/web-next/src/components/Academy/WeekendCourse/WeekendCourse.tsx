import styles from "./WeekendCourse.module.css";

const includes = [
  "Полная программа курса Классика, разбитая на субботы и воскресенья",
  "Практика на собаках с первого занятия",
  "Все расходники и модели предоставляются академией",
  "Сертификат академии груминга по итогам обучения",
];

export function WeekendCourse() {
  return (
    <section id="weekend" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.card}>
          <p className={styles.badge}>Курс выходного дня</p>
          <h2 className={styles.title}>Обучение по субботам и воскресеньям</h2>
          <p className={styles.status}>В данный момент набор закрыт</p>
          <p className={styles.text}>
            Формат для тех, кто занят в будни: полная программа курса Классика,
            разбитая на субботы и воскресенья. То же содержание, тот же уровень практики —
            просто другой график.
          </p>
          <ul className={styles.list}>
            {includes.map((item) => (
              <li key={item}>{item}</li>
            ))}
          </ul>
          <p className={styles.cta}>
            Оставьте контакты, чтобы первыми узнать об открытии набора:
          </p>
          <a href="#contacts" className={styles.ctaLink}>
            Записаться в лист ожидания
          </a>
        </div>
      </div>
    </section>
  );
}
