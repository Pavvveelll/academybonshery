import styles from "./FounderSection.module.css";

export function FounderSection() {
  return (
    <section id="founder" className={styles.section}>
      <div className={styles.container}>
        <div className={styles.inner}>
          <div className={styles.photo}>
            <img
              src="/images/master-photo.jpg"
              alt="Основатель академии BONSHERY"
              loading="lazy"
            />
          </div>
          <div className={styles.content}>
            <p className={styles.kicker}>ОСНОВАТЕЛЬ</p>
            <h2 className={styles.title}>Основатель, методолог и наставник преподавателей</h2>
            <p className={styles.text}>
              BONSHERY — это не просто академия. Это система, в которой формируются
              специалисты и преподаватели индустрии груминга.
            </p>
            <p className={styles.text}>
              Я выстроила методологию обучения, по которой работают педагоги на моей
              площадке, передавая единый стандарт качества и результата.
            </p>
            <p className={styles.text}>
              Моя задача — не только обучать мастеров, но и формировать преподавателей,
              способных системно развивать учеников и выводить их в профессию.
            </p>
            <p className={styles.text}>
              Именно поэтому в академии важен не только навык, но и уровень преподавания,
              на котором этот навык передаётся.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}
