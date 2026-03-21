import styles from "./InternationalSection.module.css";

export function InternationalSection() {
  return (
    <section id="international" className={styles.section}>
      <div className={styles.container}>
        <p className={styles.kicker}>МЕЖДУНАРОДНЫЙ ОПЫТ</p>
        <h2 className={styles.title}>Международный уровень и профессиональная среда</h2>
        <p className={styles.text}>
          Академия BONSHERY развивается внутри международной индустрии груминга,
          где формируются актуальные стандарты и подходы к работе.
        </p>
        <p className={styles.text}>
          Мы не только привозим в Россию ведущих специалистов и проводим мастер-классы,
          но и являемся организаторами международного конкурса.
        </p>
        <p className={styles.text}>
          Наши ученики обучаются за границей
          и получают опыт напрямую у мастеров индустрии.
        </p>
        <p className={styles.text}>
          Мы работаем на уровне мировой профессиональной среды
          и интегрируем этот уровень в систему подготовки специалистов.
        </p>
      </div>
    </section>
  );
}
