import styles from "./CourseIntro.module.css";

const points = [
  "отточенная методика, доступная для всех",
  "ежедневная практика по собаке на каждого ученика",
  "достаточное количество профессиональных преподавателей",
  "ВСЕ для учебы предоставляем: собачек, косметику, учебник, инструменты",
  "уютная атмосфера поддержки и комфортной учебы",
];

export function CourseIntro() {
  return (
    <section id="course" className={styles.section}>
      <div className={styles.container}>
        <p className={styles.kicker}>Основной курс обучения грумингу с нуля</p>
        <h2 className={styles.title}>Форматы основного курса</h2>

        <div className={styles.grid}>
          <article className={styles.card}>
            <h3 className={styles.cardTitle}>Базовый</h3>
            <p className={styles.cardMeta}>5 дней обучения</p>
          </article>
          <article className={styles.card}>
            <h3 className={styles.cardTitle}>Классика</h3>
            <p className={styles.cardMeta}>7 дней обучения</p>
          </article>
          <article className={styles.card}>
            <h3 className={styles.cardTitle}>Интенсив</h3>
            <p className={styles.cardMeta}>10 дней обучения</p>
          </article>
        </div>

        <div className={styles.panel}>
          <h3 className={styles.panelTitle}>Все курсы Академии груминга Боншери это:</h3>
          <ul className={styles.list}>
            {points.map((item) => (
              <li key={item}>{item}</li>
            ))}
          </ul>
        </div>

        <div className={styles.visualCard}>
          <img
            src="/images/group-photo.jpg"
            alt="Студенты Академии груминга Боншери"
            loading="lazy"
          />
        </div>
      </div>
    </section>
  );
}
